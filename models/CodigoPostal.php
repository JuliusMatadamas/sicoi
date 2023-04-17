<?php

namespace app\models;

use app\core\DbModel;

class CodigoPostal extends DbModel
{
    // PROPIEDADES
    public string $codigo_postal = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'codigos_postales';
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['codigo_postal'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['codigo_postal'];
    }
}