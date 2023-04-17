<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Permiso;
use app\models\Usuario;

class ApiPermisoController extends Controller
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
                    $permiso = new Permiso();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino", "motivo", "validacion", "observaciones");

                    $query = "SELECT * FROM permisos WHERE id_usuario = $idUsuario AND";

                    $query .= ' deleted_at IS NULL AND (id LIKE "%' . $search . '%" OR fecha_inicio LIKE "%' . $search . '%" OR hora_inicio LIKE "%' . $search . '%" OR fecha_termino LIKE "%' . $search . '%" OR hora_termino LIKE "%' . $search . '%" OR motivo LIKE "%' . $search . '%" OR validacion LIKE "%' . $search . '%" OR observaciones LIKE "%' . $search . '%")';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $permiso->getTotalData($query);

                    $result = $permiso->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = $row["id"];
                        $sub_array[] = $row["fecha_inicio"];
                        $sub_array[] = $row["hora_inicio"];
                        $sub_array[] = $row["fecha_termino"];
                        $sub_array[] = $row["hora_termino"];
                        $sub_array[] = '<input type="text" class="permiso-td_input" value="'. $row["motivo"] .'">';

                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<i class="fa-solid fa-circle-half-stroke"></i>';
                        } elseif ($row["validacion"] == 1) {
                            $sub_array[] = '<i class="fa-solid fa-circle-check"></i>';
                        } else {
                            $sub_array[] = '<i class="fa-solid fa-circle-xmark"></i>';
                        }

                        $sub_array[] = '<input type="text" class="permiso-td_input" value="'. $row["observaciones"] .'">';

                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<div class="container-td" onclick="editarPermiso(' . $row["id"] . ')"><i class="fa-solid fa-pen-to-square"></i><span>Editar</span></div>';
                        } else {
                            $sub_array[] = '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                        }

                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<div class="container-td" onclick="confirmarEliminacionDePermiso(' . $row["id"] . ')"><i class="fa-solid fa-trash-can"></i><span>Eliminar</span></div>';
                        } else {
                            $sub_array[] = '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                        }

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($permiso->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }

    public function toValidate()
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
                    echo json_encode("");
                } else {
                    $permiso = new Permiso();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "empleado", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino");

                    $query = "SELECT permisos.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', permisos.fecha_inicio, permisos.hora_inicio, permisos.fecha_termino, permisos.hora_termino, permisos.motivo FROM permisos INNER JOIN usuarios ON permisos.id_usuario = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE permisos.validacion = 0 AND permisos.deleted_at IS NULL";

                    $query .= ' AND (permisos.id LIKE "%' . $search . '%" OR CONCAT(empleados.nombre,'. "' '" .',empleados.apellido_paterno) LIKE "%' . $search . '%" OR permisos.fecha_inicio LIKE "%' . $search . '%" OR permisos.hora_inicio LIKE "%' . $search . '%" OR permisos.fecha_termino LIKE "%'. $search .'%" OR permisos.hora_termino LIKE "%' . $search . '%") ';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY permisos.id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $permiso->getTotalData($query);

                    $result = $permiso->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = $row["id"];
                        $sub_array[] = $row["empleado"];
                        $sub_array[] = $row["fecha_inicio"] . ' ' . substr($row["hora_inicio"], 0, 5);
                        $sub_array[] = $row["fecha_termino"] . ' ' . substr($row["hora_termino"], 0, 5);
                        $sub_array[] = '<div class="td-actions" onclick="cargarPermiso('."'".$row["id"].'|'.$row["empleado"].'|'.$row["fecha_inicio"].'|'.$row["hora_inicio"].'|'.$row["fecha_termino"].'|'.$row["hora_termino"].'|'.$row["motivo"]."'".')"><i class="fa-solid fa-location-crosshairs"></i><span>Ver</span></div>';

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($permiso->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}