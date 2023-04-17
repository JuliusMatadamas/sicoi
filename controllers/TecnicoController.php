<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Inventario;
use app\models\Producto;
use app\models\Usuario;
use app\models\Venta;

class TecnicoController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
        Auth::verifyModules();
    }

    public function dashboard()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('tecnicos/dashboard', $css, $js, $data);
    }

    public function entradas()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/tecnicos/entradas.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/tecnicos/entradas.js"></script>' . PHP_EOL;

        // DATA
        $idUsuario = $_SESSION["usuario"]["id"];
        $inventario = new Inventario();

        $query = "SELECT inventario.id, categorias.categoria, productos.nombre, productos.descripcion, inventario.id_inventario_origen, inventario.fecha_origen, inventario.numero_de_serie, inventario.cantidad FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.id_inventario_destino = '$idUsuario' AND inventario.id_estado_de_inventario_destino = 3 ORDER BY categorias.id";
        $data["entradas"] = $inventario->customQuery($query);

        for ($i = 0; $i < count($data["entradas"]); $i++) {
            $idInventarioOrigen = $data["entradas"][$i]["id_inventario_origen"];
            $query = "SELECT organizaciones.descripcion AS 'inventario_origen' FROM organizaciones WHERE id = '$idInventarioOrigen'";
            $result = $inventario->customQuery($query);

            if (count($result) !== 0) {
                $data["entradas"][$i]["inventario_origen"] = $result[0]["inventario_origen"];
            } else {
                $query = "SELECT CONCAT('Cliente: ', clientes.nombre, ' ', clientes.apellido_paterno) AS 'inventario_origen' FROM clientes WHERE clientes.id = '$idInventarioOrigen'";
                $result = $inventario->customQuery($query);

                if (count($result) !== 0) {
                    $data["entradas"][$i]["inventario_origen"] = $result[0]["inventario_origen"];
                } else {
                    $query = "SELECT CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'inventario_origen' FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id WHERE usuarios.id = '$idInventarioOrigen'";
                    $result = $inventario->customQuery($query);

                    if (count($result) !== 0) {
                        $data["entradas"][$i]["inventario_origen"] = $result[0]["inventario_origen"];
                    } else {
                        $data["entradas"][$i]["inventario_origen"] = "N/A";
                    }
                }
            }
        }

        // VIEW
        return $this->render('tecnicos/entradas', $css, $js, $data);
    }

    public function registrar_entradas(Request $request)
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

        $ids = explode("|", $data["ids"]);

        foreach ($ids as $id) {
            $inventario = new Inventario();
            $date = date("Y-m-d");
            $query = "UPDATE inventario SET inventario.id_estado_de_inventario_destino = 1, inventario.fecha_destino = '$date' WHERE inventario.id = '$id'";

            // SI LA INFORMACIÓN ES VÁLIDA, SE ACTUALIZA EN LA BD
            $result = $inventario->customQuery($query);

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
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'Se registraron las entradas en tu inventario correctamente.']);
        exit;
    }

    public function mi_inventario()
    {
        $producto = new Producto();

        $query = "SELECT productos.id, categorias.categoria, productos.nombre, productos.descripcion FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.deleted_at IS NULL";
        $productos = $producto->customQuery($query);

        $query = "SELECT inventario.id_producto AS 'id', SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_destino = '9' AND inventario.id_estado_de_inventario_destino = 1 GROUP BY inventario.id_producto";
        $productos_disponibles = $producto->customQuery($query);

        for ($i = 0; $i < count($productos); $i++) {
            for ($j = 0; $j < count($productos_disponibles); $j++) {
                if ($productos_disponibles[$j]["id"] == $productos[$i]["id"]) {
                    $productos[$i]["total"] = $productos_disponibles[$j]["total"];
                }
            }
        }

        $query = "SELECT inventario.id_producto AS 'id', SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_origen = '9' GROUP BY inventario.id_producto";
        $productos_salidas = $producto->customQuery($query);

        for ($i = 0; $i < count($productos); $i++) {
            for ($j = 0; $j < count($productos_salidas); $j++) {
                if ($productos_salidas[$j]["id"] == $productos[$i]["id"]) {
                    if (isset($productos[$i]["total"])) {
                        $productos[$i]["total"] = intval($productos[$i]["total"]) - intval($productos_salidas[$j]["total"]);
                    }
                }
            }
        }

        $data["inventario"] = [];
        for ($i = 0; $i < count($productos); $i++) {
            if (isset($productos[$i]["total"])) {
                $data["inventario"][] = $productos[$i];
            }
        }

        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/tecnicos/mi_inventario.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/tecnicos/mi_inventario.js"></script>' . PHP_EOL;

        // VIEW
        return $this->render('tecnicos/mi_inventario', $css, $js, $data);
    }

    public function registrar_ubicacion()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('tecnicos/registrar_ubicacion', $css, $js, $data);
    }

    public function registrar_visita()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('tecnicos/registrar_visita', $css, $js, $data);
    }

    public function reportes()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('tecnicos/reportes', $css, $js, $data);
    }

    public function salidas(Request $request)
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/tecnicos/salidas.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/tecnicos/salidas.js"></script>' . PHP_EOL;

        $producto = new Producto();
        $idInventarioDestino = $_SESSION["usuario"]["id"];

        $query = "SELECT productos.id, categorias.id AS 'id_categoria', categorias.categoria, productos.nombre, productos.descripcion FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.deleted_at IS NULL";
        $productos = $producto->customQuery($query);

        $query = "SELECT inventario.id_producto AS 'id', SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_destino = '$idInventarioDestino' AND inventario.id_estado_de_inventario_destino = 1 GROUP BY inventario.id_producto";
        $productos_disponibles = $producto->customQuery($query);

        for ($i = 0; $i < count($productos); $i++) {
            for ($j = 0; $j < count($productos_disponibles); $j++) {
                if ($productos_disponibles[$j]["id"] == $productos[$i]["id"]) {
                    $productos[$i]["total"] = $productos_disponibles[$j]["total"];
                }
            }
        }

        $query = "SELECT inventario.id_producto AS 'id', SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_origen = '$idInventarioDestino' GROUP BY inventario.id_producto";
        $productos_salidas = $producto->customQuery($query);

        for ($i = 0; $i < count($productos); $i++) {
            for ($j = 0; $j < count($productos_salidas); $j++) {
                if ($productos_salidas[$j]["id"] == $productos[$i]["id"]) {
                    if (isset($productos[$i]["total"])) {
                        $productos[$i]["total"] = intval($productos[$i]["total"]) - intval($productos_salidas[$j]["total"]);
                    }
                }
            }
        }

        $data["inventario"] = [];
        for ($i = 0; $i < count($productos); $i++) {
            if (isset($productos[$i]["total"])) {
                $data["inventario"][] = $productos[$i];
            }
        }

        $query = "SELECT productos.nombre, productos.descripcion, inventario.numero_de_serie FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE categorias.id = 3 AND inventario.id_inventario_destino = '$idInventarioDestino' AND inventario.id_estado_de_inventario_origen = 1 ORDER BY productos.nombre, productos.descripcion, inventario.numero_de_serie ASC";
        $decodificadores = $producto->customQuery($query);

        for ($i = 0; $i < count($decodificadores); $i++) {
            $serie = $decodificadores[$i]["numero_de_serie"];
            $query = "SELECT inventario.id_inventario_destino, inventario.id_estado_de_inventario_destino FROM inventario WHERE inventario.numero_de_serie = '$serie' ORDER BY inventario.id DESC LIMIT 1";
            $resultado = $producto->customQuery($query);

            if ($resultado[0]["id_inventario_destino"] == $idInventarioDestino && $resultado[0]["id_estado_de_inventario_destino"] == 1) {
                $data["decodificadores"][] = $decodificadores[$i];
            }
        }

        return $this->render('tecnicos/salidas', $css, $js, $data);
    }

    public function registrar_salidas(Request $request)
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

        $data["salidas"] = explode("|", $data["salidas"]);

        $errors = 0;
        foreach ($data["salidas"] as $salida) {
            $salida = explode(",", $salida);
            $inventario = new Inventario();
            $inventario->id_usuario = $_SESSION["usuario"]["id"];
            $inventario->id_inventario_origen = $data["id_usuario"];
            $inventario->id_estado_de_inventario_origen = '3';
            $inventario->fecha_origen = date("Y-m-d");
            $inventario->id_inventario_destino = $data["id_cliente"];
            $inventario->id_estado_de_inventario_destino = '2';
            $inventario->fecha_destino = date("Y-m-d");
            $inventario->id_producto = explode(":", $salida[0])[1];
            $inventario->numero_de_serie = explode(":", $salida[1])[1];
            $inventario->cantidad = explode(":", $salida[2])[1];
            $inventario->created_at = date("Y-m-d");

            $result = $inventario->create();

            // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "string") {
                $errors++;
            }

            // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "boolean") {
                if (!$result) {
                    $errors++;
                }
            }
        }

        if ($errors > 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'Hubo algunos errores al registrar las salidas, revisa tu inventario']);
            exit;
        }

        echo json_encode(['response' => true, 'message' => 'Se registraron las salidas de tu inventario correctamente.']);
        exit;
    }

    public function visitas_asignadas()
    {
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/tecnicos/visitas_asignadas.css">' . PHP_EOL;
        $js = '<script src="' . ENTORNO . '/public/js/tecnicos/visitas_asignadas.js"></script>' . PHP_EOL;

        $venta = new Venta();

        // VENTAS POR VISITAR
        $idTecnico = $_SESSION["usuario"]["id"];
        $query = "SELECT ventas.id, clientes.id AS 'id_cliente', CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo AS 'tipo_de_venta', ventas.fecha_programada, colonias.colonia, clientes.telefono_casa, clientes.telefono_celular, clientes.calle, clientes.numero_exterior, clientes.numero_interior, clientes.observaciones FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN colonias ON clientes.id_colonia = colonias.id WHERE ventas.id_tecnico_asignado = $idTecnico AND (ventas.id_estado_de_visita = 1 OR ventas.id_estado_de_visita = 2) AND ventas.deleted_at IS NULL ORDER BY ventas.fecha_programada ASC";
        $data["visitas_asignadas"] = $venta->customQuery($query);

        return $this->render('tecnicos/visitas_asignadas', $css, $js, $data);
    }

    public function modificar_visita(Request $request)
    {
        $data["id_venta"] = $request->getBody()["id_venta"];
        $data["id_cliente"] = $request->getBody()["id_cliente"];

        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/tecnicos/modificar_visita.css">' . PHP_EOL;

        // js
        $js = '<script src="' . ENTORNO . '/public/js/tecnicos/modificar_visita.js"></script>' . PHP_EOL;

        $venta = new Venta();

        $query = "SELECT ventas.id, CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo AS 'tipo_de_venta', ventas.fecha_programada, colonias.colonia, clientes.telefono_casa, clientes.telefono_celular, clientes.calle, clientes.numero_exterior, clientes.numero_interior, clientes.observaciones FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN colonias ON clientes.id_colonia = colonias.id WHERE ventas.id = " . $data["id_venta"];
        $data["visita"] = $venta->customQuery($query);
        $data["min"] = date("Y-m-d", strtotime("+1 days", strtotime(date("Y-m-d"))));
        $data["max"] = date("Y-m-d", strtotime("+14 days", strtotime($data["min"])));

        return $this->render('tecnicos/modificar_visita', $css, $js, $data);
    }

    public function actualizar_visita(Request $request)
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

        $id = $data["id_venta"];
        $fechaProgramada = $data["fecha_programada"];
        $fechaVisita = date("Y-m-d");
        $idEstadoDeVisita = intval($data["id_estado_de_visita"]);
        $latitud = $data["latitud"];
        $longitud = $data["longitud"];
        $observaciones = trim($data["observaciones"]);

        $query = "UPDATE ventas SET ventas.fecha_programada = '$fechaProgramada', ventas.fecha_visita = '$fechaVisita', ventas.id_estado_de_visita = $idEstadoDeVisita, ventas.latitud = '$latitud', ventas.longitud = '$longitud', ventas.observaciones = '$observaciones', ventas.id_tecnico_visito = $idUsuario WHERE ventas.id = $id";

        // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
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
        echo json_encode(['response' => true, 'message' => 'Se actualizó el estado de la visita correctamente.']);
        exit;
    }
}