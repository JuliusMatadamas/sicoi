<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Organizacion;
use app\models\Usuario;

class ApiOrganizacionController extends Controller
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
                    $organizacion = new Organizacion();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "organizacion", "descripcion");

                    $query = "SELECT organizaciones.id, organizaciones.organizacion, organizaciones.descripcion FROM organizaciones";

                    $query .= ' WHERE (organizaciones.id LIKE "%' . $search . '%" OR organizaciones.organizacion LIKE "%' . $search . '%" OR organizaciones.descripcion LIKE "%' . $search . '%")  AND organizaciones.deleted_at IS NULL ';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $organizacion->getTotalData($query);

                    $result = $organizacion->customQuery($query . $query1);

                    $data = array();

                    $cont = 1;
                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = '<input class="input-table" value="' . $cont . '">';
                        $sub_array[] = '<input class="input-table" value="' . $row["organizacion"] . '">';
                        $sub_array[] = '<input class="input-table" value="' . $row["descripcion"] . '">';
                        $sub_array[] = '<div class="td-actions" onclick="setDelete(' . "'" . $row["id"] . "'" . ",'" . $row["organizacion"] . "', '" . $row["descripcion"] . "'" . ')"><i class="fa-regular fa-trash-can"></i><span>Eliminar</span></div>';
                        $sub_array[] = '<div class="td-actions" onclick="setEdit(' . "'" . $row["id"] . "'" . ",'" . $row["organizacion"] . "', '" . $row["descripcion"] . "'" . ')"><i class="fa-regular fa-pen-to-square"></i><span>Editar</span></div>';
                        $data[] = $sub_array;
                        $cont++;
                    }

                    $output = array('recordsTotal' => count($organizacion->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}