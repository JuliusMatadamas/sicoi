<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\EstadoDeInventario;
use app\models\Usuario;

class EstadoDeInventarioController extends Controller
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
     * Muestra la tabla con los estados de inventario creados por el usuario
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/admin/estados_de_inventarios/index.css">';

        // JS
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/admin/estados_de_inventarios/index.js"></script>';

        // DATA
        $estadosDeInventarios = new EstadoDeInventario();

        $data = [];
        $result = $estadosDeInventarios->getEstadosDeInventarios();
        if (gettype($result) == "string") {
            $data[0] = 0;
            $data[1] = $result;
        } else {
            $data[0] = count($result);
            $data[1] = $result;
        }

        // VIEW
        return $this->render('admin/estados_de_inventarios/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * REGISTER
     * Guarda o actualiza en la BD un estado de inventario según la acción recibida
     * ============================================================================================
     */
    public function register(Request $request)
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

        // SI LA ACCIÓN A REALIZAR ES 'create'
        if ($data["action"] == "create") {
            $estadoDeInventario = new EstadoDeInventario();
            $estadoDeInventario->loadData($data);

            if (!$estadoDeInventario->validate()["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $estadoDeInventario->validate()["message"]]);
                exit;
            }

            // SE GUARDAN LOS DATOS
            $result = $estadoDeInventario->create();

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
            echo json_encode(['response' => true, 'message' => 'El estado de inventario fue registrado exitosamente.']);
            exit;
        }

        // SI LA ACCIÓN A REALIZAR ES 'update'
        if ($data["action"] == "update") {
            $estadoDeInventario = new EstadoDeInventario();
            $estadoDeInventario->loadData($data);

            if (!$estadoDeInventario->validate()["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $estadoDeInventario->validate()["message"]]);
                exit;
            }

            // SE GUARDAN LOS DATOS
            $result = $estadoDeInventario->update($data["id"]);

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
            echo json_encode(['response' => true, 'message' => 'El estado de inventario fue actualizado exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $estadoDeInventario = new EstadoDeInventario();
            $estadoDeInventario->loadData($data);

            // SE ELIMINAN LOS DATOS
            $result = $estadoDeInventario->delete($data["id"]);

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
            echo json_encode(['response' => true, 'message' => 'El estado de inventario fue eliminado exitosamente.']);
            exit;
        }
    }
}