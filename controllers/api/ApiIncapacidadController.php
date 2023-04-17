<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Incapacidad;
use app\models\Usuario;

class ApiIncapacidadController extends Controller
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
            $result = $usuario->customQuery("SELECT usuarios.id, usuarios.expiration_jwt, empleados.id_puesto FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id WHERE usuarios.jwt = '" . $_COOKIE["jwt"] . "'");
            if (count($result) == 0) {
                echo json_encode("");
            } else {
                $jwtExpiration = strtotime($result[0]["expiration_jwt"]);
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $diferencia = $jwtExpiration - $currentDate;
                if ($diferencia < 0) {
                    echo json_encode("");
                } else {
                    $idUsuario = $result[0]["id"];
                    $idPuesto = $result[0]["id_puesto"];

                    $incapacidad = new Incapacidad();

                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "fecha_inicio", "fecha_termino", "observaciones");

                    $query = "SELECT * FROM incapacidades WHERE ";

                    $su = array(1,5,7);
                    if (in_array(intval($idPuesto), $su, true) === false) {
                        $query .= " id_usuario = " . $idUsuario . " AND ";
                    }

                    $query .= ' deleted_at IS NULL AND (id LIKE "%' . $search . '%" OR fecha_inicio LIKE "%' . $search . '%" OR fecha_termino LIKE "%' . $search . '%" OR observaciones LIKE "%' .$search . '%") ';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $incapacidad->getTotalData($query);

                    $result = $incapacidad->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = $row["id"];
                        $sub_array[] = $row["fecha_inicio"];
                        $sub_array[] = $row["fecha_termino"];
                        $sub_array[] = '<div class="container-td" onclick="previewComprobante(' . "'" . ENTORNO . $row["comprobante"] . "'" . ')"><i class="fa-solid fa-expand"></i><span>Ver</span></div>';
                        if ($row["validacion"] == 0) {
                            $sub_array[] = '<i class="fa-solid fa-circle-half-stroke"></i>';
                        } elseif ($row["validacion"] == 1) {
                            $sub_array[] = '<i class="fa-solid fa-circle-check"></i>';
                        } else {
                            $sub_array[] = '<i class="fa-solid fa-circle-xmark"></i>';
                        }
                        $sub_array[] = '<input type="text" class="input-incapacidad" value="'. $row["observaciones"] .'">';
                        if ($row["validacion"] == 1) {
                            $sub_array[] = '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                        } else {
                            $sub_array[] = '<div class="container-td" onclick="editarIncapacidad(' . $row["id"] . ')"><i class="fa-solid fa-pen-to-square"></i><span>Editar</span></div>';
                        }
                        if ($row["validacion"] == 1) {
                            $sub_array[] = '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                        } else {
                            $sub_array[] = '<div class="container-td" onclick="confirmarEliminacionDeIncapacidad(' . $row["id"] . ')"><i class="fa-solid fa-trash-can"></i><span>Eliminar</span></div>';
                        }

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($incapacidad->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }

    public function edit(Request $request)
    {
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($cu, CURLOPT_POST, 1);
        curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CLAVE_SECRETA, 'response' => $token)));
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($cu);
        curl_close($cu);
        $respuesta = json_decode($response, true);
        if($respuesta["success"] === false)
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }

        $usuario = new Usuario();

        // SE VALIDA EL CRSF
        if ($usuario->validateCsrf($data["csrf"], $data["id_usuario"]) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
            exit;
        }

        $incapacidad = new Incapacidad();

        $result = $incapacidad->findById($data["id_incapacidad"]);

        // SI OCURRIÓ UN ERROR AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
        if (gettype($result) == "string")
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
        if (gettype($result) == "boolean")
        {
            if (!$result)
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        if (count($result) == 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se encontró la incapacidad.']);
            exit;
        }

        // SI LA INFORMACIÓN SE OBTUVO CORRECTAMENTE DE LA BD
        echo json_encode(['response' => true, 'data' => json_encode($result[0], JSON_FORCE_OBJECT)]);
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
                    $incapacidad = new Incapacidad();
                    $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
                    $start = $startGET ? intval($startGET) : 0;

                    $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
                    $length = $lengthGET ? intval($lengthGET) : 10;

                    $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
                    $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

                    $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

                    $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

                    $column = array("id", "fecha_inicio", "fecha_termino");

                    $query = "SELECT incapacidades.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', incapacidades.fecha_inicio, incapacidades.fecha_termino, incapacidades.comprobante FROM incapacidades INNER JOIN empleados ON incapacidades.id_usuario = empleados.id";

                    $query .= ' WHERE incapacidades.id LIKE "%' . $search . '%" OR CONCAT(empleados.nombre,'. "' '" .',empleados.apellido_paterno) LIKE "%' . $search . '%" OR incapacidades.fecha_inicio LIKE "%' . $search . '%" OR incapacidades.fecha_termino LIKE "%'. $search .'%" ';

                    if ($sortColumnIndex != '') {
                        $query .= ' ORDER BY ' . $column[$sortColumnIndex] . ' ' . $sortDirection . ' ';
                    } else {
                        $query .= ' ORDER BY incapacidades.id ASC ';
                    }

                    $query1 = '';

                    if ($length != -1) {
                        $query1 = ' LIMIT ' . $start . ', ' . $length;
                    }

                    $number_filter_row = $incapacidad->getTotalData($query);

                    $result = $incapacidad->customQuery($query . $query1);

                    $data = array();

                    foreach ($result as $row) {
                        $sub_array = array();
                        $sub_array[] = $row["id"];
                        $sub_array[] = $row["empleado"];
                        $sub_array[] = $row["fecha_inicio"];
                        $sub_array[] = $row["fecha_termino"];
                        $sub_array[] = '<div class="td-actions" onclick="cargarIncapacidad('."'".$row["id"].'|'.$row["empleado"].'|'.$row["fecha_inicio"].'|'.$row["fecha_termino"].'|'.$row["comprobante"]."'".')"><i class="fa-solid fa-location-crosshairs"></i><span>Ver</span></div>';

                        $data[] = $sub_array;
                    }

                    $output = array('recordsTotal' => count($incapacidad->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

                    echo json_encode($output);
                }
            }
        } else {
            Response::redirect('/not_authorized');
        }
    }
}