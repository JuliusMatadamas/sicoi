<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\core\Session;
use app\helpers\Auth;
use app\models\Usuario;

class AuthController extends Controller
{
    public function __construct()
    {
        Auth::ifSessionStarted();
    }

    /**
     * @return void
     */
    public function index()
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/auth/login.css">';

        /** ==================== @var  $js ==================== */
        $js = '<script src="'. ENTORNO .'/public/js/auth/login.js"></script>';

        /** ==================== @var  $data ==================== */
        $data = [];

        $this->setLayout('auth');
        return $this->render('auth/login', $css, $js, $data);
    }

    /**
     * @return void
     */
    public function login(Request $request)
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
        if($respuesta["success"] === false)
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }

        unset($data["token"]);

        $usuario = new  Usuario();

        $usuario->loadData($data);

        $login = $usuario->login();

        if (!$login["eval"]) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $login["message"]]);
            exit;
        }

        echo json_encode(['response' => true, 'message' => 'Los datos son válidos, en unos instantes el sistema te redireccionará.']);
            exit;
    }

    /**
     * @return void
     */
    public function reset()
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/auth/reset.css">';

        /** ==================== @var  $js ==================== */
        $js = '<script src="'. ENTORNO .'/public/js/auth/reset.js"></script>';

        /** ==================== @var  $data ==================== */
        $data = [];

        $this->setLayout('auth');
        return $this->render('auth/reset', $css, $js, $data);
    }

    public function send_token(Request $request) {
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
        if($respuesta["success"] === false)
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }

        unset($data["token"]);

        $usuario = new Usuario();

        if (empty($usuario->existEmail($data["email"]))) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se encontraron coincidencias, verifique el correo electrónico ingresado o si ha sido dado de baja de la aplicación.']);
            exit;
        } else  {
            $id = $usuario->existEmail($data["email"])[0]["id"];

            // VALIDAR SI EL ID TIENE UNA SESIÓN INICIADA
            if (!Auth::checkSession($id)) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'El correo es válido pero existe una sesión activa, por lo que no se puede enviar el enlace de reseteo; Espere unos minutos e intente de nuevo.']);
                exit;
            }

            if (!$usuario->sendMailReset($data["email"], $id)) {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'No se pudo realizar el envío del enlace de reset de la contraseña por un error en el servidor, intente nuevamente o informe a la administración si este error persiste..']);
                exit;
            }

            echo json_encode(['response' => true, 'message' => '<p>Se realizó el envío del enlace de reset, revise su correo electrónico, en caso de no recibirse ningún correo verifique en su carpeta de spam, si aún así no se ha recibido el correo, informe a la administración de esto.</p><p>NOTA: el enlace tiene una vigencia de 15 minutos a partir de este mensaje, proceda inmediatamente con el reseteo de la contraseña o tendrá que generar un nuevo enlace pasado este tiempo.</p>']);
            exit;
        }
    }

    public function nueva_clave(Request $request)
    {
        /** ==================== @var  $css ==================== */
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/auth/nueva.css">';

        /** ==================== @var  $js ==================== */
        $js = '<script src="'. ENTORNO .'/public/js/auth/nueva.js"></script>';

        /** ==================== @var  $data ==================== */
        $data = $request->getBody();
        unset($data["uri"]);

        if (!empty($data)) {
            $usuario = new Usuario();
            if (empty($usuario->evalToken($data))) {
                $data["result"] = false;
            } else {
                $data["result"] = true;
            }
        }

        $this->setLayout('auth');
        return $this->render('auth/nueva', $css, $js, $data);
    }

    public function update_clave(Request $request)
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
        if($respuesta["success"] === false)
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }

        unset($data["token"]);

        $usuario = new Usuario();

        if ($usuario->updatePass($data) === 0) {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => '<p>Ocurrió un error y no se pudo realizar la operación, intente nuevamente.</p><p>Si este error persiste, informe a la administración.</p>']);
            exit;
        }

        echo json_encode(['response' => true, 'message' => 'Los datos fueron actualizados, intente iniciar sesión con la nueva contraseña.']);
        exit;
    }

    public function destroy()
    {
        Session::destroy();
    }
}