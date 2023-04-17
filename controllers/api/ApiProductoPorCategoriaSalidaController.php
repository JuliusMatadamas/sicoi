<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Producto;
use app\models\Usuario;

class ApiProductoPorCategoriaSalidaController extends Controller
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

                    $producto = new Producto();

                    $query = "SELECT productos.id, productos.nombre, productos.descripcion FROM productos WHERE productos.deleted_at IS NULL AND productos.id_categoria = '" . $_POST['id_categoria'] . "' ORDER BY productos.nombre ASC";
                    $data["productos"] = $producto->customQuery($query);

                    $query = "SELECT inventario.id_producto AS 'id', SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_destino = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND inventario.id_estado_de_inventario_destino = 1 AND categorias.id = '" . $_POST['id_categoria'] . "' GROUP BY inventario.id_producto";
                    $data["productos_disponibles"] = $producto->customQuery($query);

                    $query = "SELECT inventario.id_producto AS 'id', SUM(inventario.cantidad) AS 'total' FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.deleted_at IS NULL AND inventario.id_inventario_origen = 'P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2' AND categorias.id = '" . $_POST['id_categoria'] . "' GROUP BY inventario.id_producto";
                    $data["productos_salidas"] = $producto->customQuery($query);

                    $disponibles = [];
                    $cont = 0;

                    for ($i = 0; $i < count($data["productos"]); $i++) {
                        for ($j = 0; $j < count($data["productos_disponibles"]); $j++) {
                            if ($data["productos"][$i]["id"] == $data["productos_disponibles"][$j]["id"]) {
                                $disponibles[$cont]["id"] = $data["productos"][$i]["id"];
                                $disponibles[$cont]["nombre"] = $data["productos"][$i]["nombre"];
                                $disponibles[$cont]["descripcion"] = $data["productos"][$i]["descripcion"];
                                $disponibles[$cont]["total"] = intval($data["productos_disponibles"][$j]["total"]);
                                $cont++;
                            }
                        }
                    }

                    for ($i = 0; $i < count($disponibles); $i++) {
                        for ($j = 0; $j < count($data["productos_salidas"]); $j++) {
                            if ($disponibles[$i]["id"] == $data["productos_salidas"][$j]["id"]) {
                                $disponibles[$i]["total"] = $disponibles[$i]["total"] - $data["productos_salidas"][$j]["total"];
                            }
                        }
                    }

                    if (count($disponibles) === 0) {
                        $statusCode = new Response();
                        $statusCode->setStatusCode(400);
                        echo json_encode(['response' => false, 'message' => 'No se obtuvo respuesta del servidor']);
                        exit;
                    }

                    // SI LA INFORMACIÓN SE OBTUVO CORRECTAMENTE LA BD
                    echo json_encode(['response' => true, 'data' => $disponibles]);
                    exit;
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}