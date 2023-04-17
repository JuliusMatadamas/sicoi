<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
const ENTORNO = "https://juliomatadamas.com";
const NAME_APP = "SICOI";
const LANG = "es-MX";
const CLAVE_SECRETA = "6LftkdAiAAAAADBtGFg3g8zZ3qcXOcpvFQeQIiLe";
const RUTA_IMG_PROFILES = __DIR__ . '/public/img/profiles/';
const RUTA_INCAPACIDADES = __DIR__ . '/public/img/incapacidades/';

use app\controllers\AdminInfoController;
use app\controllers\AdminPermisoController;
use app\controllers\AdminReporteController;
use app\controllers\AdminVacacionController;
use app\controllers\AlmacenController;
use app\controllers\api\ApiCategoriaController;
use app\controllers\api\ApiClienteController;
use app\controllers\api\ApiColoniaController;
use app\controllers\api\ApiEmpleadoController;
use app\controllers\api\ApiEstadoDeInventarioController;
use app\controllers\api\ApiEstadoDeVisitaController;
use app\controllers\api\ApiGeneroController;
use app\controllers\api\ApiIncapacidadController;
use app\controllers\api\ApiOrganizacionController;
use app\controllers\api\ApiPermisoController;
use app\controllers\api\ApiProductoController;
use app\controllers\api\ApiProductoPorCategoriaController;
use app\controllers\api\ApiProductoPorCategoriaSalidaController;
use app\controllers\api\ApiPuestoController;
use app\controllers\api\ApiTecnicoController;
use app\controllers\api\ApiTipoDeVentaController;
use app\controllers\api\ApiUsuarioController;
use app\controllers\api\ApiVacacionController;
use app\controllers\api\ApiValidarSerieSalidaController;
use app\controllers\api\ApiVentaController;
use app\controllers\api\ApiVentaDashboardController;
use app\controllers\api\ApiVentaReporteController;
use app\controllers\api\ApiVentasPorAsignarController;
use app\controllers\api\ApiVisitasAsignadasController;
use app\controllers\CategoriaController;
use app\controllers\ClienteController;
use app\controllers\EmpleadoController;
use app\controllers\ErrorController;
use app\controllers\EstadoDeInventarioController;
use app\controllers\EstadoDeVisitaController;
use app\controllers\GeneroController;
use app\controllers\IncapacidadController;
use app\controllers\InfoController;
use app\controllers\AuthController;
use app\controllers\ModuloController;
use app\controllers\OrganizacionController;
use app\controllers\PermisoController;
use app\controllers\ProductoController;
use app\controllers\PuestoController;
use app\controllers\SubModuloController;
use app\controllers\SupervisionController;
use app\controllers\TecnicoController;
use app\controllers\TipoDeVentaController;
use app\controllers\UsuarioController;
use app\controllers\VacacionController;
use app\controllers\VentaController;
use app\core\Application;

/** 00000 - SE DEFINE LA ZONA HORARIA DE LA APLICACIÓN */
date_default_timezone_set('America/Matamoros');

/** 00001 - SE CARGA EL AUTOLOAD DE LAS CLASES */
require_once __DIR__ . '/vendor/autoload.php';

/** 00002 - SE CREA UNA NUEVA INSTANCIA DE LA CLASE 'Application' */
$app = new Application($_SERVER['DOCUMENT_ROOT']);


/** RUTAS */
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO INFO
 * ================================================================================================
 */
