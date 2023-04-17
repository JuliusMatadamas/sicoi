<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Venta;

class ApiVentaReporteController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function index(Request $request)
    {
        $params = $request->getBody();

        if (strlen($params["ids_vendedores"]) === 0) {
            $paramVendedores = "";
        } else {
            $paramVendedores = "AND ventas.id_usuario_atendio IN (" . $params["ids_vendedores"] . ") ";
        }

        if (strlen($params["ids_tipos_de_ventas"]) === 0) {
            $paramTiposDeVentas = "";
        } else {
            $paramTiposDeVentas = "AND ventas.id_tipo_de_venta IN (" . $params["ids_tipos_de_ventas"] . ") ";
        }

        $paramPeriodo = "AND (ventas.created_at BETWEEN '" . $params["fecha_inicio"] . "' AND '" . $params["fecha_termino"] . "') ";

        $venta = new Venta();
        $query = "SELECT
	                CONCAT(empleados.nombre,' ',empleados.apellido_paterno) AS 'vendedor',
                    CONCAT(clientes.nombre,' ',clientes.apellido_paterno) AS 'cliente',
                    ventas.created_at AS 'fecha_venta',
                    tipos_de_ventas.tipo AS 'tipo_de_venta',
                    ventas.fecha_programada AS 'fecha_programada',
                    IF(ventas.fecha_visita IS NULL,'',ventas.fecha_visita) AS 'fecha_visita',
                    ventas.hora_visita AS 'hora_visita',
                    estados_de_visitas.estado_de_visita AS 'estado_de_visita'
                FROM ventas
	                INNER JOIN usuarios ON ventas.id_usuario_atendio = usuarios.id
                    INNER JOIN empleados ON usuarios.id = empleados.id
                    INNER JOIN clientes ON ventas.id_cliente = clientes.id
                    INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id
                    INNER JOIN estados_de_visitas ON ventas.id_estado_de_visita = estados_de_visitas.id
                WHERE
	                ventas.deleted_at IS NULL
                    $paramVendedores
                    $paramTiposDeVentas
                    $paramPeriodo
                ORDER BY
	                ventas.id";

        $result = $venta->customQuery($query);

        // SI OCURRIÓ UN ERROR AL OBTENER LA INFORMACIÓN DE LA BD
        if (gettype($result) == "string")
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => $result]);
            exit;
        }

        // SI OCURRIÓ UN ERROR DESCONOCIDO AL INTENTAR OBTENER LA INFORMACIÓN DE LA BD
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

        if (count($result) === 0)
        {
            $statusCode = new Response();
            $statusCode->setStatusCode(400);
            echo json_encode(['response' => false, 'message' => "No se obtuvo información que coincida con los parámetros recibidos."]);
            exit;
        }

        // SI LA INFORMACIÓN SE OBTUVO CORRECTAMENTE EN LA BD
        echo json_encode(['response' => true, 'data' => json_encode($result)]);
        exit;
    }
}