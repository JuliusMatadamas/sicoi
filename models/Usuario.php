<?php

namespace app\models;

use app\core\DbModel;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class Usuario extends DbModel
{
    // PROPIEDADES
    public int $id = 0;
    public string $usuario = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public string $modulos = '';
    public string $token = '';
    public $expiration_token = NULL;
    public string $csrf = '';
    public string $jwt = '';
    public $expiration_jwt = NULL;
    public string $created_at = '';

    // NOMBRE DE LA TABLA EN LA BD

    public static function validateToken($token)
    {
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($cu, CURLOPT_POST, 1);
        curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CLAVE_SECRETA, 'response' => $token)));
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($cu);
        curl_close($cu);
        $respuesta = json_decode($response, true);
        return $respuesta["success"];
    }

    // VALIDACIÓN DE LOS DATOS

    public function tableName(): string
    {
        return 'usuarios';
    }

    // CREACIÓN DE UN NUEVO REGISTRO EN LA BD

    public function validate()
    {
        if ($this->id === 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar el empleado.'];
        }

        if (strlen(trim($this->usuario)) < 7 || strlen(trim($this->usuario)) > 10) {
            return $res = ['eval' => false, 'message' => 'El usuario debe tener entre 7 y 10 carácteres.'];
        }

        if (strlen(trim($this->password)) < 7 || strlen(trim($this->password)) > 10) {
            return $res = ['eval' => false, 'message' => 'La contraseña debe tener entre 7 y 10 carácteres.'];
        }

        if (strlen(trim($this->passwordConfirm)) < 7 || strlen(trim($this->passwordConfirm)) > 10) {
            return $res = ['eval' => false, 'message' => 'La contraseñas deben coincidir.'];
        }

        if ($this->password !== $this->passwordConfirm) {
            return $res = ['eval' => false, 'message' => 'La contraseñas deben coincidir.'];
        }

        if (strlen(trim($this->modulos)) == 0) {
            return $res = ['eval' => false, 'message' => 'Se debe seleccionar al menos un módulo.'];
        }

        return $res = ['eval' => true, 'message' => 'Los datos son válidos'];
    }

    // ATRIBUTOS A INSERTAR EN LA TABLA DE LA BD

    public function create()
    {
        $date = date('Y-m-d');
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->token = '';
        $this->csrf = '';
        $this->jwt = '';
        $this->created_at = $date;
        return $this->save();
    }

    // ATRIBUTOS A ACTUALIZAR EN LA TABLA DE LA BD

    public function attributes(): array
    {
        return ['id', 'usuario', 'password', 'modulos', 'token', 'expiration_token', 'csrf', 'jwt', 'expiration_jwt', 'created_at'];
    }

    public function attributesToUpdate(): array
    {
        return ['usuario', 'password', 'modulos', 'token', 'expiration_token', 'csrf', 'jwt', 'expiration_jwt'];
    }

    public function login()
    {
        $result = $this->customQuery("SELECT usuarios.id, usuarios.password, usuarios.modulos, usuarios.expiration_jwt, empleados.nombre, empleados.apellido_paterno, empleados.apellido_materno, empleados.fecha_nacimiento, empleados.seguro_social, empleados.rfc, empleados.email, empleados.fecha_inicio, empleados.profile_img, puestos.puesto FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id INNER JOIN puestos ON empleados.id_puesto = puestos.id WHERE (empleados.fecha_baja IS NULL OR empleados.fecha_baja = '' OR empleados.fecha_baja > CURRENT_DATE) AND (usuarios.usuario = '" . $this->usuario . "')");

        if (count($result) === 0) {
            return ["eval" => false, "message" => "Estas credenciales no coinciden con nuestros registros."];
        } else {
            // CICLO FOR EN CASO DE OBTENERSE MÁS DE UN RESULTADO
            for ($i = 0; $i < count($result); $i++) {

                // SI EL PASSWORD COINCIDE CON EL REGISTRADO EN LA BD
                if (password_verify($this->password, $result[$i]["password"])) {

                    // SE VERIFICA SI NO EXISTE UN JWT
                    if ($result[$i]["expiration_jwt"] === NULL) {
                        // SE ELIMINA EL PASSWORD
                        unset($result[$i]["password"]);

                        // SE OBTIENEN LOS SUBMODULOS
                        $result_submodulos = $this->customQuery("SELECT modulos.id AS 'id_modulo', modulos.modulo, modulos.icono, submodulos.id AS 'id_submodulo', submodulos.submodulo, submodulos.ruta FROM modulos INNER JOIN submodulos ON modulos.id = submodulos.id_modulo WHERE modulos.id IN (" . $result[$i]["modulos"] . ")");

                        $result[$i]["modulos"] = $result_submodulos;

                        // SE REGISTRA EL TIEMPO
                        $time = time();

                        /**
                         * SE CREA EL TOKEN PARA GENERAR EL JWT
                         * iat : SE DEFINE LA FECHA DE CREACIÓN DEL JWT
                         * exp : SE DEFINE LA CADUCIDAD DEL JWT (30 mins)
                         * data : SE LE PASA EL ID DEL USUARIO Y SU EMAIL
                         */
                        $token = array("iat" => $time, "exp" => $time + (60 * 30), "data" => ["id" => $result[$i]["id"], "email" => $result[$i]["email"]]);

                        // SE CREA LA KEY DEL JWT
                        $key = hash("sha256", rand());

                        // SE GENERA EL JWT
                        $jwt = JWT::encode($token, $key, 'HS256');
                        // SE GUARDA EL JWT EN LAS COOKIES
                        setcookie("jwt", $jwt, $time + (60 * 30));

                        // SE CREA EL CSRF
                        $result[$i]["csrf"] = bin2hex(openssl_random_pseudo_bytes(32));

                        // SE REGISTRA EL INICIO DE SESIÓN
                        $query = "UPDATE usuarios SET token = '', usuarios.expiration_token = NULL, usuarios.csrf = '" . $result[$i]["csrf"] . "', usuarios.jwt = '" . $jwt . "', usuarios.expiration_jwt = '" . date('Y-m-d H:i:s', $time + (60 * 30)) . "' WHERE usuarios.id = " . intval($result[$i]["id"]) . " AND usuarios.deleted_at IS NULL";

                        // DEPENDIENDO DE SI SE PUDO REGISTRAR EL INICIO DE SESIÓN
                        if ($this->customUpdate($query) !== 0) {
                            unset($result[$i]["expiration_jwt"]);
                            $_SESSION["usuario"] = $result[$i];
                            return ["eval" => true, "message" => "Se ha iniciado sesión en la aplicación."];
                        } else {
                            return ["eval" => false, "message" => "No se pudo iniciar sesión, ocurrió un error en el servidor."];
                        }
                    } else {
                        if (strtotime(date('Y-m-d H:i:s')) - strtotime($result[$i]["expiration_jwt"]) < 0) {
                            return ["eval" => false, "message" => "No se pudo iniciar sesión porque ya hay una iniciada y vigente."];
                        } else {
                            // SE ELIMINA EL PASSWORD
                            unset($result[$i]["password"]);

                            // SE OBTIENEN LOS SUBMODULOS
                            $result_submodulos = $this->customQuery("SELECT modulos.id AS 'id_modulo', modulos.modulo, modulos.icono, submodulos.id AS 'id_submodulo', submodulos.submodulo, submodulos.ruta FROM modulos INNER JOIN submodulos ON modulos.id = submodulos.id_modulo WHERE modulos.id IN (" . $result[$i]["modulos"] . ")");

                            $result[$i]["modulos"] = $result_submodulos;

                            // SE REGISTRA EL TIEMPO
                            $time = time();

                            /**
                             * SE CREA EL TOKEN PARA GENERAR EL JWT
                             * iat : SE DEFINE LA FECHA DE CREACIÓN DEL JWT
                             * exp : SE DEFINE LA CADUCIDAD DEL JWT (12 Hrs)
                             * data : SE LE PASA EL ID DEL USUARIO Y SU EMAIL
                             */
                            $token = array("iat" => $time, "exp" => $time + (60 * 30), "data" => ["id" => $result[$i]["id"], "email" => $result[$i]["email"]]);

                            // SE CREA LA KEY DEL JWT
                            $key = hash("sha256", rand());

                            // SE GENERA EL JWT
                            $jwt = JWT::encode($token, $key, 'HS256');
                            // SE GUARDA EL JWT EN LAS COOKIES
                            setcookie("jwt", $jwt, $time + (60 * 30));

                            // SE CREA EL CSRF
                            $result[$i]["csrf"] = bin2hex(openssl_random_pseudo_bytes(32));

                            // SE REGISTRA EL INICIO DE SESIÓN
                            $query = "UPDATE usuarios SET token = '', usuarios.expiration_token = NULL, usuarios.csrf = '" . $result[$i]["csrf"] . "', usuarios.jwt = '" . $jwt . "', usuarios.expiration_jwt = '" . date('Y-m-d H:i:s', $time + (60 * 30)) . "' WHERE usuarios.id = " . intval($result[$i]["id"]) . " AND usuarios.deleted_at IS NULL";

                            // DEPENDIENDO DE SI SE PUDO REGISTRAR EL INICIO DE SESIÓN
                            if ($this->customUpdate($query) !== 0) {
                                unset($result[$i]["expiration_jwt"]);
                                $_SESSION["usuario"] = $result[$i];
                                return ["eval" => true, "message" => "Se ha iniciado sesión en la aplicación."];
                            } else {
                                return ["eval" => false, "message" => "No se pudo iniciar sesión, ocurrió un error en el servidor."];
                            }
                        }
                    }
                }
            }
            return ["eval" => false, "message" => "Estas credenciales no coinciden con nuestros registros."];
        }
    }

    public function existEmail($e)
    {
        return $this->customQuery("SELECT usuarios.id FROM usuarios INNER JOIN empleados ON usuarios.id = empleados.id WHERE empleados.email = '$e' AND (empleados.fecha_baja IS NULL OR empleados.fecha_baja > CURRENT_DATE)");
    }

    public function sendMailReset($email, $id)
    {
        // SE CREA EL TOKEN
        $token = bin2hex(openssl_random_pseudo_bytes(32));

        // SE CREA LA FECHA DE EXPIRACIÓN DEL TOKEN
        $expiration = date('Y-m-d H:i:s', time() + (60 * 15)); // 15 minutos de duración del token

        // SE CREA EL QUERY DE ACTUALIZACIÓN
        $sql = "UPDATE usuarios SET usuarios.token = '$token', usuarios.expiration_token = '$expiration' WHERE usuarios.id = " . $id;

        // SE ACTUALIZA LA BD
        if ($this->customUpdate($sql)) {
            // SE REALIZA EL PROCESO PARA MANDAR EL EMAIL
            $to = $email;
            $subject = "Solicitud de reseteo de contraseña en SICOI";
            $message = '
<!doctype html>
<html lang="' . LANG . '">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>' . NAME_APP . '</title>
    <style>
        .btn {
            background: #212529;
            border: solid 1px #ccc;
            border-radius: 10px;
            color: #f5f5f5 !important;
            padding: 15px 20px;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1 class="text-center">SICOI</h1>
    
    <p>Se ha solicitado el reset de la contraseña asociada a tu cuenta de correo electrónico, si tú haz realizado esta acción, da clic en el enlace para generar una nueva contraseña en el sistema SICOI, o, copia y pegalo en tu navegador.</p>
    
    <p>En caso de que no hayas sido tú quien solicitó el reset de la contraseña, haz caso omiso de este correo e informa a la administración de esto.</p>
    
    <p>Enlace:</p>
    
    <p>' . ENTORNO . '/nueva_clave/?token=' . $token . '&id=' . $id . '</p>
    
    <p>NOTA</p>
    <p>Este enlace caducará a las ' . date('H:i:s', time() + (60 * 15)) . 'hrs.</p>
    <br>
</body>
</html>
';
            $headers = "MIME-Version: 1.0" . PHP_EOL;
            $headers .= "Content-type:text/html;charset=UTF-8" . PHP_EOL;
            $headers .= 'From: SICOI <admin@sicoi.com>' . PHP_EOL;
            return mail($to, $subject, $message, $headers);
        } else {
            return false;
        }
    }

    public function evalToken($data)
    {
        $now = date('Y-m-d H:i:s', time());
        $sql = "SELECT usuarios.id FROM usuarios WHERE usuarios.id = " . $data["id"] . "  AND usuarios.token = '" . $data["token"] . "' AND usuarios.expiration_token > '$now'";
        return $this->customQuery($sql);
    }

    public function updatePass($data)
    {
        if ($data["password"] !== $data["password_confirm"]) {
            return false;
        } else {
            $password = password_hash($data["password"], PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET usuarios.password = '$password', usuarios.token = '', usuarios.expiration_token = NULL WHERE usuarios.id = " . $data["id"];
            return $this->customUpdate($sql);
        }
    }
}