<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Cliente;
use app\models\TipoDeVenta;
use app\models\Usuario;
use app\models\Venta;

class VentaController extends Controller
{
    /**
     * ============================================================================================
     * CONSTRUCTOR
     * ============================================================================================
     */
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
        Auth::verifyModules();
    }

    /**
     * ============================================================================================
     * INDEX
     * Muestra el dashboard de las ventas realizadas
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/ventas/index.css">'.PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/highcharts.js"></script>'.PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/exporting.js"></script>'.PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/export-data.js"></script>'.PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/accessibility.js"></script>'.PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/ventas/index.js"></script>'.PHP_EOL;

        // DATA
        $venta = new Venta();

        $query = "SELECT ventas.created_at AS 'fecha_venta', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'vendedor', COUNT(ventas.id_tipo_de_venta) AS 'total' FROM ventas INNER JOIN usuarios ON ventas.id_usuario_atendio = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id JOIN (SELECT DISTINCT(ventas.created_at) FROM ventas ORDER BY ventas.created_at DESC LIMIT 7) b ON ventas.created_at IN (b.created_at) WHERE ventas.deleted_at IS NULL GROUP BY ventas.created_at, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) ORDER BY ventas.created_at ASC";
        $result = $venta->customQuery($query);
        if (count($result) === 0){
            $data["ventas_ultimos7dias"] = [];
        } else {
            $data["ventas_ultimos7dias"] = $result;
        }

        $query = "SELECT WEEK(ventas.created_at) AS 'semana', COUNT(ventas.id_tipo_de_venta) AS 'total' FROM ventas WHERE YEAR(ventas.created_at) = YEAR(NOW()) AND ventas.deleted_at IS NULL GROUP BY WEEK(ventas.created_at)";
        $result = $venta->customQuery($query);
        if (count($result) === 0){
            $data["ventas_porSemanaEnElannio"] = [];
        } else {
            $data["ventas_porSemanaEnElannio"] = $result;
        }

        $query = "SELECT tipos_de_ventas.tipo AS 'tipo_de_venta', COUNT(ventas.id_tipo_de_venta) AS 'total' FROM ventas INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id WHERE ventas.deleted_at IS NULL AND YEAR(ventas.created_at) = YEAR(NOW()) GROUP BY tipos_de_ventas.tipo ORDER BY COUNT(ventas.id_tipo_de_venta) DESC";
        $result = $venta->customQuery($query);
        if (count($result) === 0){
            $data["ventas_porTipoEnElannio"] = [];
        } else {
            $data["ventas_porTipoEnElannio"] = $result;
        }

        // VIEW
        return $this->render('ventas/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * CREATE
     * Muestra el formulario para registrar una nueva venta en la aplicación
     * ============================================================================================
     */
    public function create(Request $request)
    {
        $cliente = new Cliente();
        $tipoDeVenta = new TipoDeVenta();

        if (!isset($request->getBody()["id"])) {
            $data["cliente"]["parametro"] = false;
            $data["cliente"]["id"] = 0;
            $data["cliente"]["nombre"] = "";
        } else {
            $id = $request->getBody()["id"];
            $result = $cliente->findById($id);

            if (count($result) === 0){
                $data["cliente"]["parametro"] = true;
                $data["cliente"]["id"] = 0;
                $data["cliente"]["nombre"] = "";
            } else {
                $data["cliente"]["parametro"] = true;
                $data["cliente"]["id"] = $id;
                $data["cliente"]["nombre"] = $result[0]["nombre"] . " " . $result[0]["apellido_paterno"];
                if (strlen(trim($result[0]["apellido_materno"])) !== 0) {
                    $data["cliente"]["nombre"] .= " " . $result[0]["apellido_materno"];
                }
            }
        }

        $query = "SELECT clientes.id, CONCAT(clientes.nombre,' ',clientes.apellido_paterno,IF(clientes.apellido_materno != '', CONCAT(' ', clientes.apellido_materno),'')) AS 'nombre' FROM clientes WHERE clientes.deleted_at IS NULL ORDER BY CONCAT(clientes.nombre,' ',clientes.apellido_paterno,IF(clientes.apellido_materno != '', CONCAT(' ', clientes.apellido_materno),'')) ASC";
        $data["clientes"] = $cliente->customQuery($query);

        $query = "SELECT tipos_de_ventas.id, tipos_de_ventas.tipo FROM tipos_de_ventas WHERE tipos_de_ventas.deleted_at IS NULL";
        $data["tipos_de_ventas"] = $tipoDeVenta->customQuery($query);

        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/ventas/create.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/ventas/create.js"></script>';

        // VIEW
        return $this->render('ventas/create', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * STORE
     * Guarda en la BD una nueva venta
     * ============================================================================================
     */
    public function store(Request $request)
    {
        // SE CARGAN LOS DATOS RECIBIDOS
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        if (Usuario::validateToken($token) === false) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }
        unset($data["token"]);

        // SE VALIDA EL CSRF
        $csrf = $data["csrf"];
        $idUsuario = $data["id_usuario"];
        if (Usuario::validateCsrf($csrf, $idUsuario) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
            exit;
        }
        unset($data["csrf"]);

        $venta = new Venta();

        $venta->loadData($data);
        $venta->id_usuario_atendio = $_SESSION["usuario"]["id"];

        // SE VALIDA LA INFORMACIÓN RECIBIDA
        $validacion = $venta->validate();

        // SI LA INFORMACIÓN NO ES VÁLIDA
        if (!$validacion["eval"])
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $validacion["message"]]);
            exit;
        }

        // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
        $result = $venta->create();

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string")
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "boolean")
        {
            if (!$result)
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'La venta fue registrada exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * EDIT
     * Muestra el formulario para editar una venta
     * ============================================================================================
     */
    public function edit(Request $request)
    {
        // SE CARGAN LOS DATOS RECIBIDOS
        $data = $request->getBody();
        unset($data["uri"]);

        if (isset($data["id_venta"]) && isset($data["id_estado_de_visita"])) {
            $data["with"] = true;
            in_array(intval($data["id_estado_de_visita"]),[1,2,5]) ? $data["auth"] = true : $data["auth"] = false;
            $query = "SELECT ventas.id_cliente, CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', ventas.id_tipo_de_venta, ventas.fecha_programada, ventas.observaciones FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id WHERE ventas.id = '". $data["id_venta"] ."' AND ventas.id_estado_de_visita = '". $data["id_estado_de_visita"] ."' AND ventas.deleted_at IS NULL";
            $venta = new Venta();
            $result = $venta->customQuery($query);

            if (count($result) === 0) {
                $data["finded"] = false;
            } else {
                $data["id_cliente"] = $result[0]["id_cliente"];
                $data["cliente"] = $result[0]["cliente"];
                $data["id_tipo_de_venta"] = $result[0]["id_tipo_de_venta"];
                $data["fecha_programada"] = $result[0]["fecha_programada"];
                $data["observaciones"] = $result[0]["observaciones"];
                $data["finded"] = true;
                $tipoDeVenta = new TipoDeVenta();
                $query = "SELECT tipos_de_ventas.id, tipos_de_ventas.tipo FROM tipos_de_ventas WHERE tipos_de_ventas.deleted_at IS NULL";
                $data["tipos_de_ventas"] = $tipoDeVenta->customQuery($query);
            }
        } else {
            $data["with"] = false;
        }

        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/ventas/editar.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/ventas/editar.js"></script>';

        // VIEW
        return $this->render('ventas/editar', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * UPDATE
     * Actualiza una venta en la BD
     * ============================================================================================
     */
    public function update(Request $request)
    {
        // SE CARGAN LOS DATOS RECIBIDOS
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        if (Usuario::validateToken($token) === false) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }
        unset($data["token"]);

        // SE VALIDA EL CSRF
        $csrf = $data["csrf"];
        $idUsuario = $data["id_usuario"];
        if (Usuario::validateCsrf($csrf, $idUsuario) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
            exit;
        }
        unset($data["csrf"]);

        $venta = new Venta();

        if ($data["action"] === "update") {
            $query = "UPDATE ventas SET id_tipo_de_venta = '". $data["id_tipo_de_venta"] ."', fecha_programada = '". $data["fecha_programada"] ."', observaciones = '". $data["observaciones"] ."', id_estado_de_visita = 1, id_tecnico_asignado = 0, id_tecnico_visito = 0 WHERE id = ". $data["id_venta"];
        }

        if ($data["action"] === "delete") {
            $date = date('Y-m-d');
            $query = "UPDATE ventas SET deleted_at = '". $date ."' WHERE id = ". $data["id_venta"];
        }

        // SI LA INFORMACIÓN ES VÁLIDA, SE ACTUALIZA EN LA BD
        $result = $venta->customQuery($query);

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string")
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "boolean")
        {
            if (!$result)
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        if ($data["action"] === "update") {
            echo json_encode(['response' => true, 'message' => 'La venta fue actualizada exitosamente.']);
        }

        if ($data["action"] === "delete") {
            echo json_encode(['response' => true, 'message' => 'La venta fue eliminada exitosamente.']);
        }

        exit;
    }

    /**
     * ============================================================================================
     * READ
     * Muestra el formulario para consultar la ventas registradas
     * ============================================================================================
     */
    public function read()
    {
        // CSS
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/ventas/consultar.css">';

        // JS
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/ventas/consultar.js"></script>';

        // DATA
        $venta = new Venta();
        $query = "SELECT ventas.id AS 'id', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'vendedor', CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo AS 'venta', ventas.created_at AS 'fecha_venta', ventas.fecha_programada AS 'fecha_programada', estados_de_visitas.id AS 'id_estado_de_visita', estados_de_visitas.estado_de_visita AS 'estado_de_visita' FROM ventas INNER JOIN usuarios ON ventas.id_usuario_atendio = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id WHERE ventas.deleted_at IS NULL ORDER BY ventas.id ASC LIMIT 5";
        $result = $venta->customQuery($query);
        $query = "SELECT COUNT(id) AS 'total' FROM ventas WHERE deleted_at IS NULL";
        $resultB = $venta->customQuery($query);
        if (gettype($result) == "string") {
            $data[0] = 0;
            $data[1] = $result;
        } else {
            $data[0] = intval($resultB[0]["total"]);
            $data[1] = $result;
        }

        // VIEW
        return $this->render('ventas/consultar', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * READ
     * Muestra el formulario para generar un reporte de ventas
     * ============================================================================================
     */
    public function reportes(Request $request)
    {
        // CSS
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/ventas/reportes.css">'.PHP_EOL;

        // JS
        $js = '<script src="'. ENTORNO .'/public/js/tableToExcel.js"></script>'.PHP_EOL;
        $js .= '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/ventas/reportes.js"></script>'.PHP_EOL;

        // DATA
        $ventas = new Venta();

        $query = "SELECT DISTINCT(ventas.id_usuario_atendio) AS 'id_vendedor', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'vendedor' FROM ventas INNER JOIN usuarios ON ventas.id_usuario_atendio = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE ventas.deleted_at IS NULL ORDER BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno)";
        $result = $ventas->customQuery($query);
        if (count($result) == 0) {
            $data["vendedores"] = [];
        } else {
            $data["vendedores"] = $result;
        }

        $query = "SELECT DISTINCT(ventas.id_tipo_de_venta) AS 'id_tipo_de_venta', tipos_de_ventas.tipo AS 'tipo_de_venta' FROM ventas INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id WHERE ventas.deleted_at IS NULL ORDER BY tipos_de_ventas.tipo ASC";
        $result = $ventas->customQuery($query);
        if (count($result) == 0) {
            $data["tipos_de_ventas"] = [];
        } else {
            $data["tipos_de_ventas"] = $result;
        }

        $data["fecha_inicio"] = "2022-11-05";
        $data["fecha_termino"] = date("Y-m-d");

        // VIEW
        return $this->render('ventas/reportes', $css, $js, $data);
    }
}