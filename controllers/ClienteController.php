<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Cliente;
use app\models\Colonia;
use app\models\Genero;
use app\models\Usuario;

class ClienteController extends Controller
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
     * Muestra la tabla con los clientes registrados en la aplicación
     * ============================================================================================
     */
    public function index()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/clientes/index.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/clientes/index.js"></script>';

        // DATA
        $cliente = new Cliente();

        $data = [];
        $query = "SELECT clientes.id, clientes.nombre, clientes.apellido_paterno, clientes.apellido_materno, clientes.fecha_nacimiento, clientes.id_genero, generos.genero, clientes.telefono_casa, clientes.telefono_celular, clientes.email, colonias.id_codigo_postal, codigos_postales.codigo_postal, clientes.id_colonia, colonias.colonia, clientes.rfc, clientes.calle, clientes.numero_exterior, clientes.numero_interior, clientes.observaciones FROM clientes INNER JOIN generos ON clientes.id_genero = generos.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id LIMIT 10";
        $result = $cliente->customQuery($query);
        $query = "SELECT COUNT(id) AS 'total' FROM clientes";
        $resultB = $cliente->customQuery($query);
        if (gettype($result) == "string") {
            $data[0] = 0;
            $data[1] = $result;
        } else {
            $data[0] = intval($resultB[0]["total"]);
            $data[1] = $result;
        }

        // VIEW
        return $this->render('clientes/index', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * STORE
     * Guarda en la BD un nuevo cliente
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

        $cliente = new Cliente();

        $cliente->loadData($data);

        // SE VALIDA LA INFORMACIÓN RECIBIDA
        $validacion = $cliente->validate();

        // SI LA INFORMACIÓN NO ES VÁLIDA
        if (!$validacion["eval"]) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $validacion["message"]]);
            exit;
        }

        // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
        $result = $cliente->create();

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
        echo json_encode(['response' => true, 'message' => 'El cliente fue registrado exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * CREATE
     * Muestra el formulario para registrar un nuevo cliente en la aplicación
     * ============================================================================================
     */
    public function create()
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/clientes/create.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/clientes/create.js"></script>';

        $generos = new Genero();
        $data["generos"] = $generos->getAll();

        // VIEW
        return $this->render('clientes/create', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * EDIT
     * Muestra el formulario para editar un cliente
     * ============================================================================================
     */
    public function edit(Request $request)
    {
        // SI NO SE RECIBE EL ID DEL CLIENTE
        if (!isset($request->getBody()["id"])) {
            $idCliente = 0;
        } else {
            $idCliente = $request->getBody()["id"];
        }
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/clientes/edit.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/clientes/edit.js"></script>';

        // DATA
        $info = new Cliente();
        $query = "SELECT clientes.id AS 'id_cliente', clientes.nombre, clientes.apellido_paterno, clientes.apellido_materno, clientes.fecha_nacimiento, clientes.id_genero, generos.genero, clientes.telefono_casa, clientes.telefono_celular, clientes.email, codigos_postales.id, codigos_postales.codigo_postal, clientes.id_colonia, colonias.colonia, clientes.rfc, clientes.calle, clientes.calle, clientes.numero_exterior, clientes.numero_interior, clientes.observaciones FROM clientes INNER JOIN generos ON clientes.id_genero = generos.id INNER JOIN colonias ON clientes.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id WHERE clientes.id = '$idCliente'";
        $result = $info->customQuery($query);

        if (count($result) === 0) {
            $data["generos"] = [];
            $data["colonias"] = [];
            $data["cliente"] = [];
        } else {
            $generos = new Genero();
            $data["generos"] = $generos->getAll();

            $query = "SELECT colonias.id, colonias.colonia FROM colonias INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id WHERE codigos_postales.codigo_postal = " . $result[0]["codigo_postal"];

            $colonias = new Colonia();
            $data["colonias"] = $colonias->customQuery($query);

            $data["cliente"] = $result[0];
        }

        // VIEW
        return $this->render('clientes/editar', $css, $js, $data);
    }

    /**
     * ============================================================================================
     * UPDATE
     * Actualiza un cliente en la BD
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

        $cliente = new Cliente();

        $cliente->loadData($data);

        // SE VALIDA LA INFORMACIÓN RECIBIDA
        $validacion = $cliente->validate();

        // SI LA INFORMACIÓN NO ES VÁLIDA
        if (!$validacion["eval"]) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $validacion["message"]]);
            exit;
        }

        // SI LA INFORMACIÓN ES VÁLIDA, SE ACTUALIZA EN LA BD
        $result = $cliente->update($data["id_cliente"]);

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
        echo json_encode(['response' => true, 'message' => 'El cliente fue actualizado exitosamente.']);
        exit;
    }

    /**
     * ============================================================================================
     * DELETE
     * Realiza el borrado lógico de un cliente en la BD
     * ============================================================================================
     */
    public function delete(Request $request)
    {
    }

    public function reportes(Request $request)
    {
        // CSS
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/clientes/reportes.css">';

        // JS
        $js = '<script src="' . ENTORNO . '/public/js/clientes/reportes.js"></script>';

        // DATA
        $data = [];

        // VIEW
        return $this->render('clientes/reportes', $css, $js, $data);
    }
}