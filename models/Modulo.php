<?php

namespace app\models;

use app\core\DbModel;

class Modulo extends DbModel
{
    // PROPIEDADES
    public string $modulo = '';
    public string $icono = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'modulos';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if ( strlen(trim($this->modulo)) < 4 )
        {
            return $res = ['eval' => false, 'message' => 'El módulo debe tener al menos 4 caracteres.'];
        }

        if (!preg_match('/^[\p{L} ]+$/u', $this->modulo)){
            return $res = ['eval' => false, 'message' => 'El módulo debe tener únicamente letras.'];
        }

        if ( strlen(trim($this->icono)) < 5 )
        {
            return $res = ['eval' => false, 'message' => 'La clase del ícono debe tener al menos 5 caracteres.'];
        }

        return $res = ['eval' => true];
    }

    // CREACIÓN DE UN NUEVO REGISTRO EN LA BD
    public function create()
    {
        $date = date('Y-m-d');
        $this->created_at = $date;
        return $this->save();
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['modulo', 'icono', 'created_at'];
    }

    public function attributesToUpdate(): array
    {
        return ['modulo', 'icono'];
    }
}