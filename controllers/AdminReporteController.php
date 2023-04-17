<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;

class AdminReporteController extends Controller
{
    public function __construct()
    {
        // SE VALIDA SI EL USUARIO HA INICIADO SESIÓJN
        if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"])) {
            Response::redirect('/log_out');
        } else {
            // SE VALIDA SI EL USUARIO TIENE AUTORIZADO EL MODULO
            $output = [];
            for ($i = 0; $i < count($_SESSION["usuario"]["modulos"]); $i++) {
                $m = strtolower($_SESSION["usuario"]["modulos"][$i]["modulo"]);
                $m = str_replace("á", "a", $m);
                $m = str_replace("é", "e", $m);
                $m = str_replace("í", "i", $m);
                $m = str_replace("ó", "o", $m);
                $m = str_replace("ú", "u", $m);
                $output[$i]["modulo"] = $m;
            }
            $modulosFiltrados =  array_map("unserialize", array_unique(array_map("serialize", $output)));

            $location = str_replace("uri=", "", explode("/",$_SERVER["QUERY_STRING"])[0]);

            $cont = 0;
            $modulos = [];
            foreach ($modulosFiltrados as $key => $value) {
                $modulos[$cont] = $value["modulo"];
                $cont++;
            }

            if (in_array($location, $modulos) === false) {
                Response::redirect('/not_authorized');
            }
        }
    }

    public function index()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('admin/reportes/index', $css, $js, $data);
    }

    public function save(Request $request)
    {
    }
}