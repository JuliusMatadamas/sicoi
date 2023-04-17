<?php

namespace app\helpers;

class Functions
{
    public static function isValidDate($date)
    {
        $d = \DateTime::createFromFormat("Y-m-d", $date);
        return $d && $d->format("Y-m-d") === $date;
    }

    public static function validarFechaAnterior($fecha)
    {
        $fechaActual = date('Y-m-d');

        if ($fecha < $fechaActual) {
            return true;
        } else {
            return false;
        }
    }

    public static function validarFechaNoAnterior($fecha1, $fecha2)
    {
        if ($fecha1 > $fecha2) {
            return true;
        } else {
            return false;
        }
    }
}