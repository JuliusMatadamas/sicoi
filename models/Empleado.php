<?php

namespace app\models;

use app\core\DbModel;
use DateTime;

class Empleado extends DbModel
{
    // PROPIEDADES
    public string $nombre = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public string $fecha_nacimiento = '';
    public int $id_genero = 0;
    public string $seguro_social = '';
    public string $rfc = '';
    public string $email = '';
    public string $telefono_casa = '';
    public string $telefono_celular = '';
    public string $calle = '';
    public string $numero_exterior = '';
    public string $numero_interior = '';
    public int $id_colonia = 0;
    public string $fecha_inicio = '';
    public $fecha_baja = NULL;
    public int $id_puesto = 0;
    public string $profile_img = '';
    public string $old_profile_img = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD

    public function validate()
    {
        if (strlen(trim($this->nombre)) < 2) {
            return $res = ['eval' => false, 'message' => 'El nombre debe tener al menos 2 letras.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->nombre)) {
            return $res = ['eval' => false, 'message' => 'El nombre debe tener solo letras y espacios.'];
        }

        if (strlen(trim($this->apellido_paterno)) < 2) {
            return $res = ['eval' => false, 'message' => 'El apellido paterno debe tener al menos 2 letras.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->apellido_paterno)) {
            return $res = ['eval' => false, 'message' => 'El apellido paterno debe tener solo letras y espacios.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->apellido_materno)) {
            return $res = ['eval' => false, 'message' => 'El apellido materno debe tener solo letras y espacios.'];
        }

        try {
            $cY = date("Y");
            $bY = date("Y", strtotime($this->fecha_nacimiento));
            $diff = $cY - $bY;

            if ($diff < 18) {
                return $res = ['eval' => false, 'message' => 'La persona debe tener al menos 18 años.'];
            }

            if ($diff >= 65) {
                return $res = ['eval' => false, 'message' => 'La persona tiene 65 o más años'];
            }
        } catch (\Exception $e) {
            return $res = ['eval' => false, 'message' => 'No se pudo evaluar la fecha de nacimiento.'];
        }

        if ($this->id_genero == 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar el género de la persona.'];
        }

        if (strlen(trim($this->seguro_social)) !== 11) {
            return $res = ['eval' => false, 'message' => 'El número de sguro social debe tener 11 dígitos'];
        }

        if (!preg_match("#^[0-9]+$#", $this->seguro_social)) {
            return $res = ['eval' => false, 'message' => 'El número de seguro social debe tener solo números.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]+$#", $this->rfc)) {
            return $res = ['eval' => false, 'message' => 'El RFC debe tener solo letras y números.'];
        }

        if (strlen(trim($this->rfc)) !== 13) {
            return $res = ['eval' => false, 'message' => 'El RFC debe tener 13 carácteres.'];
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return $res = ['eval' => false, 'message' => 'El formato de la dirección de correo electrónico no es válida.'];
        }

        if (strlen(trim($this->telefono_casa)) !== 10) {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener 10 dígitos.'];
        }

        if (!preg_match("#^[0-9]+$#", $this->telefono_casa)) {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener solo números.'];
        }

        if (strlen(trim($this->telefono_celular)) !== 10) {
            return $res = ['eval' => false, 'message' => 'El teléfono celular debe tener 10 dígitos.'];
        }

        if (!preg_match("#^[0-9]+$#", $this->telefono_celular)) {
            return $res = ['eval' => false, 'message' => 'El teléfono celular debe tener solo números.'];
        }

        if (strlen(trim($this->calle)) < 1) {
            return $res = ['eval' => false, 'message' => 'La calle debe tener al menos un caracter.'];
        }

        if (strlen(trim($this->numero_exterior)) != 0) {
            if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]+$#", $this->numero_exterior)) {
                return $res = ['eval' => false, 'message' => 'El número exterior debe tener solo números con/sin letras.'];
            }
        }

        if (strlen(trim($this->numero_interior)) != 0) {
            if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]+$#", $this->numero_interior)) {
                return $res = ['eval' => false, 'message' => 'El número interior debe tener solo números con/sin letras.'];
            }
        }

        if ($this->id_colonia == 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar la colonia'];
        }

        if (strlen(trim($this->fecha_inicio)) == 0) {
            return $res = ['eval' => false, 'message' => 'Se debe ingresar la fecha de inicio.'];
        }

        $d = DateTime::createFromFormat('Y-m-d', $this->fecha_inicio);
        $els = explode("-", $this->fecha_inicio);

        if (intval($els[1]) !== intval($d->format('m'))) {
            return $res = ['eval' => false, 'message' => 'La fecha de inicio no es válida.'];
        }

        if (strlen(trim($this->fecha_baja)) !== 0) {
            $d = DateTime::createFromFormat('Y-m-d', $this->fecha_baja);
            $els = explode("-", $this->fecha_baja);

            if (intval($els[1]) !== intval($d->format('m'))) {
                return $res = ['eval' => false, 'message' => 'La fecha de baja no es válida.'];
            } else {
                $d1 = new DateTime($this->fecha_inicio);
                $d2 = new DateTime($this->fecha_baja);
                $interval = $d1->diff($d2);
                if (floatval($interval->format('%R%a')) < 0) {
                    return $res = ['eval' => false, 'message' => 'La fecha de baja debe ser igual o posterior a la fecha de inicio.'];
                }
            }
        }

        if ($this->id_puesto == 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar el puesto'];
        }

        return $res = ['eval' => true];
    }

