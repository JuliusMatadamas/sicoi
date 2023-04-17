<?php

namespace app\models;

use app\core\DbModel;
use PDO;

class Vacacion extends DbModel
{
    // ATRIBUTOS
    public int $id_usuario = 0;
    public int $age = 0;
    public string $fecha = '';
    public int $validacion = 0;
    public int $estado = 0;
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'vacaciones';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if ($this->fecha == '' || $this->fecha == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió la fecha a tomar de vacaciones.'];
        }

        $fI = explode('-', $this->fecha);
        if (!checkdate(intval($fI[1]), intval($fI[2]), intval($fI[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha recibida no es válida.'];
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
        $sql = "UPDATE vacaciones SET deleted_at = NOW() WHERE id = " . $id;
        return $this->customUpdate($sql);
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['id_usuario', 'age', 'fecha', 'validacion', 'estado', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['fecha', 'validacion', 'estado'];
    }

    public function getVacaciones()
    {
        $tablename = $this->tableName();
        $idUsuario = $this->id_usuario;
        $sql = "SELECT * FROM $tablename WHERE $tablename.id_usuario = $idUsuario AND $tablename.deleted_at IS NULL";
        try {
            $statement = self::prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return $e->errorInfo[2];
        }
    }

    public function getToValidate()
    {
        $sql = "SELECT vacaciones.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', vacaciones.fecha FROM vacaciones INNER JOIN usuarios ON vacaciones.id_usuario = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE vacaciones.deleted_at IS NULL AND vacaciones.validacion = 0 AND vacaciones.estado = 0";
        try {
            $statement = self::prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return $e->errorInfo[2];
        }
    }
}