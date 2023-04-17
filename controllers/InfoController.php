<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Empleado;
use app\models\Incapacidad;
use app\models\Usuario;

class InfoController extends Controller
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
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/info/create.css">';

        /** ==================== @var  $js ==================== */
        $js = '<script src="' . ENTORNO . '/public/js/info/create.js"></script>';

        /** ==================== @var  $data ==================== */
        $data = [];

        return $this->render('info/index', $css, $js, $data);
    }

    public function datos()
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="' . ENTORNO . '/public/css/info/datos.css">';

        /** ==================== @var  $js ==================== */
        $js = '<script src="' . ENTORNO . '/public/js/info/datos.js"></script>';

        /** ==================== @var  $data ==================== */
        $empleado = new Empleado();
        $data['empleado'] = $empleado->findById($_SESSION["usuario"]["id"]);

        $data['cp'] = $empleado->customQuery("SELECT codigos_postales.codigo_postal FROM empleados INNER JOIN colonias ON empleados.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id WHERE empleados.id_colonia = " . $data['empleado'][0]['id_colonia']);

        $data['colonias'] = $empleado->customQuery("SELECT colonias.id, colonias.colonia FROM colonias INNER JOIN codigos_postales ON  colonias.id_codigo_postal = codigos_postales.id WHERE codigos_postales.codigo_postal = " . $data['cp'][0]['codigo_postal']);

        return $this->render('info/datos', $css, $js, $data);
    }

    public function guardarDatos(Request $request)
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

        $empleado = new Empleado();
        $empleado->loadData($data);

        // SI NO SE PASA LA VALIDACIÓN
        if (!$empleado->validateDatos()["eval"]) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $empleado->validateDatos()["message"]]);
            exit;
        }

        $query = "UPDATE empleados SET id_colonia = ".$data["id_colonia"].", calle = '".$data["calle"]."', numero_exterior = '".$data["numero_exterior"]."', numero_interior = '".$data["numero_interior"]."', email = '".$data["email"]."', telefono_casa = '".$data["telefono_casa"]."', telefono_celular = '".$data["telefono_celular"]."' WHERE id = ".$data["id"];

        $result = $empleado->customUpdate($query);

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
        echo json_encode(['response' => true, 'message' => 'La información fue actualizada exitosamente.']);
        exit;
    }

    public function incapacidad()
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">'.PHP_EOL;
        $css .= '<link rel="stylesheet" href="' . ENTORNO . '/public/css/info/incapacidades.css">';

        /** ==================== @var  $js ==================== */
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>'.PHP_EOL;
        $js .= '<script src="' . ENTORNO . '/public/js/info/incapacidades.js"></script>';

        /** ==================== @var  $data ==================== */
        $incapacidades = new Incapacidad();
        $data[0] = count($incapacidades->customQuery("SELECT incapacidades.* FROM incapacidades WHERE incapacidades.id_usuario = ".$_SESSION["usuario"]["id"]." LIMIT 10"));
        $data[1] = $incapacidades->customQuery("SELECT incapacidades.* FROM incapacidades WHERE incapacidades.id_usuario = ".$_SESSION["usuario"]["id"]." LIMIT 10");

        return $this->render('info/incapacidades', $css, $js, $data);
    }

    public function modificarIncapacidad(Request $request)
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
            $incapacidad = new Incapacidad();
            $incapacidad->loadData($data);

            $result = $incapacidad->validate();

            if (!$result["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $result["message"]]);
                exit;
            }

            $result = $incapacidad->create();

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
            echo json_encode(['response' => true, 'message' => 'La incapacidad fue registrada exitosamente.']);
            exit;
        }

        if ($data["action"] == "update") {
            $incapacidad = new Incapacidad();
            $incapacidad->loadData($data);

            $result = $incapacidad->validateUpdate();

            if (!$result["eval"]) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $result["message"]]);
                exit;
            }

            $result = $incapacidad->update($data["id"]);

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
            echo json_encode(['response' => true, 'message' => 'La incapacidad fue actualizada exitosamente.']);
            exit;
        }

        if ($data["action"] == "delete") {
            $incapacidad = new Incapacidad();
            $incapacidad->loadData($data);

            $result = $incapacidad->delete();

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
            echo json_encode(['response' => true, 'message' => 'La incapacidad fue eliminada exitosamente.']);
            exit;
        }
    }

    public function permisos()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('info/permisos', $css, $js, $data);
    }

    public function vacaciones()
    {
        $css = '';
        $js = '';
        $data = [];
        return $this->render('info/vacaciones', $css, $js, $data);
    }
}