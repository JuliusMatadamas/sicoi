<?php

namespace app\helpers;

use app\core\Response;
use app\models\Usuario;

class Auth
{
    public static function ifSessionStarted()
    {
        $location = str_replace("uri=", "", explode("/", $_SERVER["QUERY_STRING"])[0]);

        if (isset($_SESSION["usuario"]) && !empty($_SESSION["usuario"])) {
            Response::redirect("/info");
        }
    }

    public static function verifySession()
    {
        if (!isset($_SESSION["usuario"])) {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                Response::redirect('/log_out');
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                return ["eval" => false, "message" => "No se ha iniciado sesión o esta ya ha caducado, favor de iniciar sesión en la aplicación."];
            }
        }

        if (empty($_SESSION["usuario"])) {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                Response::redirect('/log_out');
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                return ["eval" => false, "message" => "No se ha iniciado sesión o esta ya ha caducado, favor de iniciar sesión en la aplicación."];
            }
        }
    }

    public static function verifyJwt()
    {
        if (!isset($_COOKIE["jwt"])) {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                Response::redirect('/log_out');
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                return ["eval" => false, "message" => "No se realizó el proceso porque no se recibió un token requerido por la aplicación, debe iniciar sesión nuevamente en la aplicación."];
            }
        }

        if (strlen($_COOKIE["jwt"]) == 0) {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                Response::redirect('/log_out');
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                return ["eval" => false, "message" => "No se realizó el proceso porque no se recibió un token requerido por la aplicación, debe iniciar sesión nuevamente en la aplicación."];
            }
        } else {
            $usuario = new Usuario();
            $expiration = $usuario->customQuery("SELECT usuarios.expiration_jwt FROM usuarios WHERE usuarios.jwt = '" . $_COOKIE["jwt"] . "'")[0]["expiration_jwt"];

            // SI LA FECHA DE EXPIRACIÓN DEL JWT YA PASÓ
            if ((strtotime($expiration) - strtotime(date('Y-m-d H:i:s'))) < 0) {
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    Response::redirect('/log_out');
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $statusCode = new Response();
                    $statusCode->setStatusCode(400);
                    return ["eval" => false, "message" => "No se realizó el proceso porque un token requerido por la aplicación esta caducado, debe iniciar sesión nuevamente en la aplicación."];
                }
            } else {
                $time = time() + (60 * 30);
                // SE ACTUALIZA LA COOKIE
                setcookie("jwt", $_COOKIE["jwt"], $time, "/");
                // SE ACTUALIZA LA FECHA DE EXPIRACIÓN EN LA BD
                $usuario->customUpdate("UPDATE usuarios SET usuarios.expiration_jwt = '" . date('Y-m-d H:i:s', $time) . "' WHERE usuarios.jwt = '" . $_COOKIE["jwt"] . "'");
            }
        }
    }

    public static function verifyModules()
    {
        // SE VALIDA SI EL USUARIO TIENE AUTORIZADO EL MODULO
        $output = [];
        for ($i = 0; $i < count($_SESSION["usuario"]["modulos"]); $i++) {
            $m = strtolower($_SESSION["usuario"]["modulos"][$i]["modulo"]);
            $m = str_replace("á", "a", $m);
            $m = str_replace("é", "e", $m);
            $m = str_replace("í", "i", $m);
            $m = str_replace("ó", "o", $m);
            $m = str_replace("ú", "u", $m);
            $output[$i]["modulo"] = $m;
        }

        $modulosFiltrados = array_map("unserialize", array_unique(array_map("serialize", $output)));

        $location = str_replace("uri=", "", explode("/", $_SERVER["QUERY_STRING"])[0]);

        $cont = 0;

        $modulos = [];

        foreach ($modulosFiltrados as $key => $value) {
            $modulos[$cont] = $value["modulo"];
            $cont++;
        }

        if (in_array($location, $modulos) === false) {
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                Response::redirect('/not_authorized');
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => 'No cuentas con los permisos para acceder al recurso solicitado.']);
            }
        }
    }

    public static function checkSession($id)
    {
        $usuario = new Usuario();
        $sql = "SELECT usuarios.expiration_jwt FROM usuarios WHERE usuarios.id = " . $id;
        $result = $usuario->customQuery($sql);
        if ((strtotime($result[0]["expiration_jwt"]) - strtotime(date('Y-m-d H:i:s'))) > 0) {
            return false;
        } else {
            return true;
        }
    }
}