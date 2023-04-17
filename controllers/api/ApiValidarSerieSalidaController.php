<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Producto;
use app\models\Usuario;

class ApiValidarSerieSalidaController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function index()
    {
        if (isset($_COOKIE["jwt"])) {

            $usuario = new Usuario();

            $result = $usuario->customQuery("SELECT usuarios.id, usuarios.expiration_jwt FROM usuarios WHERE usuarios.jwt = '" . $_COOKIE["jwt"] . "'");

            if (count($result) == 0) {
                echo json_encode("");
            } else {
                $jwtExpiration = strtotime($result[0]["expiration_jwt"]);
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $diferencia = $jwtExpiration - $currentDate;
                if ($diferencia < 0) {
                    echo json_encode("");
                } else {
                    // SE VALIDA EL RECAPTCHA
                    $token = $_POST["token"];
                    if (Usuario::validateToken($token) === false) {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
                        exit;
                    }
                    unset($_POST["token"]);

                    // SE VALIDA EL CSRF
                    $csrf = $_POST["csrf"];
                    $idUsuario = $_POST["id_usuario"];
                    if (Usuario::validateCsrf($csrf, $idUsuario) === 0) {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
                        exit;
                    }
                    unset($_POST["csrf"]);
                    unset($_POST["id_usuario"]);

                    $numeroDeSerie = $_POST["numero_de_serie"];

                    $producto = new Producto();

                    $query = "SELECT * FROM inventario WHERE inventario.numero_de_serie = '$numeroDeSerie' ORDER BY inventario.id DESC LIMIT 1";

                    $result = $producto->customQuery($query);

                    if (count($result) === 0) {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => 'No se encontró este número de serie en la BD.']);
                        exit;
                    }

                    if ($result[0]["id_inventario_destino"] !== "P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2") {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => 'Este número de serie no se encuentra en el inventario del almacén.']);
                        exit;
                    }

                    if ($result[0]["id_estado_de_inventario_destino"] !== "1") {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => 'Este número de serie no se encuentra disponible para salida.']);
                        exit;
                    }

                    echo json_encode(['response' => false, 'message' => 'Número de serie disponible.']);
                    exit;
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}