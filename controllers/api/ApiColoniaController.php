<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Colonia;
use app\models\Usuario;

class ApiColoniaController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function index(Request $request)
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

        $colonias = new Colonia();
        $cp = $data["codigo_postal"];

        // SE REALIZA LA CONSULTA A LA BD
        $result = $colonias->customQuery("SELECT colonias.id, colonias.colonia FROM colonias INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id WHERE codigos_postales.codigo_postal = '$cp' ORDER BY colonias.colonia ASC");

        // SI OCURRIÓ UN ERROR AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
        if (gettype($result) == "string")
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
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

        // SI LA INFORMACIÓN SE OBTUVO CORRECTAMENTE DE LA BD
        echo json_encode(['response' => true, 'data' => json_encode($result, JSON_FORCE_OBJECT)]);
    }
}