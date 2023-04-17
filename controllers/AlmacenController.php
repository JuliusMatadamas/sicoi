<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Inventario;
use app\models\Organizacion;
use app\models\Usuario;
use Exception;

class AlmacenController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
        Auth::verifyModules();
    }

    public function categoria()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('almacen/categoria', $css, $js, $data);
    }

    public function consultar()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('almacen/consultar/index', $css, $js, $data);
    }

    public function dashboard()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/almacen/dashboard/index.css">' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/highcharts.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/data.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/drilldown.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/exporting.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/export-data.js"></script>' . PHP_EOL;
        $css .= '<script src="' . ENTORNO . '/public/js/modules/accessibility.js"></script>' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/almacen/dashboard/index.js"></script>' . PHP_EOL;

        // DATA
        $inventario = new Inventario();

        $query = "SELECT categorias.id, categorias.categoria FROM categorias WHERE categorias.deleted_at IS NULL ORDER BY categorias.categoria ASC";
        $categorias = $inventario->customQuery($query);

        $query = "SELECT productos.id, productos.nombre, categorias.id AS 'id_categoria' FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.deleted_at IS NULL ORDER BY productos.id ASC";
        $productos = $inventario->customQuery($query);

        for ($i = 0; $i < count($categorias); $i++) {
            $query = "SELECT SUM(inventario.cantidad) AS 'entradas' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_destino = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND inventario.id_estado_de_inventario_destino = 1 AND categorias.id = " . $categorias[$i]["id"];
            $entradas = intval($inventario->customQuery($query)[0]["entradas"]);

            $query = "SELECT SUM(inventario.cantidad) AS 'salidas' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_origen = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND categorias.id = " . $categorias[$i]["id"];
            $salidas = intval($inventario->customQuery($query)[0]["salidas"]);

            $categorias[$i]["y"] = $entradas - $salidas;

            if ($categorias[$i]["y"] > 0) {
                $query = "SELECT productos.id, productos.nombre, SUM(inventario.cantidad) AS 'entradas' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_destino = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND inventario.id_estado_de_inventario_destino = 1 AND categorias.id = " . $categorias[$i]["id"] . " GROUP BY productos.id";
                $results = $inventario->customQuery($query);

                for ($j = 0; $j < count($productos); $j++) {
                    for ($k = 0; $k < count($results); $k++) {
                        if ($results[$k]["id"] == $productos[$j]["id"]) {
                            $productos[$j]["total"] = $results[$k]["entradas"];
                        }
                    }
                }

                $query = "SELECT productos.id, productos.nombre, SUM(inventario.cantidad) AS 'salidas' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_origen = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND categorias.id = " . $categorias[$i]["id"] . " GROUP BY productos.id";
                $results = $inventario->customQuery($query);

                for ($j = 0; $j < count($productos); $j++) {
                    for ($k = 0; $k < count($results); $k++) {
                        if ($results[$k]["id"] == $productos[$j]["id"]) {
                            $productos[$j]["total"] = $productos[$j]["total"] - $results[$k]["salidas"];
                        }
                    }
                }

                for ($j = 0; $j < count($productos); $j++) {
                    if ($productos[$j]["id_categoria"] == $categorias[$i]["id"] && isset($productos[$j]["total"])) {
                        $categorias[$i]["data"][] = $productos[$j];
                    }
                }
            }
        }

        $totalProductos = 0;

        for ($i = 0; $i < count($categorias); $i++) {
            if (isset($categorias[$i]["data"])) {
                $totalProductos = $totalProductos + count($categorias[$i]["data"]);
            }
        }

        for ($i = 0; $i < count($categorias); $i++) {
            if (isset($categorias[$i]["data"])) {
                $categorias[$i]["y"] = (count($categorias[$i]["data"]) / $totalProductos) * 100;
            }
        }

        for ($i = 0; $i < count($categorias); $i++) {
            if (isset($categorias[$i]["data"])) {
                $data[] = $categorias[$i];
            }
        }

        // VIEW
        return $this->render('almacen/dashboard/index', $css, $js, $data);
    }

    public function entradas()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/almacen/entradas/index.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/almacen/entradas/index.js"></script>' . PHP_EOL;

        // DATA
        $organizacion = new Organizacion();

        $query = "SELECT organizaciones.id, organizaciones.organizacion, organizaciones.descripcion FROM organizaciones WHERE organizaciones.organizacion != 'AMT' AND organizaciones.deleted_at IS NULL ORDER BY organizaciones.descripcion ASC";
        $data["organizaciones_externas"] = $organizacion->customQuery($query);

        $query = "SELECT estados_de_inventarios.id, estados_de_inventarios.estado_de_inventario FROM estados_de_inventarios WHERE estados_de_inventarios.deleted_at IS NULL ORDER BY estados_de_inventarios.estado_de_inventario ASC";
        $data["estados_de_inventarios"] = $organizacion->customQuery($query);

        $query = "SELECT categorias.id, categorias.categoria FROM categorias WHERE categorias.deleted_at IS NULL ORDER BY categorias.categoria ASC";
        $data["categorias"] = $organizacion->customQuery($query);

        // VIEW
        return $this->render('almacen/entradas/index', $css, $js, $data);
    }

    public function registrarEntradas(Request $request)
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

        $mainArray = [];
        $subArray = [];
        $items = explode("|", $data["items"]);
        foreach ($items as $key => $value) {
            $mainArray[$key] = explode(",", $value);
            foreach ($mainArray[$key] as $k => $v) {
                $prods = explode(":", $v);
                $subArray[$prods[0]] = $prods[1];
            }
            $mainArray[$key] = $subArray;
        }

        $inventario = new Inventario();

        $errors = "No se pudo registrar lo siguiente: ";
        $contErrors = 0;
        foreach ($mainArray as $key => $item) {
            $inventario->loadData($item);

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $inventario->create();

            // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "string") {
                $errors .= $item["producto"] . "(" . $item["numero_de_serie"] . ")(" . $item["cantidad"] . "), ";
                $contErrors++;
            }

            // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "boolean") {
                if (!$result) {
                    $errors .= $item["producto"] . "(" . $item["numero_de_serie"] . ")(" . $item["cantidad"] . "), ";
                    $contErrors++;
                }
            }
        }

        if ($contErrors > 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => substr($errors, 0, -2) . "."]);
            exit;
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'Se registró correctamente el inventario en la BD.']);
        exit;
    }

    public function inventarios()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('almacen/inventarios/index', $css, $js, $data);
    }

    public function productos()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('almacen/productos', $css, $js, $data);
    }

    public function reportes()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('almacen/reportes/index', $css, $js, $data);
    }

    public function salidas()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/almacen/salidas/index.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/almacen/salidas/index.js"></script>' . PHP_EOL;

        // DATA
        $organizaciones = new Organizacion();

        $query = "SELECT organizaciones.id, organizaciones.organizacion, organizaciones.descripcion FROM organizaciones WHERE organizaciones.organizacion NOT LIKE '%AMT%' AND organizaciones.descripcion NOT LIKE '%CEDIS%' ORDER BY organizaciones.descripcion ASC";
        $data["organizaciones_internas"] = $organizaciones->customQuery($query);

        $query = "SELECT empleados.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'organizacion', puestos.puesto AS 'descripcion' FROM empleados INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE puestos.id = 8 AND empleados.deleted_at IS NULL ORDER BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno) ASC";
        $results = $organizaciones->customQuery($query);
        foreach ($results as $result) {
            array_push($data["organizaciones_internas"], $result);
        }

        $query = "SELECT estados_de_inventarios.id, estados_de_inventarios.estado_de_inventario FROM estados_de_inventarios WHERE estados_de_inventarios.id NOT LIKE 1 AND estados_de_inventarios.deleted_at IS NULL ORDER BY estados_de_inventarios.estado_de_inventario ASC";
        $data["estados_de_inventarios"] = $organizaciones->customQuery($query);

        $query = "SELECT categorias.id, categorias.categoria FROM categorias WHERE categorias.deleted_at IS NULL ORDER BY categorias.categoria ASC";
        $data["categorias"] = $organizaciones->customQuery($query);

        $query = "SELECT categorias.id, categorias.categoria, SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.id_inventario_destino = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND inventario.id_estado_de_inventario_destino = 1 AND inventario.deleted_at IS NULL GROUP BY categorias.id ORDER BY categorias.categoria ASC";
        $data["categorias_disponibles"] = $organizaciones->customQuery($query);

        $query = "SELECT categorias.id, categorias.categoria, SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.id_inventario_origen = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND inventario.deleted_at IS NULL GROUP BY categorias.id ORDER BY categorias.categoria ASC";
        $data["categorias_salidas"] = $organizaciones->customQuery($query);

        $data["categorias_con_existencias"] = [];
        for ($i = 0; $i < count($data["categorias"]); $i++) {
            $cantidadDisponible = 0;
            $cantidadSalida = 0;
            if (isset($data["categorias_disponibles"][$i]["id"])) {
                $cantidadDisponible = $data["categorias_disponibles"][$i]["total"];
            }
            if (isset($data["categorias_salidas"][$i]["id"])) {
                $cantidadSalida = $data["categorias_salidas"][$i]["total"];
            }
            $total = $cantidadDisponible - $cantidadSalida;

            if ($total > 0) {
                $data["categorias_con_existencias"][$i]["id"] = $data["categorias"][$i]["id"];
                $data["categorias_con_existencias"][$i]["categoria"] = $data["categorias"][$i]["categoria"];
                $data["categorias_con_existencias"][$i]["total"] = $total;
            }
        }

        // VIEW
        return $this->render('almacen/salidas/index', $css, $js, $data);
    }

    public function registrarSalidas(Request $request)
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

        $mainArray = [];
        $subArray = [];
        $items = explode("|", $data["items"]);
        foreach ($items as $key => $value) {
            $mainArray[$key] = explode(",", $value);
            foreach ($mainArray[$key] as $k => $v) {
                $prods = explode(":", $v);
                $subArray[$prods[0]] = $prods[1];
            }
            $mainArray[$key] = $subArray;
        }

        $inventario = new Inventario();

        $errors = "No se pudo registrar lo siguiente: ";
        $contErrors = 0;
        foreach ($mainArray as $key => $item) {
            $inventario->loadData($item);

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $inventario->fecha_destino = NULL;
            $result = $inventario->create();

            // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "string") {
                $errors .= $item["producto"] . "(" . $item["numero_de_serie"] . ")(" . $item["cantidad"] . "), ";
                $contErrors++;
            }

            // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "boolean") {
                if (!$result) {
                    $errors .= $item["producto"] . "(" . $item["numero_de_serie"] . ")(" . $item["cantidad"] . "), ";
                    $contErrors++;
                }
            }
        }

        if ($contErrors > 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $errors]);
            exit;
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'Se registró correctamente el inventario en la BD.']);
        exit;
    }
}