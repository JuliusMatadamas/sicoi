<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Usuario;
use app\models\Venta;

class ApiVentasPorAsignarController extends Controller
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

                    $column = array("id", "cliente", "colonia", "calle", "codigo_postal", "tipo_de_venta", "fecha_programada");

                    $query = "SELECT ventas.id, CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente', colonias.colonia, clientes.calle, codigos_postales.codigo_postal, tipos_de_ventas.tipo AS 'tipo_de_venta', ventas.fecha_programada FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id";

                    $query .= ' WHERE (ventas.id LIKE "%' . $search . '%" OR CONCAT(clientes.nombre,' . "' '" . ',clientes.apellido_paterno) LIKE "%' . $search . '%" OR colonias.colonia LIKE "%' . $search . '%" OR clientes.calle LIKE "%' . $search . '%" OR codigos_postales.codigo_postal LIKE "%' . $search . '%" OR tipos_de_ventas.tipo LIKE "%' . $search . '%" OR ventas.fecha_programada LIKE "%' . $search . '%") AND ventas.id_tecnico_asignado = 0 AND ventas.fecha_visita IS NULL AND ventas.deleted_at IS NULL ';

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
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["id"] . '">';
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["cliente"] . '">';
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["colonia"] . '">';
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["calle"] . '">';
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["codigo_postal"] . '">';
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["tipo_de_venta"] . '">';
                        $sub_array[] = '<input type="text" class="input-table" value="' . $row["fecha_programada"] . '">';
                        $sub_array[] = '<div class="td-actions" onclick="asignacion(' . "'" . implode("','", $row) . "'" . ')"><i class="fa-solid fa-person-chalkboard"></i><span>Asignar técnico</span></div>';

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