<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Organizacion;
use app\models\Usuario;

class OrganizacionController extends Controller
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
     * Muestra el index de las organizaciones registradas en el almacén
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/almacen/organizacion/index.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/almacen/organizacion/index.js"></script>' . PHP_EOL;

        // DATA
        $organizacion = new Organizacion();
        $data["organizaciones"] = $organizacion->customQuery("SELECT organizaciones.id, organizaciones.organizacion, organizaciones.descripcion FROM organizaciones WHERE organizaciones.deleted_at IS NULL ORDER BY organizaciones.organizacion ASC");

        $result = $organizacion->customQuery("SELECT COUNT(organizaciones.id) AS 'total' FROM organizaciones WHERE organizaciones.deleted_at IS NULL");

        $data["registros"] = intval($result[0]["total"]);

        // VIEW
        return $this->render('almacen/organizacion/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * REGISTER
     * Registra en la BD un nuevo registro o una actualización de una organización en el almacén
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

        $organizacion = new Organizacion();

        if ($data["action"] == "create") {
            $organizacion->loadData($data);

            $result = $organizacion->create();

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
            echo json_encode(['response' => true, 'message' => 'La organizacion fue registrada exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $id = $data["id"];
            $date = date("Y-m-d");
            $query = "UPDATE organizaciones SET organizaciones.deleted_at = '$date' WHERE organizaciones.id = '$id'";

            $result = $organizacion->customQuery($query);

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
            echo json_encode(['response' => true, 'message' => 'La organización fue eliminada exitosamente.']);
            exit;
        }

        if ($data["action"] == "update") {
            $id = $data["id"];
            $o = $data["organizacion"];
            $d = $data["descripcion"];
            $query = "UPDATE organizaciones SET organizacion = '$o', descripcion = '$d'  WHERE organizaciones.id = '$id'";

            $result = $organizacion->customQuery($query);

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
            echo json_encode(['response' => true, 'message' => 'La organización fue actualizada exitosamente.']);
            exit;
        }
    }
}