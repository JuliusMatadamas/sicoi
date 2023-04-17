<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\Modulo;
use app\models\SubModulo;

class SubModuloController extends Controller
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
        $modulos = new Modulo();
        $data = $modulos->getAll();
        return $this->render('admin/submodulos/index', '', '', $data);
    }

    public function save(Request $request)
    {
        // SE CREA UNA NUEVA INSTANCIA DE LA CLASE 'Módulo'
        $submodulo = new SubModulo();

        // SE CARGAN LOS DATOS RECIBIDOS EN LOS ATRIBUTOS DE LA CLASE
        $submodulo->loadData($request->getBody());

        // SE VALIDA LA INFORMACIÓN RECIBIDA
        $validacion = $submodulo->validate();

        // SI LA INFORMACIÓN NO ES VÁLIDA
        if (!$validacion["eval"])
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $validacion["message"]]);
            exit;
        }

        // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
        $result = $submodulo->create();

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string")
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
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

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'El submódulo fue registrado exitosamente.']);
    }
}