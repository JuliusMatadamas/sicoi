<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Usuario;
use app\models\Vacacion;

class VacacionController extends Controller
{
    /**
     * ============================================================================================
     * CONSTRUCTOR
     * ============================================================================================
     */
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
        Auth::verifyModules();
    }

    /**
     * ============================================================================================
     * INDEX
     * Muestra la tabla con las solicitudes de vacaciones realizadas por el usuario
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/vacaciones/index.css">';

        // JS
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/vacaciones/index.js"></script>';

        // DATA
        $vacacion = new Vacacion();
        $vacacion->id_usuario = $_SESSION["usuario"]["id"];

        $data = [];
        $result = $vacacion->getVacaciones();
        if (gettype($result) == "string") {
            $data[0] = 0;
            $data[1] = $result;
        } else {
            $data[0] = count($result);
            $data[1] = $result;
        }

        // VIEW
        return $this->render('vacaciones/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * CREATE
     * Muestra el formulario para crear una solicitud de vacaciones
     * ============================================================================================
     */
    public function create()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/vacaciones/create.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/vacaciones/create.js"></script>';

        // DATA
        $vacaciones = new Vacacion();
        $vacaciones->id_usuario = $_SESSION["usuario"]["id"];
        $data = $vacaciones->getVacaciones();

        // VIEW
        return $this->render('vacaciones/create', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * STORE
     * Guarda en la BD una solicitud de vacaciones
     * ============================================================================================
     */
    public function store(Request $request)
    {
        // SE CARGAN LOS DATOS RECIBIDOS
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        if (Usuario::validateToken($token) === false) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }
        unset($data["token"]);

        // SE VALIDA EL CSRF
        $csrf = $data["csrf"];
        $idUsuario = $data["id_usuario"];
        if (Usuario::validateCsrf($csrf, $idUsuario) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
            exit;
        }
        unset($data["csrf"]);

        $fechas = explode(",", $data["vacaciones"]);

        if (count($fechas) == 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se recibió ninguna fecha a solicitar de vacaciones.']);
            exit;
        }

        foreach ($fechas as $key => $value) {
            $vacacion = new Vacacion();
            $vacacion->id_usuario = $idUsuario;
            $vacacion->age = $data["age"];
            $vacacion->fecha = $value;

            if (!$vacacion->validate()["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $vacacion->validate()["message"]]);
                exit;
            }

            // SE GUARDAN LOS DATOS
            $result = $vacacion->create();

            // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "string") {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $result]);
                exit;
            }

            // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "boolean") {
                if (!$result) {
                    $statusCode = new Response();
                    $statusCode->setStatusCode(400);
                    echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                    exit;
                }
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'La solicitud de vacaciones fue registrada exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * EDIT
     * Muestra el formulario para editar una solicitud de vacaciones
     * ============================================================================================
     */
    public function edit(Request $request)
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/vacaciones/edit.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/vacaciones/edit.js"></script>';

        // DATA
        $data = $request->getBody();
        unset($data["uri"]);

        $vacacion = new Vacacion();

        $data = $vacacion->findById($data["id"])[0];

        // VIEW
        return $this->render('vacaciones/edit', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * UPDATE
     * Actualiza en la BD la solicitud de vacaciones
     * ============================================================================================
     */
    public function update(Request $request)
    {
        // SE CARGAN LOS DATOS RECIBIDOS
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        if (Usuario::validateToken($token) === false) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }
        unset($data["token"]);

        // SE VALIDA EL CSRF
        $csrf = $data["csrf"];
        $idUsuario = $data["id_usuario"];
        if (Usuario::validateCsrf($csrf, $idUsuario) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
            exit;
        }
        unset($data["csrf"]);

        $vacacion = new Vacacion();
        $result = $vacacion->customUpdate("UPDATE vacaciones SET fecha = '".$data["fecha"]."' WHERE id = " . $data["id"]);

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string") {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "boolean") {
            if (!$result) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'La solicitud de vacaciones fue actualizada exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * DELETE
     * Elimina logicamente en la BD la solicitud de vacaciones
     * ============================================================================================
     */
    public function delete(Request $request)
    {
        // SE CARGAN LOS DATOS RECIBIDOS
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        if (Usuario::validateToken($token) === false) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }
        unset($data["token"]);

        // SE VALIDA EL CSRF
        $csrf = $data["csrf"];
        $idUsuario = $data["id_usuario"];
        if (Usuario::validateCsrf($csrf, $idUsuario) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'El csrf no es válido.']);
            exit;
        }
        unset($data["csrf"]);

        $vacacion = new Vacacion();

        // SE ELIMINA EL REGISTRO
        $date = date('Y-m-d');
        $result = $vacacion->customUpdate("UPDATE vacaciones SET deleted_at = '$date' WHERE id = " . $data["id_vacacion"]);

        // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "string") {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
        if (gettype($result) == "boolean") {
            if (!$result) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                exit;
            }
        }

        // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'message' => 'La solicitud de vacaciones fue eliminada exitosamente.']);
        exit;
    }
}