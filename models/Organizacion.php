<?php

namespace app\models;

use app\core\DbModel;
use app\helpers\Uid;

class Organizacion extends DbModel
{
    // PROPIEDADES
    public string $id = '';
    public string $organizacion = '';
    public string $descripcion = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'organizaciones';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (strlen(trim($this->organizacion)) < 3) {
            return $res = ['eval' => false, 'message' => 'La organización debe tener al menos 3 caracteres.'];
        }

        if (strlen(trim($this->organizacion)) > 10) {
            return $res = ['eval' => false, 'message' => 'La organización no debe tener más de 10 caracteres.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->organizacion)) {
            return $res = ['eval' => false, 'message' => 'El nombre de la organización debe tener solo letras y espacios.'];
        }

        if (strlen(trim($this->descripcion)) < 5) {
            return $res = ['eval' => false, 'message' => 'La descripción de la organización debe tener al menos 5 caracteres.'];
        }

        if (strlen(trim($this->descripcion)) > 255) {
            return $res = ['eval' => false, 'message' => 'La descripción de la organización no debe tener más de 255 caracteres.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->descripcion)) {
            return $res = ['eval' => false, 'message' => 'La descripción de la organización debe tener solo letras y espacios.'];
        }

        return $res = ['eval' => true];
    }

    // CREACIÓN DE UN NUEVO REGISTRO EN LA BD
    public function create()
    {
        $this->id = Uid::generateId();
        $date = date('Y-m-d');
        $this->created_at = $date;
        return $this->save();
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['id', 'organizacion', 'descripcion', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['organizacion', 'descripcion'];
    }
}