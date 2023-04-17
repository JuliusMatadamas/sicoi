<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\helpers\Auth;
use app\models\Venta;

class ApiVentaDashboardController extends Controller
{
    public function __construct()
    {
        Auth::verifySession();
        Auth::verifyJwt();
    }

    public function index(Request $request)
    {
        $venta = new Venta();
        $params = $request->getBody();

        if ($params['tablero'] == 'ventas_hoy_ayer') {
            $query = "SELECT ventas.created_at AS 'fecha_venta', tipos_de_ventas.tipo AS 'tipo_de_venta', COUNT(ventas.id_tipo_de_venta) AS 'total' FROM ventas INNER JOIN tipos_de_ventas ON ventas.id_tipo_de_venta = tipos_de_ventas.id JOIN (SELECT DISTINCT(ventas.created_at) FROM ventas ORDER BY ventas.created_at DESC LIMIT 2) b ON ventas.created_at IN (b.created_at) GROUP BY ventas.created_at, tipos_de_ventas.tipo ORDER BY ventas.created_at DESC";
            $result = $venta->customQuery($query);
            if (count($result) === 0){
                $statusCode = new Response();
                $statusCode->setStatusCode(400);
                return json_encode(['response' => false, 'data' => []]);
            } else {
                return json_encode(['response' => true, 'data' => $result]);
            }
        }
    }
}