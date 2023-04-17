<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\CodigoPostal;
use app\models\Empleado;
use app\models\Genero;
use app\models\Puesto;
use DateTime;


class EmpleadoController extends Controller
{
    public function __construct()
    {
        // SE VALIDA SI EL USUARIO HA INICIADO SESIÓJN
        if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"])) {
            Response::redirect('/log_out');
        } else {
            // SE VALIDA SI EL USUARIO TIENE AUTORIZADO EL MODULO
            $output = [];
            for ($i = 0; $i < count($_SESSION["usuario"]["modulos"]); $i++) {
                $m = strtolower($_SESSION["usuario"]["modulos"][$i]["modulo"]);
                $m = str_replace("á", "a", $m);
                $m = str_replace("é", "e", $m);
                $m = str_replace("í", "i", $m);
                $m = str_replace("ó", "o", $m);
                $m = str_replace("ú", "u", $m);
                $output[$i]["modulo"] = $m;
            }
            $modulosFiltrados =  array_map("unserialize", array_unique(array_map("serialize", $output)));

            $location = str_replace("uri=", "", explode("/",$_SERVER["QUERY_STRING"])[0]);

            $cont = 0;
            $modulos = [];
            foreach ($modulosFiltrados as $key => $value) {
                $modulos[$cont] = $value["modulo"];
                $cont++;
            }

            if (in_array($location, $modulos) === false) {
                Response::redirect('/not_authorized');
            }
        }
    }

    public function index()
    {
        $empleados = new Empleado();
        $data[0] = count($empleados->getAll());
        $data[1] = $empleados->customQuery("SELECT empleados.id, CONCAT(empleados.nombre,' ',empleados.apellido_paterno,' ',empleados.apellido_materno) AS 'empleado', empleados.email, empleados.telefono_casa, empleados.telefono_celular, empleados.seguro_social, empleados.rfc, puestos.puesto, empleados.profile_img, empleados.fecha_inicio, empleados.fecha_baja FROM empleados INNER JOIN puestos ON empleados.id_puesto = puestos.id LIMIT 10");
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/jstable.css">
        <link rel="stylesheet" href="'. ENTORNO .'/public/css/empleados.css">';
        $js = '<script src="'. ENTORNO .'/public/js/jstable.min.js"></script>
                <script src="'. ENTORNO .'/public/js/empleados/create.js"></script>';

        return $this->render('empleados/index', $css, $js, $data);
    }

    public function editar(Request $request)
    {
        $data['current_date'] = date('Y-m-d');

        $generos = new Genero();
        $data['generos'] = $generos->getAll();

        $puestos = new Puesto();
        $data['puestos'] = $puestos->getAll();

        $empleado = new Empleado();
        $data['empleado'] = $empleado->findById($request->getBody()["id"]);

        $data['cp'] = $empleado->customQuery("SELECT codigos_postales.codigo_postal FROM empleados INNER JOIN colonias ON empleados.id_colonia = colonias.id INNER JOIN codigos_postales ON colonias.id_codigo_postal = codigos_postales.id WHERE empleados.id_colonia = ".$data['empleado'][0]['id_colonia']);

        $data['colonias'] = $empleado->customQuery("SELECT colonias.id, colonias.colonia FROM colonias INNER JOIN codigos_postales ON  colonias.id_codigo_postal = codigos_postales.id WHERE codigos_postales.codigo_postal = ".$data['cp'][0]['codigo_postal']);

        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/empleados.css">';
        $js = '<script src="'. ENTORNO .'/public/js/empleados/edit.js"></script>';

        return $this->render('empleados/editar', $css, $js, $data);
    }

    public function nuevo()
    {
        $data['current_date'] = date('Y-m-d');

        $generos = new Genero();
        $data['generos'] = $generos->getAll();

        $puestos = new Puesto();
        $data['puestos'] = $puestos->getAll();

        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/empleados.css">';
        $js = '<script src="'. ENTORNO .'/public/js/empleados/create.js"></script>';

        return $this->render('empleados/nuevo', $css, $js, $data);
    }

    public function save(Request $request)
    {
        $data = $request->getBody();

        // SE VALIDA EL RECAPTCHA
        $token = $data["token"];
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($cu, CURLOPT_POST, 1);
        curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => CLAVE_SECRETA, 'response' => $token)));
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($cu);
        curl_close($cu);
        $respuesta = json_decode($response, true);
        if($respuesta["success"] === false)
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => 'No se pasó la validación del recaptcha.']);
            exit;
        }

        // SE VALIDA EL CSRF

        // SI LA ACCIÓN A REALIZAR ES 'CREATE'
        if ($data["action"] == "create")
        {
            // SE CREA UNA NUEVA INSTANCIA DE LA CLASE 'Empleado'
            $empleado = new Empleado();

            // SE CARGAN LOS DATOS RECIBIDOS EN LOS ATRIBUTOS DE LA CLASE
            $empleado->loadData($data);

            // SE GUARDA LA IMAGEN DEL PERFIL DEL EMPLEADO
            if ($empleado->profile_img !== "")
            {
                try {
                    $image_parts = explode(";base64,", $empleado->profile_img);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $file = RUTA_IMG_PROFILES . uniqid() . '.png';
                    file_put_contents($file, $image_base64);
                    $pos = strpos($file, '/public/');
                    $ruta = substr($file, $pos);
                    $empleado->profile_img = $ruta;
                } catch (Exception $e) {
                    $empleado->profile_img = '';
                }
            }

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $empleado->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"])
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            $result = $empleado->create();

            // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "string")
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $result]);
                exit;
            }

            // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "boolean")
            {
                if (!$result)
                {
                    $statusCode = new Response();
                    $statusCode->setStatusCode(400);
                    echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                    exit;
                }
            }

            // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
            echo json_encode(['response' => true, 'message' => 'El empleado fue registrado exitosamente.']);
            exit;
        }

        // SI LA ACCIÓN A REALIZAR ES 'EDIT'
        if ($data["action"] == "edit")
        {
            $empleado = new Empleado();
            $empleado->loadData($data);

            // SE VALIDA LA INFORMACIÓN RECIBIDA
            $validacion = $empleado->validate();

            // SI LA INFORMACIÓN NO ES VÁLIDA
            if (!$validacion["eval"])
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $validacion["message"]]);
                exit;
            }

            // SE GUARDA LA IMAGEN DEL PERFIL DEL EMPLEADO
            if ($empleado->profile_img !== "")
            {
                // SI HABÍA ANTERIORMENTE UNA IMAGEN GUARDADA EN EL SERVIDOR, SE ELIMINA ENTONCES
                if ($empleado->old_profile_img !== '')
                {
                    try {
                        $parts = explode("/", $empleado->old_profile_img);
                        $last = count($parts) - 1;
                        $routeFileToDelete = RUTA_IMG_PROFILES . $parts[$last];
                        if (file_exists($routeFileToDelete))
                        {
                            unlink($routeFileToDelete);
                        }
                    } catch (\Exception $e) {
                        $empleado->old_profile_img = '';
                    }
                }
                try {
                    $image_parts = explode(";base64,", $empleado->profile_img);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $file = RUTA_IMG_PROFILES . uniqid() . '.png';
                    file_put_contents($file, $image_base64);
                    $pos = strpos($file, '/public/');
                    $ruta = substr($file, $pos);
                    $empleado->profile_img = $ruta;
                } catch (Exception $e) {
                    $empleado->profile_img = '';
                }
            }

            // SI LA INFORMACIÓN ES VÁLIDA, SE GUARDA EN LA BD
            if (gettype($empleado->fecha_baja) == "string" && $empleado->fecha_baja == "") {
                $empleado->fecha_baja = NULL;
            }
            $result = $empleado->update($data["id_empleado"]);

            // SI OCURRIÓ UN ERROR AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "string")
            {
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                echo json_encode(['response' => false, 'message' => $result]);
                exit;
            }

            // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR GUARDAR LA INFORMACIÓN EN LA BD
            if (gettype($result) == "boolean")
            {
                if (!$result)
                {
                    $statusCode = new Response();
                    $statusCode->setStatusCode(400);
                    echo json_encode(['response' => false, 'message' => 'Ocurrió un error al realizar la petición al servidor.']);
                    exit;
                }
            }

            // SI LA INFORMACIÓN FUE GUARDADA CORRECTAMENTE EN LA BD
            echo json_encode(['response' => true, 'message' => 'La información del empleado fue actualizada exitosamente.']);
            exit;
        }
    }
}