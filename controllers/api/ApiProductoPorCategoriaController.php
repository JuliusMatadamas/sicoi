<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Producto;
use app\models\Usuario;

class ApiProductoPorCategoriaController extends Controller
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

                    $idCategoria = $_POST["id_categoria"];

                    $productos = new Producto();

                    $query = "SELECT productos.id, productos.nombre, productos.descripcion FROM productos WHERE productos.id_categoria = $idCategoria AND productos.deleted_at IS NULL ORDER BY productos.nombre, productos.descripcion ASC";

                    $result = $productos->customQuery($query);


                    // SI OCURRIÓ UN ERROR AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
                    if (gettype($result) == "string") {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => $result]);
                        exit;
                    }

                    // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
                    if (gettype($result) == "boolean") {
                        if (!$result) {
                            $statusCode = new Response();
                            $statusCode->setStatusCode(400);
                            echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                            exit;
                        }
                    }

                    // SI LA INFORMACIÓN SE OBTUVO CORRECTAMENTE LA BD
                    echo json_encode(['response' => true, 'data' => $result]);
                    exit;
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}