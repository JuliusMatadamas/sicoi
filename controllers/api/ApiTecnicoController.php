<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Cliente;
use app\models\Inventario;
use app\models\Usuario;

class ApiTecnicoController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function obtener_entradas()
    {
        if (isset($_COOKIE["jwt"])) {

            $usuario = new Usuario();

            $result = $usuario->customQuery("SELECT usuarios.expiration_jwt FROM usuarios WHERE usuarios.jwt = '" . $_COOKIE["jwt"] . "'");

            if (count($result) == 0) {
                echo json_encode("");
            } else {
                $jwtExpiration = strtotime($result[0]["expiration_jwt"]);
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $diferencia = $jwtExpiration - $currentDate;
                if ($diferencia < 0) {
                    $statusCode = new Response();
                    $statusCode->setStatusCode(400);
                    echo json_encode("");
                } else {
                    $idUsuario = $_POST["id_usuario"];
                    $inventario = new Inventario();

                    $query = "SELECT inventario.id, categorias.categoria, productos.nombre, productos.descripcion, inventario.id_inventario_origen, inventario.fecha_origen, inventario.numero_de_serie, inventario.cantidad FROM inventario INNER JOIN productos ON inventario.id_producto = productos.id INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE inventario.id_inventario_destino = '$idUsuario' AND inventario.id_estado_de_inventario_destino = 3 ORDER BY categorias.id";
                    $data["entradas"] = $inventario->customQuery($query);

                    if (count($data["entradas"]) === 0) {
                        echo json_encode("");
                    } else {
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
                        echo json_encode($data["entradas"]);
                    }
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}