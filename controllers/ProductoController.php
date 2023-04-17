<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Categoria;
use app\models\Producto;
use app\models\Usuario;

class ProductoController extends Controller
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
     * Muestra el index de los productos del almacén
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/almacen/productos/index.css">' . PHP_EOL;

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/almacen/productos/index.js"></script>' . PHP_EOL;

        // DATA
        $categoria = new Categoria();
        $data["categorias"] = $categoria->customQuery("SELECT categorias.id, categorias.categoria FROM categorias WHERE categorias.deleted_at IS NULL ORDER BY categorias.categoria ASC");
        $producto = new Producto();
        $data["productos"] = $producto->customQuery("SELECT productos.id, productos.nombre, productos.id_categoria, productos.descripcion, categorias.categoria FROM productos INNER JOIN categorias ON productos.id_categoria = categorias.id WHERE productos.deleted_at IS NULL AND categorias.deleted_at IS NULL LIMIT 5");
        $data["registros"] = $producto->customQuery("SELECT COUNT(productos.id) AS 'total' FROM productos WHERE productos.deleted_at IS NULL");

        // VIEW
        return $this->render('almacen/producto/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * REGISTER
     * Registra en la BD un nuevo registro o una actualización de un producto de almacén
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

        $producto = new Producto();

        if ($data["action"] == "create") {
            $producto->loadData($data);

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $producto->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $producto->create();

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
            echo json_encode(['response' => true, 'message' => 'El producto fue registrado exitosamente.']);
            exit;
        }

        if ($data["action"] == "update") {
            $producto->loadData($data);

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $producto->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE ACTUALIZA EN LA BD
            $result = $producto->customQuery("UPDATE productos SET productos.nombre = '" . $producto->nombre . "', productos.descripcion = '" . $producto->descripcion . "', productos.id_categoria = " . $producto->id_categoria . " WHERE productos.id = " . $data["id"]);

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
            echo json_encode(['response' => true, 'message' => 'El producto fue actualizado exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $producto->loadData($data);

            // SE ACTUALIZA LA INFORMACIÓN EN LA BD
            $result = $producto->customQuery("DELETE FROM productos WHERE productos.id = " . $data["id"]);

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
            echo json_encode(['response' => true, 'message' => 'El producto fue eliminado exitosamente.']);
            exit;
        }
    }
}