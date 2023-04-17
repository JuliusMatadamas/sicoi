<?php

namespace app\core;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public static function redirect(string $string)
    {
        header('Location: ' . ENTORNO . $string);
    }
}