<?php

namespace app\models;

use app\core\DbModel;

class Genero extends DbModel
{
    // PROPIEDADES
    public string $genero = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'generos';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if ( strlen(trim($this->genero)) < 3 )
        {
            return $res = ['eval' => false, 'message' => 'El género debe tener al menos 3 caracteres.'];
        }

        if (!preg_match('/^[\p{L} ]+$/u', $this->genero)){
            return $res = ['eval' => false, 'message' => 'El género debe tener únicamente letras.'];
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
        return ['genero', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['genero'];
    }
}