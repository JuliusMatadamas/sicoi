<?php

namespace app\models;

use app\core\DbModel;

class Venta extends DbModel
{
    // ATRIBUTOS
    public int $id_usuario_atendio = 0;
    public string $id_cliente = '0';
    public int $id_tipo_de_venta = 0;
    public string $fecha_programada = '';
    public string $fecha_visita = '';
    public int $id_estado_de_visita = 1;
    public string $hora_visita = '';
    public string $latitud = '';
    public string $longitud = '';
    public string $observaciones = '';
    public int $id_tecnico_asignado = 0;
    public int $id_tecnico_visito = 0;
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'ventas';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (strlen(trim($this->id_cliente)) === 0 || $this->id_cliente == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id del cliente.'];
        }

        if (trim($this->id_tipo_de_venta) === 0) {
            return $res = ['eval' => false, 'message' => 'No se recibió el id de venta realizada.'];
        }

        try {
            $fechaActual = date('Y-m-d');

            if (((((strtotime($this->fecha_programada) - strtotime($fechaActual)) / 24) / 60) / 60) < 0) {
                return $res = ['eval' => false, 'message' => 'La fecha programada no debe ser anterior a la fecha actual.'];
            }
        } catch (\Exception $e) {
            return $res = ['eval' => false, 'message' => 'No se pudo evaluar la fecha programada de visita.'];
        }

        if (strlen(trim($this->observaciones)) < 10) {
            return $res = ['eval' => false, 'message' => 'Las observaciones deben tener al menos 10 caracteres.'];
        }

        if (strlen(trim($this->observaciones)) > 255) {
            return $res = ['eval' => false, 'message' => 'Las observaciones no deben tener más de 255 caracteres.'];
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
        return ['id_usuario_atendio', 'id_cliente', 'id_tipo_de_venta', 'fecha_programada', 'id_estado_de_visita', 'hora_visita', 'latitud', 'longitud', 'observaciones', 'id_tecnico_asignado', 'id_tecnico_visito', 'created_at'];
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['id_tipo_de_venta', 'fecha_programada', 'fecha_visita', 'id_estado_de_visita', 'hora_visita', 'latitud', 'longitud', 'observaciones', 'id_tecnico_asignado', 'id_tecnico_visito'];
    }
}