<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\Puesto;

class PuestoController extends Controller
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
        $puestos = new Puesto();
        $data[0] = count($puestos->getAll());
        $data[1] = $puestos->customQuery("SELECT * FROM puestos LIMIT 10");
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">
        <link rel="stylesheet" href="'. ENTORNO .'/public/css/admin/puestos.css">';
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>
                <script src="'. ENTORNO .'/public/js/admin/puestos.js"></script>';

        return $this->render('admin/puestos/index', $css, $js, $data);
    }

    public function save(Request $request)
    {
        // SE RECIBE LA INFORMACIÓN
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

        // SE VALIDA EL CSRF

        // SI LA ACCIÓN A REALIZAR ES CREATE
        if ($data["action"] == "create")
        {
            // SE CREA UNA NUEVA INSTANCIA DE LA CLASE 'Puesto'
            $puesto = new Puesto();

            // SE CARGAN LOS DATOS RECIBIDOS EN LOS ATRIBUTOS DE LA CLASE
            $puesto->loadData($request->getBody());

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $puesto->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"])
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $puesto->create();

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
            echo json_encode(['response' => true, 'message' => 'El puesto fue registrado exitosamente.']);
            exit;
        }

        // SI LA ACCIÓN A REALIZAR ES UPDATE
        if ($data["action"] == "update")
        {
            $puesto = new Puesto();

            // SE CARGAN LOS DATOS RECIBIDOS EN LOS ATRIBUTOS DE LA CLASE
            $puesto->loadData($request->getBody());

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $puesto->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"])
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $puesto->update($data["id"]);

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
            echo json_encode(['response' => true, 'message' => 'El puesto fue actualizado exitosamente.']);
            exit;
        }
    }

    public function delete(Request $request)
    {
        // SE RECIBE LA INFORMACIÓN
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

        // SE VALIDA EL CSRF

        // SE REALIZA LA ELIMINACIÓN DEL REGISTRO
    }
}