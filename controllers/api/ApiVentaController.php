<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Usuario;
use app\models\Venta;

class ApiVentaController extends Controller
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
                    $venta = new Venta();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "vendedor", "cliente", "venta", "fecha_venta", "fecha_programada", "id_estado_de_visita", "estado_de_visita");

                    $query = "SELECT ventas.id AS 'id', CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'vendedor', CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', tipos_de_ventas.tipo AS 'venta', ventas.created_at AS 'fecha_venta', ventas.fecha_programada AS 'fecha_programada', estados_de_visitas.id AS 'id_estado_de_visita', estados_de_visitas.estado_de_visita AS 'estado_de_visita' FROM ventas INNER JOIN usuarios ON ventas.id_usuario_atendio = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id";

                    $query .= ' WHERE (ventas.id LIKE "%' . $search . '%" OR CONCAT(empleados.nombre,'. "' '" .',empleados.apellido_paterno) LIKE "%' . $search . '%" OR CONCAT(clientes.nombre,'. "' '" .',clientes.apellido_paterno) LIKE "%' . $search . '%" OR tipos_de_ventas.tipo LIKE "%' . $search . '%" OR ventas.created_at LIKE "%' . $search . '%" OR ventas.fecha_programada LIKE "%' . $search . '%" OR estados_de_visitas.estado_de_visita LIKE "%' . $search . '%") AND ventas.deleted_at IS NULL ';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $venta->getTotalData($query);

                    $result = $venta->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["id"].'">';
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["vendedor"].'">';
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["cliente"].'">';
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["venta"].'">';
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["fecha_venta"].'">';
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["fecha_programada"].'">';
                        $sub_array[] = '<input type="text" class="input-table" value="'.$row["estado_de_visita"].'">';

                        if (in_array(intval($row["id_estado_de_visita"]),[1,2,5])) {
                            $sub_array[] = '<div class="td-actions" onclick="window.location = '. "'" . ENTORNO . '/ventas/editar?id_venta=' . $row["id"] . '&id_estado_de_visita=' . $row["id_estado_de_visita"] . "'" . '"><i class="fa-regular fa-pen-to-square"></i><span>Editar</span></div>';
                        } else {
                            $sub_array[] = '<!-- -->';
                        }

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($venta->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}