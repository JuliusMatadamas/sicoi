<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Response;
use app\helpers\Auth;
use app\models\Usuario;
use app\models\Vacacion;

class ApiVacacionController extends Controller
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
                    $vacacion = new Vacacion();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "fecha_inicio", "hora_inicio", "fecha_termino", "hora_termino", "motivo", "validacion");

                    $query = "SELECT * FROM vacaciones";

                    $query .= ' WHERE id_usuario = '.$idUsuario.' AND deleted_at IS NULL AND (id LIKE "%' . $search . '%" OR fecha LIKE "%' . $search . '%" OR validacion LIKE "%' . $search . '%" OR estado LIKE "%' . $search . '%")';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $vacacion->getTotalData($query);

                    $result = $vacacion->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = $row["id"];
                        $sub_array[] = $row["fecha"];

                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<i class="fa-solid fa-circle-half-stroke"></i>';
                        } elseif ($row["validacion"] == 1) {
                            $sub_array[] = '<i class="fa-solid fa-circle-check"></i>';
                        } else {
                            $sub_array[] = '<i class="fa-solid fa-circle-xmark"></i>';
                        }

                        if ($row["validacion"] == 0) {
                            $sub_array[] = 'Pendiente de autorizar';
                        } else {
                            if ($row["validacion"] == 1) {
                                if ($row["estado"] == 0) {
                                    $sub_array[] = 'Pendiente de tomar';
                                } else {
                                    $sub_array[] = 'Tomada';
                                }
                            } else {
                                $sub_array[] = 'Rechazada';
                            }
                        }

                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<div class="container-td" onclick="editarVacacion(' . $row["id"] . ')"><i class="fa-solid fa-pen-to-square"></i><span>Editar</span></div>';
                        } else {
                            $sub_array[] = '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                        }

                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<div class="container-td" onclick="confirmarEliminacionDeVacacion(' . $row["id"] . ')"><i class="fa-solid fa-trash-can"></i><span>Eliminar</span></div>';
                        } else {
                            $sub_array[] = '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                        }

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($vacacion->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}