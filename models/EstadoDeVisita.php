<?php

namespace app\models;

use app\core\DbModel;
use PDO;

class EstadoDeVisita extends DbModel
{
    // ATRIBUTOS
    public string $estado_de_visita = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'estados_de_visitas';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (strlen(trim($this->estado_de_visita)) == 0 || $this->estado_de_visita == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el estado de la visita a registrar.'];
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

    // BORRADO LÓGICO DE UN REGISTRO EN LA BD
    public function delete($id)
    {
        $date = date('Y-m-d');
        $this->created_at = $date;
        $sql = "UPDATE ". $this->tableName() ." SET deleted_at = '". $this->created_at ."' WHERE id = " . $id;
        return $this->customUpdate($sql);
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['estado_de_visita', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['estado_de_visita'];
    }

    public function getEstadosDeVisistas()
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