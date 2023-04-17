<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\Session;
use app\helpers\Auth;
use app\models\Genero;

class GeneroController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
        Auth::verifyModules();
    }

    public function index()
    {
        $generos = new Genero();
        $data[0] = count($generos->getAll());
        $data[1] = $generos->customQuery("SELECT * FROM generos LIMIT 10");
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">
        <link rel="stylesheet" href="'. ENTORNO .'/public/css/admin/generos.css">';
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>
                <script src="'. ENTORNO .'/public/js/admin/generos.js"></script>';

        return $this->render('admin/generos/index', $css, $js, $data);
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
            // SE CREA UNA NUEVA INSTANCIA DE LA CLASE 'Genero'
            $genero = new Genero();

            // SE CARGAN LOS DATOS RECIBIDOS EN LOS ATRIBUTOS DE LA CLASE
            $genero->loadData($request->getBody());

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $genero->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"])
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $genero->create();

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
            echo json_encode(['response' => true, 'message' => 'El género fue registrado exitosamente.']);
            exit;
        }

        // SI LA ACCIÓN A REALIZAR ES UPDATE
        if ($data["action"] == "update")
        {
            $genero = new Genero();

            // SE CARGAN LOS DATOS RECIBIDOS EN LOS ATRIBUTOS DE LA CLASE
            $genero->loadData($request->getBody());

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $genero->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"])
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $genero->update([$data["genero"], $data["id"]]);

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
            echo json_encode(['response' => true, 'message' => 'El género fue actualizado exitosamente.']);
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