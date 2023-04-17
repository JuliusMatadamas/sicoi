<?php

namespace app\models;

use app\core\DbModel;

class Producto extends DbModel
{
    // PROPIEDADES
    public string $nombre = '';
    public string $descripcion = '';
    public int $id_categoria = 0;
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'productos';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (strlen(trim($this->nombre)) < 3) {
            return $res = ['eval' => false, 'message' => 'El nombre del producto debe tener al menos 3 caracteres.'];
        }

        if (strlen(trim($this->nombre)) > 45) {
            return $res = ['eval' => false, 'message' => 'El nombre del producto no debe tener más de 45 caracteres.'];
        }

        if (strlen(trim($this->descripcion)) < 5) {
            return $res = ['eval' => false, 'message' => 'La descripción del producto debe tener al menos 5 caracteres.'];
        }

        if (strlen(trim($this->descripcion)) > 255) {
            return $res = ['eval' => false, 'message' => 'La descripción del producto no debe tener más de 255 caracteres.'];
        }

        if ($this->id_categoria == 0) {
            return $res = ['eval' => false, 'message' => 'El id de categoria no es válido.'];
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
        return ['nombre', 'descripcion', 'id_categoria', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['nombre', 'descripcion', 'id_categoria'];
    }
}