<?php

namespace app\controllers\api;

use app\core\Controller;
use app\models\Puesto;

class ApiPuestoController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $puesto = new Puesto();
        $startGET = filter_input(INPUT_GET, "start", FILTER_SANITIZE_NUMBER_INT);
        $start = $startGET ? intval($startGET) : 0;

        $lengthGET = filter_input(INPUT_GET, "length", FILTER_SANITIZE_NUMBER_INT);
        $length = $lengthGET ? intval($lengthGET) : 10;

        $searchQuery = filter_input(INPUT_GET, "searchQuery", FILTER_SANITIZE_STRING);
        $search = empty($searchQuery) || $searchQuery === "null" ? "" : $searchQuery;

        $sortColumnIndex = filter_input(INPUT_GET, "sortColumn", FILTER_SANITIZE_NUMBER_INT);

        $sortDirection = filter_input(INPUT_GET, "sortDirection", FILTER_SANITIZE_STRING);

        $column = array("id", "puesto");

        $query = "SELECT * FROM puestos";

        $query .= ' WHERE id LIKE "%'.$search.'%" OR puesto LIKE "%'.$search.'%" ';

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

        $number_filter_row = $puesto->getTotalData($query);

        $result = $puesto->customQuery($query . $query1);

        $data = array();

        foreach ($result as $row)
        {
            $sub_array = array();
            $sub_array[] = $row["id"];
            $sub_array[] = $row["puesto"];
            $sub_array[] = '<div class="container-edit_puesto" onclick="setEdit('."'".$row["id"]."'".','."'".$row["puesto"]."'".')"><i class="fa-regular fa-pen-to-square"></i><span>Editar</span></div>';
            $sub_array[] = '<div class="container-edit_puesto" onclick="mostrarConfirmacion('."'".$row["id"]."','".$row["puesto"]."'".')"><i class="fa-regular fa-trash-can"></i><span>Eliminar</span></div>';
            $data[] = $sub_array;
        }

        $output = array('recordsTotal' => count($puesto->getAll()), 'recordsFiltered' => $number_filter_row, 'data' => $data);

        echo json_encode($output);
    }
}