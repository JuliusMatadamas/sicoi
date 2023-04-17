<?php

namespace app\controllers\api;

use app\core\Controller;
use app\models\Empleado;

class ApiEmpleadoController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $empleado = new Empleado();
        $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
        $start = $startGET ? intval($startGET) : 0;

        $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
        $length = $lengthGET ? intval($lengthGET) : 10;

        $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
        $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

        $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

        $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

        $column = array("id", "nombre", "email", "telefono_casa", "telefono_email", "seguro_social", "rfc", "puesto", "fecha_inicio", "fecha_baja");

        $query = "SELECT empleados.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno) AS 'empleado', empleados.email, empleados.telefono_casa, empleados.telefono_celular, empleados.seguro_social, empleados.rfc, puestos.puesto, empleados.profile_img, empleados.fecha_inicio, empleados.fecha_baja FROM empleados INNER JOIN puestos ON empleados.id_puesto = puestos.id";

        $query .= ' WHERE empleados.id LIKE "%'.$search.'%" OR CONCAT(empleados.nombre,'."' '".',empleados.apellido_paterno,'."' '".',empleados.apellido_materno) LIKE "%'.$search.'%" OR empleados.email LIKE "%'.$search.'%" OR empleados.telefono_casa LIKE "%'.$search.'%" OR empleados.telefono_celular LIKE "%'.$search.'%" OR empleados.seguro_social LIKE "%'.$search.'%" OR empleados.rfc LIKE "%'.$search.'%" OR puestos.puesto LIKE "%'.$search.'%" OR empleados.fecha_inicio LIKE "%'.$search.'%" OR empleados.fecha_baja LIKE "%'.$search.'%" ';

        if ($sortColumnIndex != '')
        {
            $query .= ' ORDER BY '.$column[$sortColumnIndex].' '.$sortDirection.' ';
        }
        else
        {
            $query .= ' ORDER BY id ASC ';
        }

        $query1 = '';

        if($length != -1)
        {
            $query1 = ' LIMIT '.$start.', '.$length;
        }

        $number_filter_row = $empleado->getTotalData($query);

        $result = $empleado->customQuery($query . $query1);

        $data = array();

        foreach ($result as $row)
        {
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = '<a class="a-profile" onclick="mostrarProfileImg('."'".ENTORNO.$row["profile_img"]."','".$row["empleado"]."'".')">'.$row["empleado"].'</a>';
            $sub_array[] = "<a class='a-profile' href='mailto:".$row['email']."'>".$row['email']."</a>";
            $sub_array[] = "<a class='a-profile' href='tel:+52".$row["telefono_casa"]."'>".$row["telefono_casa"]."</a>";
            $sub_array[] = "<a class='a-profile' href='tel:+52".$row["telefono_celular"]."'>".$row["telefono_celular"]."</a>";;
            $sub_array[] = $row["seguro_social"];
            $sub_array[] = $row["rfc"];
            $sub_array[] = $row["puesto"];
            $sub_array[] = $row["fecha_inicio"];
            $sub_array[] = $row["fecha_baja"];
            $sub_array[] = '<form name="form-edit_employee_'.$row["id"].'" id="form-edit_employee_'.$row["id"].'" action="'.ENTORNO.'/empleados/editar" method="post"><div class="container-edit_empleado" onclick="document.forms['."'form-edit_employee_".$row["id"]."'".'].submit()"><input type="hidden" name="id" value="'.$row["id"].'"><i class="fa-regular fa-pen-to-square"></i><span>Editar</span></div></form>';
            $data[] = $sub_array;
        }

        $output = array('recordsTotal' => count($empleado->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

        echo json_encode($output);
    }
}