<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Inventario;
use app\models\Usuario;

class ApiAlmacenController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function reportePorOrganizacion(Request $request)
    {
        // SE RECIBEN LOS DATOS
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

        $idOrganizacion = $data["id_organizacion"];

        $inventario = new Inventario();

        $query = "SELECT organizaciones.id, CONCAT(organizaciones.organizacion,' - ',organizaciones.descripcion) AS 'organizacion' FROM organizaciones WHERE organizaciones.deleted_at IS NULL UNION SELECT usuarios.id, CONCAT('Técnico: ',empleados.nombre,' ',empleados.apellido_paterno) AS 'organizacion' FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE usuarios.deleted_at IS NULL AND puestos.id = 8 UNION SELECT clientes.id, CONCAT('Cliente: ', clientes.nombre,' ',clientes.apellido_paterno) AS 'organizacion' FROM clientes WHERE clientes.deleted_at IS NULL";
        $resultados = $inventario->customQuery($query);
        $organizaciones = [];
        foreach ($resultados as $resultado) {
            $organizaciones[$resultado["id"]] = $resultado["organizacion"];
        }

        $query = "SELECT estados_de_inventarios.id, estados_de_inventarios.estado_de_inventario FROM estados_de_inventarios WHERE estados_de_inventarios.deleted_at IS NULL";
        $resultados = $inventario->customQuery($query);
        $estadosDeInventarios = [];
        foreach ($resultados as $resultado) {
            $estadosDeInventarios[$resultado["id"]] = $resultado["estado_de_inventario"];
        }

        if ($idOrganizacion === "0") {
            $query = "SELECT inventario.id_inventario_origen, inventario.id_estado_de_inventario_origen, inventario.fecha_origen, inventario.id_inventario_destino, inventario.id_estado_de_inventario_destino, inventario.fecha_destino, inventario.id_producto, inventario.numero_de_serie, inventario.cantidad FROM inventario";
        } else {
            $query = "SELECT inventario.id_inventario_origen, inventario.id_estado_de_inventario_origen, inventario.fecha_origen, inventario.id_inventario_destino, inventario.id_estado_de_inventario_destino, inventario.fecha_destino, inventario.id_producto, inventario.numero_de_serie, inventario.cantidad FROM inventario WHERE inventario.id_inventario_origen = '$idOrganizacion' UNION SELECT inventario.id_inventario_origen, inventario.id_estado_de_inventario_origen, inventario.fecha_origen, inventario.id_inventario_destino, inventario.id_estado_de_inventario_destino, inventario.fecha_destino, inventario.id_producto, inventario.numero_de_serie, inventario.cantidad FROM inventario WHERE inventario.id_inventario_destino = '$idOrganizacion'";
        }

        $resultados = $inventario->customQuery($query);
        for ($i = 0; $i < count($resultados); $i++) {
            $resultados[$i]["inventario_origen"] = $organizaciones[$resultados[$i]["id_inventario_origen"]];
            $resultados[$i]["inventario_destino"] = $organizaciones[$resultados[$i]["id_inventario_destino"]];

            $resultados[$i]["estado_de_inventario_origen"] = $estadosDeInventarios[$resultados[$i]["id_estado_de_inventario_origen"]];
            $resultados[$i]["estado_de_inventario_destino"] = $estadosDeInventarios[$resultados[$i]["id_estado_de_inventario_destino"]];

            $query = "SELECT categorias.id AS 'id_categoria', categorias.categoria, productos.nombre, productos.descripcion FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.id = '" . $resultados[$i]["id_producto"] . "'";
            $resultadoProducto = $inventario->customQuery($query)[0];
            $resultados[$i]["id_categoria"] = $resultadoProducto["id_categoria"];
            $resultados[$i]["categoria"] = $resultadoProducto["categoria"];
            $resultados[$i]["nombre_producto"] = $resultadoProducto["nombre"];
            $resultados[$i]["descripcion_producto"] = $resultadoProducto["descripcion"];
        }
        echo json_encode($resultados);
    }

    public function reportePorSerie(Request $request)
    {
        // SE RECIBEN LOS DATOS
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

        $numeroDeSerie = $data["numero_de_serie"];

        $inventario = new Inventario();

        $query = "SELECT organizaciones.id, CONCAT(organizaciones.organizacion,' - ',organizaciones.descripcion) AS 'organizacion' FROM organizaciones WHERE organizaciones.deleted_at IS NULL UNION SELECT usuarios.id, CONCAT('Técnico: ',empleados.nombre,' ',empleados.apellido_paterno) AS 'organizacion' FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE usuarios.deleted_at IS NULL AND puestos.id = 8 UNION SELECT clientes.id, CONCAT('Cliente: ', clientes.nombre,' ',clientes.apellido_paterno) AS 'organizacion' FROM clientes WHERE clientes.deleted_at IS NULL";
        $resultados = $inventario->customQuery($query);
        $organizaciones = [];
        foreach ($resultados as $resultado) {
            $organizaciones[$resultado["id"]] = $resultado["organizacion"];
        }

        $query = "SELECT estados_de_inventarios.id, estados_de_inventarios.estado_de_inventario FROM estados_de_inventarios WHERE estados_de_inventarios.deleted_at IS NULL";
        $resultados = $inventario->customQuery($query);
        $estadosDeInventarios = [];
        foreach ($resultados as $resultado) {
            $estadosDeInventarios[$resultado["id"]] = $resultado["estado_de_inventario"];
        }

        $query = "SELECT inventario.id_inventario_origen, inventario.id_estado_de_inventario_origen, inventario.fecha_origen, inventario.id_inventario_destino, inventario.id_estado_de_inventario_destino, inventario.fecha_destino, inventario.id_producto, inventario.numero_de_serie, inventario.cantidad FROM inventario WHERE inventario.numero_de_serie = '$numeroDeSerie'";

        $resultados = $inventario->customQuery($query);
        for ($i = 0; $i < count($resultados); $i++) {
            $resultados[$i]["inventario_origen"] = $organizaciones[$resultados[$i]["id_inventario_origen"]];
            $resultados[$i]["inventario_destino"] = $organizaciones[$resultados[$i]["id_inventario_destino"]];

            $resultados[$i]["estado_de_inventario_origen"] = $estadosDeInventarios[$resultados[$i]["id_estado_de_inventario_origen"]];
            $resultados[$i]["estado_de_inventario_destino"] = $estadosDeInventarios[$resultados[$i]["id_estado_de_inventario_destino"]];

            $query = "SELECT categorias.id AS 'id_categoria', categorias.categoria, productos.nombre, productos.descripcion FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.id = '" . $resultados[$i]["id_producto"] . "'";
            $resultadoProducto = $inventario->customQuery($query)[0];
            $resultados[$i]["id_categoria"] = $resultadoProducto["id_categoria"];
            $resultados[$i]["categoria"] = $resultadoProducto["categoria"];
            $resultados[$i]["nombre_producto"] = $resultadoProducto["nombre"];
            $resultados[$i]["descripcion_producto"] = $resultadoProducto["descripcion"];
        }
        echo json_encode($resultados);
    }
}