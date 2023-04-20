<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Usuario;
use app\models\Venta;

class SupervisionController extends Controller
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

    public function asignar_visitas()
    {
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/supervision/asignar_visitas.css">' . PHP_EOL;
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/supervision/asignar_visitas.js"></script>' . PHP_EOL;

        $venta = new Venta();

        // VENTAS POR VISITAR
        $query = "SELECT ventas.id, CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', colonias.colonia, clientes.calle, codigos_postales.codigo_postal, tipos_de_ventas.tipo AS 'tipo_de_venta', ventas.fecha_programada FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id WHERE ventas.id_tecnico_asignado = 0 AND ventas.fecha_visita IS NULL AND ventas.deleted_at IS NULL ORDER BY ventas.fecha_programada ASC LIMIT 5";
        $data["ventas"] = $venta->customQuery($query);
        $query = "SELECT COUNT(ventas.id) AS 'total' FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id WHERE ventas.id_tecnico_asignado = 0 AND ventas.fecha_visita IS NULL AND ventas.deleted_at IS NULL ORDER BY ventas.fecha_programada";
        $data["total"] = $venta->customQuery($query);
        $query = "SELECT usuarios.id AS 'id_tecnico', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'tecnico' FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE usuarios.deleted_at IS NULL AND puestos.id = 8 ORDER BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno) ASC";
        $data["tecnicos"] = $venta->customQuery($query);

        return $this->render('supervision/asignar_visitas', $css, $js, $data);
    }

    public function registrar_asignacion(Request $request)
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

        $idVenta = $data["id_venta"];
        $idTecnico = $data["id_tecnico"];
        $venta = new Venta();

        $query = "UPDATE ventas SET ventas.id_tecnico_asignado = $idTecnico  WHERE ventas.id = $idVenta";
        $result = $venta->customUpdate($query);

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string") {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "boolean") {
            if (!$result) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'Se asignó el técnico a la venta exitosamente.']);
        exit;
    }

    public function consultar_asignaciones()
    {
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/supervision/consultar_asignaciones.css">' . PHP_EOL;
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/supervision/consultar_asignaciones.js"></script>' . PHP_EOL;

        $venta = new Venta();
        $query = "SELECT ventas.id, CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'tecnico_asignado', ventas.fecha_programada, estados_de_visitas.estado_de_visita, ventas.observaciones FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN usuarios ON ventas.id_tecnico_asignado = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id WHERE ventas.deleted_at IS NULL ORDER BY id ASC LIMIT 5";
        $data["visitas_programadas"] = $venta->customQuery($query);

        $query = "SELECT COUNT(ventas.id) AS 'total' FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN usuarios ON ventas.id_tecnico_asignado = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id WHERE ventas.deleted_at IS NULL";
        $data["total_visitas"] = $venta->customQuery($query);

        return $this->render('supervision/consultar_asignaciones', $css, $js, $data);
    }

    public function modificar_asignacion(Request $request)
    {
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/supervision/modificar_asignacion.css">' . PHP_EOL;
        $js = '<script src="' . ENTORNO . '/public/js/supervision/modificar_asignacion.js"></script>' . PHP_EOL;

        $venta = new Venta();

        $query = "SELECT usuarios.id AS 'id_tecnico', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'tecnico' FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE usuarios.deleted_at IS NULL AND puestos.id = 8 ORDER BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno) ASC";
        $data["tecnicos"] = $venta->customQuery($query);
        $data["id"] = $request->getBody()["id"];
        $query = "SELECT CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo AS 'tipo_de_venta', ventas.fecha_programada, codigos_postales.codigo_postal, colonias.colonia, clientes.calle, ventas.id_tecnico_asignado FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id WHERE ventas.id = " . $data["id"];
        $data["asignacion"] = $venta->customQuery($query);

        return $this->render('supervision/modificar_asignacion', $css, $js, $data);
    }

    public function actualizar_asignacion(Request $request)
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

        $idVenta = $data["id_venta"];
        $idTecnico = $data["id_tecnico"];
        $venta = new Venta();

        $query = "UPDATE ventas SET ventas.id_tecnico_asignado = $idTecnico  WHERE ventas.id = $idVenta";
        $result = $venta->customUpdate($query);

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string") {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "boolean") {
            if (!$result) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'Se modificó la asignación exitosamente.']);
        exit;
    }

    public function dashboard()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/supervision/dashboard.css">' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/highcharts.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/data.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/drilldown.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/variable-pie.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/exporting.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/export-data.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/accessibility.js"></script>' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/supervision/dashboard.js"></script>' . PHP_EOL;

        // DATA
        $venta = new Venta();

        $query = "SELECT estados_de_visitas.estado_de_visita, COUNT(ventas.id_estado_de_visita) AS 'total', ROUND((COUNT(ventas.id_estado_de_visita) / (SELECT COUNT(ventas.id) FROM ventas WHERE ventas.deleted_at IS NULL)) * 100,2) AS 'porcentaje' FROM ventas INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id WHERE ventas.deleted_at IS NULL GROUP BY ventas.id_estado_de_visita";
        $data["radius-pie"] = $venta->customQuery($query);

        $query = "SELECT ventas.id_tecnico_asignado, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'tecnico', ROUND((COUNT(ventas.id_estado_de_visita) / (SELECT COUNT(ventas.id) FROM ventas WHERE ventas.deleted_at IS NULL)) * 100,2) AS 'porcentaje' FROM ventas INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id INNER JOIN usuarios ON ventas.id_tecnico_asignado = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE ventas.deleted_at IS NULL GROUP BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno)";
        $data["resultados"] = $venta->customQuery($query);

        $data["tecnicos"] = [];
        foreach ($data["resultados"] as $tecnico) {
            $id = intval($tecnico["id_tecnico_asignado"]);
            $query = "SELECT estados_de_visitas.estado_de_visita, ROUND((COUNT(ventas.id) / (SELECT COUNT(ventas.id) FROM ventas WHERE ventas.id_tecnico_asignado = $id AND ventas.deleted_at IS NULL)) * 100, 2) AS 'porcentaje' FROM ventas INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id WHERE ventas.id_tecnico_asignado = $id AND ventas.deleted_at IS NULL GROUP BY estados_de_visitas.estado_de_visita";
            $tecnico["detalle"] = $venta->customQuery($query);
            array_push($data["tecnicos"], $tecnico);
        }

        // VIEW
        return $this->render('supervision/dashboard', $css, $js, $data);
    }

    public function reportes()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('supervision/reportes', $css, $js, $data);
    }

    public function ubicacion_de_tecnicos()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/supervision/ubicacion_de_tecnicos.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/supervision/ubicacion_de_tecnicos.js"></script>' . PHP_EOL;
        $js .= '<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlEkZjkKQU93koBHdMWSyKxdYAqedosY4&callback=initMap"></script>' . PHP_EOL;

        // DATA
        $ventas = new Venta();

        $query = "SELECT MIN(ventas.fecha_visita) AS 'fecha_min' FROM ventas";

        $data["fecha_min"] = $ventas->customQuery($query)[0]["fecha_min"];
        $data["fecha_max"] = date("Y-m-d");

        $query = "SELECT ventas.id_tecnico_visito AS 'id_tecnico', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'tecnico' FROM ventas INNER JOIN usuarios ON ventas.id_tecnico_visito = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE ventas.deleted_at IS NULL AND ventas.id_tecnico_visito IS NOT NULL GROUP BY ventas.id_tecnico_visito ORDER BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno) ASC";
        $data["tecnicos"] = $ventas->customQuery($query);

        // VIEW
        return $this->render('supervision/ubicacion_de_tecnicos', $css, $js, $data);
    }

    public function save(Request $request)
    {
    }
}