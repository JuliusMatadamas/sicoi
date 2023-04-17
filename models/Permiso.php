<?php

namespace app\models;

use app\core\DbModel;
use DateTime;
use PDO;

class Permiso extends DbModel
{
    // ATRIBUTOS
    public int $id_usuario = 0;
    public string $fecha_inicio = '';
    public string $hora_inicio = '';
    public string $fecha_termino = '';
    public string $hora_termino = '';
    public string $motivo = '';
    public int $validacion = 0;
    public string $observaciones = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'permisos';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if ($this->fecha_inicio == '' || $this->fecha_inicio == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió la fecha de inicio.'];
        }

        $fI = explode('-', $this->fecha_inicio);
        if (!checkdate(intval($fI[1]), intval($fI[2]), intval($fI[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha de inicio no es válida.'];
        }

        if ($this->hora_inicio == '' || $this->hora_inicio == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió la hora de inicio.'];
        }

        if ($this->fecha_termino == '' || $this->fecha_termino == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió la fecha de término.'];
        }

        $fT = explode('-', $this->fecha_termino);
        if (!checkdate(intval($fT[1]), intval($fT[2]), intval($fT[0]))) {
            return $res = ['eval' => false, 'message' => 'La fecha de término no es válida.'];
        }

        if ($this->hora_termino == '' || $this->hora_termino == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió la hora de término.'];
        }


        $date1 = new DateTime($this->fecha_inicio . ' ' . $this->hora_inicio . ':00');
        $date2 = new DateTime($this->fecha_termino . ' ' . $this->hora_termino . ':00');

        if ($date1 > $date2) {
            return $res = ['eval' => false, 'message' => 'La fecha de término no puede ser anterior a la fecha de inicio.'];
        }

        if (strlen(trim($this->motivo)) == 0 || $this->motivo == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el motivo del permiso.'];
        }

        if (strlen(trim($this->motivo)) < 20) {
            return $res = ['eval' => false, 'message' => 'El texto del motivo del permiso no cumple con la longitud mínima de 20 caracteres.'];
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
        $sql = "UPDATE permisos SET deleted_at = NOW() WHERE id = " . $id;
        return $this->customUpdate($sql);
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributes(): array
    {
        return ['id_usuario', 'fecha_inicio', 'hora_inicio', 'fecha_termino', 'hora_termino', 'motivo', 'validacion', 'observaciones', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['fecha_inicio', 'hora_inicio', 'fecha_termino', 'hora_termino', 'motivo', 'validacion', 'observaciones'];
    }

    public function getPermisos()
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

    public function toValidate()
    {
        $tablename = $this->tableName();
        $idUsuario = $this->id_usuario;
        $sql = "SELECT permisos.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'empleado', permisos.fecha_inicio, permisos.hora_inicio, permisos.fecha_termino, permisos.hora_termino, permisos.motivo FROM permisos INNER JOIN usuarios ON permisos.id_usuario = usuarios.id INNER JOIN empleados ON usuarios.id = empleados.id WHERE permisos.validacion = 0 AND permisos.deleted_at IS NULL";
        try {
            $statement = self::prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return $e->errorInfo[2];
        }
    }
}