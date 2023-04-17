<?php

namespace app\helpers;

class Uid
{
    public static function generateId()
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($chars);
        $random = '';
        for ($i = 0; $i < 100; $i++) {
            $random .= $chars[rand(0, $length - 1)];
        }
        return $random;
    }
}