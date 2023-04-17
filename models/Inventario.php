<?php

namespace app\models;

use app\core\DbModel;
use app\helpers\Functions;

class Inventario extends DbModel
{
    // PROPIEDADES
    public string $id_usuario = '0';
    public string $id_inventario_origen = '';
    public string $id_estado_de_inventario_origen = '0';
    public string $fecha_origen = '';
    public string $id_inventario_destino = '';
    public string $id_estado_de_inventario_destino = '0';
    public $fecha_destino = NULL;
    public string $id_producto = '0';
    public string $numero_de_serie = '';
    public string $cantidad = '0';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'inventario';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (empty($this->id_usuario)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del usuario'];
        }

        if (empty($this->id_inventario_origen)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del inventario de origen'];
        }

        if (empty($this->id_estado_de_inventario_origen)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del estado del inventario de origen'];
        }

        if (!Functions::isValidDate($this->fecha_origen)) {
            return $res = ['eval' => false, 'message' => 'La fecha de envío no es válida.'];
        }

        if (!Functions::validarFechaAnterior($this->fecha_origen)) {
            return $res = ['eval' => false, 'message' => 'La fecha de origen no debe ser igual o posterior a la fecha actual.'];
        }

        if (empty($this->id_inventario_destino)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del inventario destino'];
        }

        if (empty($this->id_estado_de_inventario_destino)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del estado de inventario destino'];
        }

        if (!Functions::isValidDate($this->fecha_destino)) {
            return $res = ['eval' => false, 'message' => 'La fecha de recepción no es válida.'];
        }

        if (!Functions::validarFechaAnterior($this->fecha_origen)) {
            return $res = ['eval' => false, 'message' => 'La fecha de origen no debe ser igual o posterior a la fecha actual.'];
        }

        if (!Functions::validarFechaNoAnterior($this->fecha_destino, $this->fecha_origen)) {
            return $res = ['eval' => false, 'message' => 'La fecha de recepción no debe ser igual o anterior a la fecha de envío'];
        }

        if (empty($this->id_producto)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del producto'];
        }

        if (empty($this->numero_de_serie)) {
            return $res = ['eval' => false, 'message' => 'No se recibió el número de serie'];
        }

        if (empty($this->cantidad)) {
            return $res = ['eval' => false, 'message' => 'No se recibió la cantidad de productos a recibir'];
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
        return ['id_usuario', 'id_inventario_origen', 'id_estado_de_inventario_origen', 'fecha_origen', 'id_inventario_destino', 'id_estado_de_inventario_destino', 'fecha_destino', 'id_producto', 'numero_de_serie', 'cantidad', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['id_usuario', 'id_inventario_origen', 'id_estado_de_inventario_origen', 'fecha_origen', 'id_inventario_destino', 'id_estado_de_inventario_destino', 'fecha_destino', 'id_producto', 'numero_de_serie', 'cantidad'];
    }
}