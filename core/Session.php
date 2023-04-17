<?php

namespace app\core;

use app\models\Usuario;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public static function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    public static function destroy()
    {
        if (isset($_SESSION["usuario"]) && !empty($_SESSION["usuario"])) {
            $usuario = new Usuario();
            $usuario->customUpdate("UPDATE usuarios SET usuarios.token = '', usuarios.expiration_token = NULL, usuarios.csrf = '', usuarios.jwt = '', usuarios.expiration_jwt = NULL WHERE usuarios.id = " . $_SESSION["usuario"]["id"]);
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"] );
        }

        setcookie("jwt", "", time() - 3600);

        session_destroy();

        Response::redirect('');
    }
}