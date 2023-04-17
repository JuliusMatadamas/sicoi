<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Cliente;
use app\models\Usuario;

class ApiClienteController extends Controller
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
                $idUsuario = $result[0]["id"];
                $jwtExpiration = strtotime($result[0]["expiration_jwt"]);
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $diferencia = $jwtExpiration - $currentDate;
                if ($diferencia < 0) {
                    echo json_encode("");
                } else {
                    $cliente = new Cliente();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "nombre", "apellido_paterno", "apellido_materno", "fecha_nacimiento", "genero", "rfc", "telefono_casa", "telefono_celular", "email", "codigo_postal", "colonia", "calle", "numero_exterior", "numero_interior", "observaciones");

                    $query = "SELECT clientes.id, clientes.nombre, clientes.apellido_paterno, clientes.apellido_materno, clientes.fecha_nacimiento, clientes.id_genero, generos.genero, clientes.telefono_casa, clientes.telefono_celular, clientes.email, colonias.id_codigo_postal, codigos_postales.codigo_postal, clientes.id_colonia, colonias.colonia, clientes.rfc, clientes.calle, clientes.numero_exterior, clientes.numero_interior, clientes.observaciones FROM clientes INNER JOIN generos ON clientes.id_genero = generos.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id";

                    $query .= ' WHERE clientes.nombre LIKE "%' . $search . '%" OR clientes.apellido_paterno LIKE "%' . $search . '%" OR clientes.apellido_materno LIKE "%' . $search . '%" OR clientes.fecha_nacimiento LIKE "%' . $search . '%" OR generos.genero LIKE "%' . $search . '%" OR clientes.telefono_casa LIKE "%' . $search . '%" OR clientes.telefono_celular LIKE "%' . $search . '%" OR clientes.email LIKE "%' . $search . '%" OR codigos_postales.codigo_postal LIKE "%' . $search . '%" OR colonias.colonia LIKE "%' . $search . '%" OR clientes.rfc LIKE "%' . $search . '%" OR clientes.calle LIKE "%' . $search . '%" OR clientes.numero_exterior LIKE "%' . $search . '%" OR clientes.numero_interior LIKE "%' . $search . '%" OR clientes.observaciones LIKE "%' . $search . '%" ';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $cliente->getTotalData($query);

                    $result = $cliente->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["nombre"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["apellido_paterno"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["apellido_materno"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["fecha_nacimiento"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["genero"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["rfc"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["telefono_casa"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["telefono_celular"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["email"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["codigo_postal"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["colonia"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["calle"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["numero_exterior"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["numero_interior"].'">';
                        $sub_array[] = '<input type="text" class="input-item" value="'.$row["observaciones"].'">';
                        $sub_array[] = '<div class="td-actions" onclick="window.location = '. "'" . ENTORNO . '/clientes/editar?id=' . $row["id"] . "'".'"><i class="fa-regular fa-pen-to-square"></i><span>Editar</span></div>';
                        $sub_array[] = '<div class="td-actions" onclick="window.location = '. "'" . ENTORNO . '/ventas/nueva?id=' . $row["id"] . "'".'"><i class="fa-solid fa-comments-dollar"></i><span>Venta</span></div>';

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($cliente->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}