<?php

namespace app\models;

use app\core\DbModel;

class SubModulo extends DbModel
{
    // PROPIEDADES
    public string $submodulo = '';
    public string $ruta = '';
    public int $id_modulo = 0;
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'submodulos';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if ( strlen(trim($this->submodulo)) < 4 )
        {
            return $res = ['eval' => false, 'message' => 'El submódulo debe tener al menos 4 caracteres.'];
        }

        if (!preg_match('/^[\p{L} ]+$/u', $this->submodulo)){
            return $res = ['eval' => false, 'message' => 'El submódulo debe tener únicamente letras.'];
        }

        if ( strlen(trim($this->ruta)) < 5 )
        {
            return $res = ['eval' => false, 'message' => 'La ruta del submódulo debe tener al menos 5 caracteres.'];
        }

        if ( $this->id_modulo == 0 )
        {
            return $res = ['eval' => false, 'message' => 'Debe seleccionar el módulo al que pertenece el submodulo.'];
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
        return ['submodulo', 'ruta', 'id_modulo', 'created_at'];
    }
}