<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Permiso;
use app\models\Usuario;

class PermisoController extends Controller
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
     * Muestra la tabla con los permisos solicitados por el usuario
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/permisos/index.css">';

        // JS
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/permisos/index.js"></script>';

        // DATA
        $permiso = new Permiso();
        $permiso->id_usuario = $_SESSION["usuario"]["id"];

        $data = [];
        $result = $permiso->getPermisos();
        if (gettype($result) == "string") {
            $data[0] = 0;
            $data[1] = $result;
        } else {
            $data[0] = count($result);
            $data[1] = $result;
        }

        // VIEW
        return $this->render('permisos/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * CREATE
     * Muestra el formulario para crear una nueva solicitud de permiso para el usuario
     * ============================================================================================
     */
    public function create()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/permisos/create.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/permisos/create.js"></script>';

        // DATA
        $data[0] = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00'];

        // VIEW
        return $this->render('permisos/create', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * STORE
     * Realiza el proceso de registro en la BD del permiso creado por el usuario
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

        // SE VALIDAN LOS DATOS
        $permiso = new Permiso();
        $permiso->loadData($data);
        if (!$permiso->validate()["eval"]) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $permiso->validate()["message"]]);
            exit;
        }

        // SE GUARDAN LOS DATOS
        $result = $permiso->create();

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
        echo json_encode(['response' => true, 'message' => 'La solicitud de permiso fue registrada exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * READ
     * Muestra el formulario para editar una solicitud de permiso para el usuario
     * ============================================================================================
     */
    public function read(Request $request)
    {
        $toGet = new Permiso();
        $result = $toGet->findById($request->getBody()["id"]);

        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/permisos/read.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/permisos/read.js"></script>';

        // DATA
        $data[0] = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00'];
        $data[1] = $result[0];

        // VIEW
        return $this->render('permisos/read', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * UPDATE
     * Realiza el proceso de actualización del permisos editado por el usuario
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

        // SE VALIDAN LOS DATOS
        $permiso = new Permiso();
        $permiso->loadData($data);
        if (!$permiso->validate()["eval"]) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $permiso->validate()["message"]]);
            exit;
        }

        $idPermiso = $data["id_permiso"];

        $result = $permiso->update($idPermiso);

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
        echo json_encode(['response' => true, 'message' => 'La solicitud de permiso fue actualizada exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * UPDATE
     * Realiza el proceso de actualización del permisos editado por el usuario
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

        $permiso = new Permiso();

        $result = $permiso->delete($data["id_permiso"]);

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
        echo json_encode(['response' => true, 'message' => 'La solicitud de permiso fue eliminada exitosamente.']);
        exit;
    }
}