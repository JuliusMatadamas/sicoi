<?php

namespace app\controllers\api;

use app\core\Controller;
use app\models\Usuario;

class ApiUsuarioController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $usuario = new Usuario();
        $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
        $start = $startGET ? intval($startGET) : 0;

        $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
        $length = $lengthGET ? intval($lengthGET) : 10;

        $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
        $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

        $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

        $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

        $column = array("id", "empleado", "usuario", "puesto");

        $query = "SELECT usuarios.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', usuarios.usuario, puestos.puesto FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE (empleados.fecha_baja IS NULL OR empleados.fecha_baja = '' OR empleados.fecha_baja > CURRENT_DATE) AND (usuarios.deleted_at IS NULL) AND ";

        $query .= ' (usuarios.id LIKE "%'.$search.'%" OR CONCAT(empleados.nombre,'."' '".',empleados.apellido_paterno) LIKE "%'.$search.'%" OR usuarios.usuario LIKE "%'.$search.'%" OR puestos.puesto LIKE "%'.$search.'%") ';

        if ($sortColumnIndex != '')
        {
            $query .= ' ORDER BY '.$column[$sortColumnIndex].' '.$sortDirection.' ';
        }
        else
        {
            $query .= ' ORDER BY usuarios.id ASC ';
        }

        $query1 = '';

        if($length != -1)
        {
            $query1 = ' LIMIT '.$start.', '.$length;
        }

        $number_filter_row = $usuario->getTotalData($query);

        $result = $usuario->customQuery($query . $query1);

        $data = array();

        foreach ($result as $row)
        {
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["empleado"];
            $sub_array[] = $row['usuario'];
            $sub_array[] = $row["puesto"];
            $sub_array[] = '<form name="form-edit_user_'.$row["id"].'" id="form-edit_user_'.$row["id"].'" action="'.ENTORNO.'/usuarios/editar" method="post"><div class="container-edit_usuario" onclick="document.forms['."'form-edit_user_".$row["id"]."'".'].submit()"><input type="hidden" name="id" value="'.$row["id"].'"><i class="fa-regular fa-pen-to-square"></i><span>Editar</span></div></form>';
            $data[] = $sub_array;
        }

        $output = array('recordsTotal' => count($usuario->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

        echo json_encode($output);
    }
}