    // VALIDACIÓN DE LOS DATOS

    public function validateDatos()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return $res = ['eval' => false, 'message' => 'El formato de la dirección de correo electrónico no es válida.'];
        }

        if (strlen(trim($this->telefono_casa)) !== 10) {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener 10 dígitos.'];
        }

        if (!preg_match("#^[0-9]+$#", $this->telefono_casa)) {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener solo números.'];
        }

        if (strlen(trim($this->telefono_celular)) !== 10) {
            return $res = ['eval' => false, 'message' => 'El teléfono celular debe tener 10 dígitos.'];
        }

        if (!preg_match("#^[0-9]+$#", $this->telefono_celular)) {
            return $res = ['eval' => false, 'message' => 'El teléfono celular debe tener solo números.'];
        }

        if (strlen(trim($this->calle)) < 1) {
            return $res = ['eval' => false, 'message' => 'La calle debe tener al menos un caracter.'];
        }

        if (strlen(trim($this->numero_exterior)) != 0) {
            if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]+$#", $this->numero_exterior)) {
                return $res = ['eval' => false, 'message' => 'El número exterior debe tener solo números con/sin letras.'];
            }
        }

        if (strlen(trim($this->numero_interior)) != 0) {
            if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]+$#", $this->numero_interior)) {
                return $res = ['eval' => false, 'message' => 'El número interior debe tener solo números con/sin letras.'];
            }
        }

        if ($this->id_colonia == 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar la colonia'];
        }
        return $res = ['eval' => true];
    }

    public function create()
    {
        $date = date('Y-m-d');
        $this->created_at = $date;
        return $this->save();
    }

    // CREACIÓN DE UN NUEVO REGISTRO EN LA BD

    public function updateDatos($id)
    {
        $sql = "UPDATE " . $this->tableName() . " SET id_colonia = '" . $this->id_colonia . "', calle = '" . $this->calle . "', numero_exterior = '" . $this->numero_exterior . "', numero_interior = '" . $this->numero_interior . "', email = '" . $this->email . "', telefono_casa = '" . $this->telefono_casa . "', telefono_celular = '" . $this->telefono_celular . "' WHERE id = " . $id;
        return $this->customUpdate($sql);
    }

    // ACTUALIZACIÓN DE LOS DATOS POR EL USUARIO

    public function tableName(): string
    {
        return 'empleados';
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD

    public function attributes(): array
    {
        return ['nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 'id_genero', 'seguro_social', 'rfc', 'email', 'telefono_casa', 'telefono_celular', 'calle', 'numero_exterior', 'numero_interior', 'id_colonia', 'fecha_inicio', 'id_puesto', 'profile_img', 'created_at'];
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 'id_genero', 'seguro_social', 'rfc', 'email', 'telefono_casa', 'telefono_celular', 'calle', 'numero_exterior', 'numero_interior', 'id_colonia', 'fecha_inicio', 'fecha_baja', 'id_puesto', 'profile_img'];
    }
}