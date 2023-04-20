<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Usuario;
use app\models\Venta;

class ApiSupervisionController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function ubicacionDeTecnicos(Request $request)
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

        $venta = new Venta();

        if ($data["id_tecnico"] == "0") {
            $param1 = "";
        } else {
            $param1 = " AND usuarios.id = " . $data["id_tecnico"] . " ";
        }

        $param2 = " AND ventas.fecha_visita = '" . $data["fecha"] . "' ";

        $query = "SELECT ventas.id AS 'id_venta', usuarios.id AS 'id_tecnico', CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo AS 'tipo_de_venta', ventas.fecha_programada, ventas.fecha_visita, estados_de_visitas.estado_de_visita, ventas.hora_visita, ventas.latitud, ventas.longitud, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'tecnico' FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id INNER JOIN usuarios ON ventas.id_tecnico_visito = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE ventas.deleted_at IS NULL $param1 $param2  ORDER BY ventas.id_tecnico_visito, ventas.hora_visita ASC;";
        $result = $venta->customQuery($query);

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

        // SI SE OBTUVO UNA RESPUESTA SATISFACTORIA DEL SERVIDOR
        echo json_encode(['response' => true, 'data' => $result]);
        exit;
    }
}