$app->router->get('info', [InfoController::class, 'index']);
$app->router->get('info/datos', [InfoController::class, 'datos']);
$app->router->post('info/datos', [InfoController::class, 'guardarDatos']);
$app->router->get('info/incapacidades', [InfoController::class, 'incapacidad']);
$app->router->post('info/incapacidades', [InfoController::class, 'modificarIncapacidad']);
$app->router->get('info/permisos', [PermisoController::class, 'index']);
$app->router->get('info/permisos/nuevo', [PermisoController::class, 'create']);
$app->router->post('info/permisos/nuevo', [PermisoController::class, 'store']);
$app->router->get('info/permisos/editar', [PermisoController::class, 'read']);
$app->router->post('info/permisos/actualizar', [PermisoController::class, 'update']);
$app->router->post('info/permisos/eliminar', [PermisoController::class, 'delete']);
$app->router->get('info/vacaciones', [VacacionController::class, 'index']);
$app->router->get('info/vacaciones/nuevo', [VacacionController::class, 'create']);
$app->router->post('info/vacaciones/store', [VacacionController::class, 'store']);
$app->router->get('info/vacaciones/edit', [VacacionController::class, 'edit']);
$app->router->post('info/vacaciones/update', [VacacionController::class, 'update']);
$app->router->post('info/vacaciones/delete', [VacacionController::class, 'delete']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO ADMINISTRACIÓN
 * ================================================================================================
 */
/** ESTADOS DE INVENTARIO */
$app->router->get('administracion/estados_de_inventarios', [EstadoDeInventarioController::class, 'index']);
$app->router->post('administracion/estados_de_inventarios', [EstadoDeInventarioController::class, 'register']);
/** ESTADOS DE VISITAS */
$app->router->get('administracion/estados_de_visitas', [EstadoDeVisitaController::class, 'index']);
$app->router->post('administracion/estados_de_visitas', [EstadoDeVisitaController::class, 'register']);
/** GENEROS */
$app->router->get('administracion/generos', [GeneroController::class, 'index']);
$app->router->post('administracion/generos', [GeneroController::class, 'save']);
$app->router->post('administracion/generos/delete', [GeneroController::class, 'delete']);
/** MODULOS */
$app->router->get('administracion/modulos', [ModuloController::class, 'index']);
$app->router->post('administracion/modulos', [ModuloController::class, 'save']);
/** SUBMODULOS */
$app->router->get('administracion/submodulos', [SubModuloController::class, 'index']);
$app->router->post('administracion/submodulos', [SubModuloController::class, 'save']);
/** INCAPACIDADES */
$app->router->get('administracion/incapacidades', [IncapacidadController::class, 'index']);
$app->router->post('administracion/incapacidades', [IncapacidadController::class, 'register']);
/** PERMISOS */
$app->router->get('administracion/permisos', [AdminPermisoController::class, 'index']);
$app->router->post('administracion/permisos', [AdminPermisoController::class, 'register']);
/** PUESTOS */
$app->router->get('administracion/puestos', [PuestoController::class, 'index']);
$app->router->post('administracion/puestos', [PuestoController::class, 'save']);
$app->router->post('administracion/puestos/delete', [PuestoController::class, 'delete']);
/** REPORTES */
$app->router->get('administracion/reportes', [AdminReporteController::class, 'index']);
$app->router->post('administracion/reportes', [AdminReporteController::class, 'save']);
/** TIPOS DE VENTAS */
$app->router->get('administracion/tipos_de_ventas', [TipoDeVentaController::class, 'index']);
$app->router->post('administracion/tipos_de_ventas', [TipoDeVentaController::class, 'register']);
/** VACACIONES */
$app->router->get('administracion/vacaciones', [AdminVacacionController::class, 'index']);
$app->router->post('administracion/vacaciones', [AdminVacacionController::class, 'register']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO EMPLEADOS
 * ================================================================================================
 */
$app->router->get('empleados', [EmpleadoController::class, 'index']);
$app->router->post('empleados/editar', [EmpleadoController::class, 'editar']);
$app->router->get('empleados/nuevo', [EmpleadoController::class, 'nuevo']);
$app->router->post('empleados/nuevo', [EmpleadoController::class, 'save']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO USUARIOS
 * ================================================================================================
 */
$app->router->get('usuarios/consultar', [UsuarioController::class, 'index']);
$app->router->get('usuarios/nuevo', [UsuarioController::class, 'nuevo']);
$app->router->post('usuarios/editar', [UsuarioController::class, 'editar']);
$app->router->post('usuarios/guardar', [UsuarioController::class, 'guardar']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO CLIENTES
 * ================================================================================================
 */
$app->router->get('clientes/consultar', [ClienteController::class, 'index']);
$app->router->get('clientes/editar', [ClienteController::class, 'edit']);
$app->router->post('clientes/editar', [ClienteController::class, 'update']);
$app->router->get('clientes/nuevo', [ClienteController::class, 'create']);
$app->router->post('clientes/nuevo', [ClienteController::class, 'store']);
$app->router->get('clientes/reportes', [ClienteController::class, 'reportes']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO VENTAS
 * ================================================================================================
 */
$app->router->get('ventas/consultar', [VentaController::class, 'read']);
$app->router->get('ventas/dashboard', [VentaController::class, 'index']);
$app->router->get('ventas/editar', [VentaController::class, 'edit']);
$app->router->post('ventas/editar', [VentaController::class, 'update']);
$app->router->get('ventas/nueva', [VentaController::class, 'create']);
$app->router->post('ventas/nueva', [VentaController::class, 'store']);
$app->router->get('ventas/reportes', [VentaController::class, 'reportes']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO ALMACÉN
 * ================================================================================================
 */
$app->router->get('almacen/categoria', [CategoriaController::class, 'index']);
$app->router->post('almacen/categoria', [CategoriaController::class, 'register']);
$app->router->get('almacen/consultar', [AlmacenController::class, 'consultar']);
$app->router->get('almacen/dashboard', [AlmacenController::class, 'dashboard']);
$app->router->get('almacen/entradas', [AlmacenController::class, 'entradas']);
$app->router->post('almacen/entradas', [AlmacenController::class, 'registrarEntradas']);
$app->router->get('almacen/inventarios', [AlmacenController::class, 'inventarios']);
$app->router->get('almacen/organizaciones', [OrganizacionController::class, 'index']);
$app->router->post('almacen/organizaciones', [OrganizacionController::class, 'register']);
$app->router->get('almacen/productos', [ProductoController::class, 'index']);
$app->router->post('almacen/productos', [ProductoController::class, 'register']);
$app->router->get('almacen/reportes', [AlmacenController::class, 'reportes']);
$app->router->get('almacen/salidas', [AlmacenController::class, 'salidas']);
$app->router->post('almacen/salidas', [AlmacenController::class, 'registrarSalidas']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO TÉCNICOS
 * ================================================================================================
 */
$app->router->get('tecnicos/dashboard', [TecnicoController::class, 'dashboard']);
$app->router->get('tecnicos/entradas', [TecnicoController::class, 'entradas']);
$app->router->post('tecnicos/entradas', [TecnicoController::class, 'registrar_entradas']);
$app->router->get('tecnicos/mi_inventario', [TecnicoController::class, 'mi_inventario']);
$app->router->post('tecnicos/modificar_visita', [TecnicoController::class, 'modificar_visita']);
$app->router->post('tecnicos/actualizar_visita', [TecnicoController::class, 'actualizar_visita']);
$app->router->get('tecnicos/registrar_ubicacion', [TecnicoController::class, 'registrar_ubicacion']);
$app->router->get('tecnicos/reportes', [TecnicoController::class, 'reportes']);
$app->router->post('tecnicos/salidas', [TecnicoController::class, 'salidas']);
$app->router->get('tecnicos/salidas', [TecnicoController::class, 'salidas']);
$app->router->post('tecnicos/registrar_salidas', [TecnicoController::class, 'registrar_salidas']);
$app->router->get('tecnicos/visitas_asignadas', [TecnicoController::class, 'visitas_asignadas']);
/**
 * ================================================================================================
 * RUTAS DEL MÓDULO SUPERVISIÓN
 * ================================================================================================
 */
$app->router->get('supervision/asignar_visitas', [SupervisionController::class, 'asignar_visitas']);
$app->router->post('supervision/asignar_visitas', [SupervisionController::class, 'registrar_asignacion']);
$app->router->get('supervision/consultar_visitas', [SupervisionController::class, 'consultar_asignaciones']);
$app->router->get('supervision/dashboard', [SupervisionController::class, 'dashboard']);
$app->router->post('supervision/modificar_asignacion', [SupervisionController::class, 'modificar_asignacion']);
$app->router->post('supervision/actualizar_asignacion', [SupervisionController::class, 'actualizar_asignacion']);
$app->router->get('supervision/reportes', [SupervisionController::class, 'reportes']);
$app->router->get('supervision/ubicacion_de_tecnicos', [SupervisionController::class, 'ubicacion_de_tecnicos']);
/** LOGIN */
$app->router->get('', [AuthController::class, 'index']);
$app->router->post('', [AuthController::class, 'login']);
$app->router->get('reset_clave', [AuthController::class, 'reset']);
$app->router->post('reset_clave', [AuthController::class, 'send_token']);
$app->router->get('nueva_clave/', [AuthController::class, 'nueva_clave']);
$app->router->post('nueva_clave', [AuthController::class, 'update_clave']);
$app->router->get('log_out', [AuthController::class, 'destroy']);
/** ERRORES EN LAS RUTAS */
$app->router->get('_404', [ErrorController::class, 'index']);
$app->router->get('not_authorized', [ErrorController::class, 'notAuth']);

/**
 * ================================================================================================
 * RUTAS API
 * ================================================================================================
 */
$app->router->get('api/administracion/generos', [ApiGeneroController::class, 'index']);
$app->router->get('api/administracion/puestos', [ApiPuestoController::class, 'index']);
$app->router->get('api/empleados', [ApiEmpleadoController::class, 'index']);
$app->router->post('api/colonias', [ApiColoniaController::class, 'index']);
$app->router->get('api/usuarios', [ApiUsuarioController::class, 'index']);
$app->router->get('api/incapacidades', [ApiIncapacidadController::class, 'index']);
$app->router->post('api/incapacidades', [ApiIncapacidadController::class, 'edit']);
$app->router->get('api/incapacidades/por_validar', [ApiIncapacidadController::class, 'toValidate']);
$app->router->get('api/permisos', [ApiPermisoController::class, 'index']);
$app->router->get('api/permisos/por_validar', [ApiPermisoController::class, 'toValidate']);
$app->router->get('api/vacaciones', [ApiVacacionController::class, 'index']);
$app->router->get('api/clientes', [ApiClienteController::class, 'index']);
$app->router->get('api/estados_de_inventarios', [ApiEstadoDeInventarioController::class, 'index']);
$app->router->get('api/estados_de_visitas', [ApiEstadoDeVisitaController::class, 'index']);
$app->router->get('api/tipos_de_ventas', [ApiTipoDeVentaController::class, 'index']);
$app->router->get('api/ventas', [ApiVentaController::class, 'index']);
$app->router->post('api/ventas/dashboard', [ApiVentaDashboardController::class, 'index']);
$app->router->post('api/ventas/reporte', [ApiVentaReporteController::class, 'index']);
$app->router->get('api/categorias', [ApiCategoriaController::class, 'index']);
$app->router->get('api/productos', [ApiProductoController::class, 'index']);
$app->router->post('api/productos_por_categoria', [ApiProductoPorCategoriaController::class, 'index']);
$app->router->post('api/productos_por_categoria_salidas', [ApiProductoPorCategoriaSalidaController::class, 'index']);
$app->router->get('api/organizaciones', [ApiOrganizacionController::class, 'index']);
$app->router->get('api/ventas_por_asignar', [ApiVentasPorAsignarController::class, 'index']);
$app->router->get('api/visitas_asignadas', [ApiVisitasAsignadasController::class, 'index']);
$app->router->post('api/validar_serie_salida', [ApiValidarSerieSalidaController::class, 'index']);
$app->router->post('api/tecnicos/obtener_entradas', [ApiTecnicoController::class, 'obtener_entradas']);

/** 00003 - SE ARRANCA LA APLICACIÓN */
$app->run();