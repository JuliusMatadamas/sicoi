<?php

namespace app\models;

use app\core\DbModel;

class Categoria extends DbModel
{
    // PROPIEDADES
    public string $categoria = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'categorias';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if ( strlen(trim($this->categoria)) < 7 )
        {
            return $res = ['eval' => false, 'message' => 'La categoría debe tener al menos 7 caracteres.'];
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
        return ['categoria', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['categoria'];
    }
}