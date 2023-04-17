<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Categoria;
use app\models\Usuario;

class CategoriaController extends Controller
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
     * Muestra el index de las categorias del almacén
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/almacen/categorias/index.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/almacen/categorias/index.js"></script>' . PHP_EOL;

        // DATA
        $categoria = new Categoria();
        $data = $categoria->customQuery("SELECT categorias.id, categorias.categoria FROM categorias WHERE categorias.deleted_at IS NULL ORDER BY categorias.categoria ASC");

        // VIEW
        return $this->render('almacen/categoria/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * REGISTER
     * Registra en la BD un nuevo registro o una actualización de una categoría de almacén
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

        $categoria = new Categoria();

        if ($data["action"] == "create") {
            $categoria->loadData($data);

            $result = $categoria->create();

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
            echo json_encode(['response' => true, 'message' => 'La categoría fue registrada exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $id = intval($data["id"]);
            $date = date('Y-m-d');
            $query = "UPDATE categorias SET categorias.deleted_at = '$date' WHERE categorias.id = $id";

            $result = $categoria->customQuery($query);

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
            echo json_encode(['response' => true, 'message' => 'La categoría fue eliminada exitosamente.']);
            exit;
        }

        if ($data["action"] == "update") {
            $id = intval($data["id"]);
            $c = $data["categoria"];
            $query = "UPDATE categorias SET categorias.categoria = '$c' WHERE categorias.id = $id";

            $result = $categoria->customQuery($query);

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
            echo json_encode(['response' => true, 'message' => 'La categoría fue actualizada exitosamente.']);
            exit;
        }
    }
}