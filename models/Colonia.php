<?php

namespace app\models;

use app\core\DbModel;

class Colonia extends DbModel
{
    // PROPIEDADES
    public string $colonia = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'colonias';
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['colonia', 'id_codigo_postal'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['colonia', 'id_codigo_postal'];
    }
}