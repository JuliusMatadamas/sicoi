<?php

namespace app\models;

use app\core\DbModel;
use PDO;

class TipoDeVenta extends DbModel
{
    // ATRIBUTOS
    public int $id = 0;
    public string $tipo = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'tipos_de_ventas';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (strlen(trim($this->tipo)) == 0 || $this->tipo == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el tipo de venta.'];
        }

        if (strlen(trim($this->tipo)) < 10) {
            return $res = ['eval' => false, 'message' => 'El texto del tipo de venta no cumple con la longitud mínima de 10 caracteres.'];
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

    // ACTUALIZACIÓN DE UN REGISTRO EN LA BD
    public function update($id)
    {
        return parent::update($id);
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['tipo', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['tipo'];
    }

    public function getTipos()
    {
        $tablename = $this->tableName();
        $sql = "SELECT * FROM $tablename WHERE deleted_at IS NULL";
        try {
            $statement = self::prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return $e->errorInfo[2];
        }
    }
}