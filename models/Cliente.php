<?php

namespace app\models;

use app\core\DbModel;

class Cliente extends DbModel
{
    // ATRIBUTOS
    public string $id = '0';
    public string $nombre = '';
    public string $apellido_paterno = '';
    public string $apellido_materno = '';
    public int $id_genero = 1;
    public string $fecha_nacimiento = 'NULL';
    public string $telefono_casa = '';
    public string $telefono_celular = '';
    public string $email = '';
    public int $id_colonia = 1;
    public string $rfc = '';
    public string $calle = '';
    public string $numero_exterior = '';
    public string $numero_interior = '';
    public string $observaciones = '';
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD
    public function tableName(): string
    {
        return 'clientes';
    }

    // VALIDACIÓN DE LOS DATOS
    public function validate()
    {
        if (strlen(trim($this->nombre)) === 0 || $this->nombre == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el nombre del cliente.'];
        }

        if (strlen(trim($this->nombre)) < 2) {
            return $res = ['eval' => false, 'message' => 'El nombre del cliente debe tener al menos 2 caracteres.'];
        }

        if (strlen(trim($this->nombre)) > 40) {
            return $res = ['eval' => false, 'message' => 'El nombre del cliente no debe tener más de 40 caracteres.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->nombre))
        {
            return $res = ['eval' => false, 'message' => 'El nombre del cliente debe tener solo letras, espacios y/o acentos.'];
        }

        if (strlen(trim($this->apellido_paterno)) === 0 || $this->apellido_paterno == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el apellido paterno del cliente.'];
        }

        if (strlen(trim($this->apellido_paterno)) < 2) {
            return $res = ['eval' => false, 'message' => 'El apellido paterno del cliente debe tener al menos 2 caracteres.'];
        }

        if (strlen(trim($this->apellido_paterno)) > 40) {
            return $res = ['eval' => false, 'message' => 'El apellido paterno del cliente no debe tener más de 40 caracteres.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->apellido_paterno))
        {
            return $res = ['eval' => false, 'message' => 'El apellido paterno del cliente debe tener solo letras, espacios y/o acentos.'];
        }

        if (strlen(trim($this->apellido_materno)) !== 0) {
            if (strlen(trim($this->apellido_materno)) < 2) {
                return $res = ['eval' => false, 'message' => 'El apellido materno del cliente debe tener al menos 2 caracteres.'];
            }

            if (strlen(trim($this->apellido_materno)) > 40) {
                return $res = ['eval' => false, 'message' => 'El apellido materno del cliente no debe tener más de 40 caracteres.'];
            }

            if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ ]+$#", $this->apellido_materno))
            {
                return $res = ['eval' => false, 'message' => 'El apellido materno del cliente debe tener solo letras, espacios y/o acentos.'];
            }
        }

        try {
            $cY = date("Y");
            $bY = date("Y", strtotime($this->fecha_nacimiento));
            $diff = $cY - $bY;

            if ($diff < 18)
            {
                return $res = ['eval' => false, 'message' => 'El cliente debe tener al menos 18 años.'];
            }

            if ($diff >= 65)
            {
                return $res = ['eval' => false, 'message' => 'El cliente no debe tener de 65 años en adelante.'];
            }
        } catch (\Exception $e) {
            return $res = ['eval' => false, 'message' => 'No se pudo evaluar la fecha de nacimiento.'];
        }

        if (intval($this->id_genero) === 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar el género del cliente.'];
        }

        if (!preg_match("#^[0-9]+$#", $this->telefono_casa))
        {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener solo números.'];
        }

        if (strlen($this->telefono_casa) !== 10) {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener 10 dígitos.'];
        }

        if (!preg_match("#^[0-9]+$#", $this->telefono_celular))
        {
            return $res = ['eval' => false, 'message' => 'El teléfono celular debe tener solo números.'];
        }

        if (strlen($this->telefono_celular) !== 10) {
            return $res = ['eval' => false, 'message' => 'El teléfono de casa debe tener 10 dígitos.'];
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return $res = ['eval' => false, 'message' => 'El formato de la dirección de correo electrónico no es válida.'];
        }

        if (intval($this->id_colonia) === 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar la colonia en que reside el cliente.'];
        }

        if (!preg_match("#^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]+$#", $this->rfc))
        {
            return $res = ['eval' => false, 'message' => 'El RFC debe tener solo letras y números.'];
        }

        if (strlen(trim($this->rfc)) !== 13)
        {
            return $res = ['eval' => false, 'message' => 'El RFC debe tener 13 carácteres.'];
        }

        if ( strlen(trim($this->calle)) === 0 || $this->calle == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió la calle del cliente.'];
        }

        if (strlen(trim($this->calle)) < 2) {
            return $res = ['eval' => false, 'message' => 'La calle del cliente debe tener al menos 2 caracteres.'];
        }

        if (strlen(trim($this->calle)) > 45) {
            return $res = ['eval' => false, 'message' => 'La calle del cliente no debe tener más de 45 caracteres.'];
        }

        if ( strlen(trim($this->numero_exterior)) === 0 || $this->numero_exterior == NULL) {
            return $res = ['eval' => false, 'message' => 'No se recibió el número exterior.'];
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
        return ['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 'id_genero', 'telefono_casa', 'telefono_celular', 'email', 'id_colonia', 'rfc', 'calle', 'numero_exterior', 'numero_interior', 'observaciones', 'created_at'];
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD
    public function attributesToUpdate(): array
    {
        return ['nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 'id_genero', 'telefono_casa', 'telefono_celular', 'email', 'id_colonia', 'rfc', 'calle', 'numero_exterior', 'numero_interior', 'observaciones'];
    }
}