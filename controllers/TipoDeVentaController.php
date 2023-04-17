<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\TipoDeVenta;
use app\models\Usuario;

class TipoDeVentaController extends Controller
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
     * Muestra la tabla con los tipos de ventas que se realizarán en el sistema
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/admin/tipos_de_ventas/index.css">';

        // JS
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/admin/tipos_de_ventas/index.js"></script>';

        // DATA
        $tipoDeVenta = new TipoDeVenta();

        $data = [];
        $result = $tipoDeVenta->getTipos();
        if (gettype($result) == "string") {
            $data[0] = 0;
            $data[1] = $result;
        } else {
            $data[0] = count($result);
            $data[1] = $result;
        }

        // VIEW
        return $this->render('admin/tipos_de_ventas/index', $css, $js, $data);
    }

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

        if ($data["action"] == "create") {
            unset($data["id_usuario"]);
            unset($data["id"]);
            unset($data["action"]);

            // SE VALIDAN LOS DATOS
            $tipoDeVenta = new TipoDeVenta();
            $tipoDeVenta->loadData($data);
            if (!$tipoDeVenta->validate()["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $tipoDeVenta->validate()["message"]]);
                exit;
            }

            // SE GUARDAN LOS DATOS
            $result = $tipoDeVenta->create();

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
            echo json_encode(['response' => true, 'message' => 'El tipo de venta se registró exitosamente.']);
            exit;
        }

        if ($data["action"] == "update") {
            // SE VALIDAN LOS DATOS
            $tipoDeVenta = new TipoDeVenta();
            $tipoDeVenta->loadData($data);
            if (!$tipoDeVenta->validate()["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $tipoDeVenta->validate()["message"]]);
                exit;
            }

            // SE GUARDAN LOS DATOS
            $id = $data["id"];
            $tipo = $data["tipo"];
            $result = $tipoDeVenta->customUpdate("UPDATE tipos_de_ventas SET tipo = '$tipo' WHERE id = $id");

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
            echo json_encode(['response' => true, 'message' => 'El tipo de venta se actualizó exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $tipoDeVenta = new TipoDeVenta();
            // SE GUARDAN LOS DATOS
            $id = $data["id"];
            $date = date('Y-m-d');
            $result = $tipoDeVenta->customUpdate("UPDATE tipos_de_ventas SET deleted_at = '$date' WHERE id = $id");

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
            echo json_encode(['response' => true, 'message' => 'El tipo de venta se eliminó exitosamente.']);
            exit;
        }
    }
}