<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Empleado;
use app\models\Modulo;
use app\models\Usuario;

class UsuarioController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
        Auth::verifyModules();
    }

    public function index()
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/jstable.css">' . PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/usuarios/index.css">' . PHP_EOL;

        /** ==================== @var  $js ==================== */
        $js = '<script src="' . ENTORNO . '/public/js/jstable.min.js"></script>' . PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/usuarios/index.js"></script>' . PHP_EOL;

        /** ==================== @var  $data ==================== */
        $usuarios = new Usuario();
        $data[0] = count($usuarios->getAll());
        $data[1] = $usuarios->customQuery("SELECT usuarios.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', usuarios.usuario, puestos.puesto FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE (empleados.fecha_baja IS NULL OR empleados.fecha_baja = '' OR empleados.fecha_baja > CURRENT_DATE) AND (usuarios.deleted_at IS NULL)");

        /** ==================== RENDER VIEW ==================== */
        return $this->render('usuarios/index', $css, $js, $data);
    }

    public function nuevo()
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/usuarios/nuevo.css">' . PHP_EOL;

        /** ==================== @var  $js ==================== */
        $js = '<script src="' . ENTORNO . '/public/js/usuarios/nuevo.js"></script>' . PHP_EOL;

        /** ==================== @var  $data ==================== */
        $empleados = new Empleado();
        $data[0] = $empleados->customQuery("SELECT empleados.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno) AS empleado FROM empleados WHERE empleados.fecha_baja IS NULL AND empleados.id NOT IN (SELECT usuarios.id FROM usuarios) ORDER BY CONCAT(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno) ASC");

        $modulos = new Modulo();
        $data[1] = $modulos->customQuery("SELECT id, modulo FROM modulos WHERE deleted_at IS NULL ORDER BY id ASC");

        /** ==================== RENDER VIEW ==================== */
        return $this->render('usuarios/nuevo', $css, $js, $data);
    }

    public function editar(Request $request)
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/usuarios/editar.css">' . PHP_EOL;

        /** ==================== @var  $js ==================== */
        $js = '<script src="' . ENTORNO . '/public/js/usuarios/editar.js"></script>' . PHP_EOL;

        /** ==================== @var  $data ==================== */
        $empleado = new Empleado();
        $data[0] = $empleado->customQuery("SELECT usuarios.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', usuarios.usuario, usuarios.modulos FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id WHERE usuarios.id = " . intval($request->getBody()["id"]))[0];

        $modulos = new Modulo();
        $data[1] = $modulos->customQuery("SELECT id, modulo FROM modulos WHERE deleted_at IS NULL ORDER BY id ASC");

        /** ==================== RENDER VIEW ==================== */
        return $this->render('usuarios/editar', $css, $js, $data);
    }

    public function guardar(Request $request)
    {
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
        if ($respuesta["success"] === false) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }

        // SE VALIDA EL CSRF

        // SE PROCESA LA INFORMACIÓN RECIBIDA
        if ($data["action"] == "create") {
            $usuario = new Usuario();
            $usuario->loadData($data);

            $validacion = $usuario->validate();

            if (!$validacion["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $usuario->create();

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
            echo json_encode(['response' => true, 'message' => 'El usuario fue registrado exitosamente.']);
            exit;
        }

        if ($data["action"] == "update") {
            $usuario = new Usuario();

            $usuario->loadData($data);

            $validacion = $usuario->validate();

            if (!$validacion["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $query = "UPDATE usuarios SET usuarios.usuario = '".$usuario->usuario."', usuarios.password = '".password_hash($usuario->password, PASSWORD_DEFAULT)."', usuarios.modulos = '".$usuario->modulos."' WHERE usuarios.id = ".$usuario->id;

            $result = $usuario->customQuery($query);

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
            echo json_encode(['response' => true, 'message' => 'El usuario fue actualizado exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $usuario = new Usuario();

            $usuario->loadData($data);

            $deletedAt = date('Y-m-d');
            $query = "DELETE FROM usuarios WHERE usuarios.id = ".$usuario->id;

            $result = $usuario->customQuery($query);

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
            echo json_encode(['response' => true, 'message' => 'El usuario fue eliminado exitosamente.']);
            exit;
        }
    }
}