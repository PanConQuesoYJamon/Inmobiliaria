<?php

if (session_status() === PHP_SESSION_NONE){

    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' =>  ' ',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}


require 'config/env.php';
require 'Router.php';

// Controllers
require 'controllers/UsuariosController.php';
require 'controllers/PropietariosController.php';
require 'controllers/ClientesController.php';
require 'controllers/ZonasController.php';
require 'controllers/TiposInmuebleController.php';
require 'controllers/InmueblesController.php';
require 'controllers/TiposContratoController.php';
require 'controllers/ContratosController.php';
require 'controllers/PagosAlquilerController.php';
require 'controllers/BitacoraController.php';
require 'controllers/AuthController.php';

// Controllers Tablas nuevas: Facturación
require 'controllers/ConceptosFacturacionController.php';
require 'controllers/FacturasController.php';
require 'controllers/PagosFacturaController.php';

// Instancias
$usuariosController      = new UsuariosController();
$propietariosController  = new PropietariosController();
$clientesController      = new ClientesController();
$zonasController         = new ZonasController();
$tiposInmuebleController = new TiposInmuebleController();
$inmueblesController     = new InmueblesController();
$tiposContratoController = new TiposContratoController();
$contratosController     = new ContratosController();
$pagosAlquilerController = new PagosAlquilerController();
$bitacoraController      = new BitacoraController();
$authController          = new AuthController();

// Instancias Tablas nuevas: Facturación
$conceptosFacturacionController = new ConceptosFacturacionController();
$facturasController             = new FacturasController();
$pagosFacturaController         = new PagosFacturaController();

$router = new Router();

// Rutas de Usuarios
$router->add('usuarios',        [$usuariosController, 'index']);
$router->add('usuario_new',     [$usuariosController, 'new']);
$router->add('usuario_create',  [$usuariosController, 'create']);
$router->add('usuario_edit',    [$usuariosController, 'edit']);
$router->add('usuario_update',  [$usuariosController, 'update']);
$router->add('usuario_delete',  [$usuariosController, 'delete']);

// Rutas de Propietarios
$router->add('propietarios',          [$propietariosController, 'index']);
$router->add('propietario_new',       [$propietariosController, 'new']);
$router->add('propietario_create',    [$propietariosController, 'create']);
$router->add('propietario_edit',      [$propietariosController, 'edit']);
$router->add('propietario_update',    [$propietariosController, 'update']);
$router->add('propietario_delete',    [$propietariosController, 'delete']);

// Rutas de Clientes
$router->add('clientes',        [$clientesController, 'index']);
$router->add('cliente_new',     [$clientesController, 'new']);
$router->add('cliente_create',  [$clientesController, 'create']);
$router->add('cliente_edit',    [$clientesController, 'edit']);
$router->add('cliente_update',  [$clientesController, 'update']);
$router->add('cliente_delete',  [$clientesController, 'delete']);

// Rutas de Zonas
$router->add('zonas',        [$zonasController, 'index']);
$router->add('zona_new',     [$zonasController, 'new']);
$router->add('zona_create',  [$zonasController, 'create']);
$router->add('zona_edit',    [$zonasController, 'edit']);
$router->add('zona_update',  [$zonasController, 'update']);
$router->add('zona_delete',  [$zonasController, 'delete']);


// Rutas de Tipos de Inmueble
$router->add('tipos_inmueble',        [$tiposInmuebleController, 'index']);
$router->add('tipo_inmueble_new',     [$tiposInmuebleController, 'new']);
$router->add('tipo_inmueble_create',  [$tiposInmuebleController, 'create']);
$router->add('tipo_inmueble_edit',    [$tiposInmuebleController, 'edit']);
$router->add('tipo_inmueble_update',  [$tiposInmuebleController, 'update']);
$router->add('tipo_inmueble_delete',  [$tiposInmuebleController, 'delete']);

// Rutas: Inmuebles
$router->add('inmuebles',        [$inmueblesController, 'index']);
$router->add('inmueble_new',     [$inmueblesController, 'new']);
$router->add('inmueble_create',  [$inmueblesController, 'create']);
$router->add('inmueble_edit',    [$inmueblesController, 'edit']);
$router->add('inmueble_update',  [$inmueblesController, 'update']);
$router->add('inmueble_delete',  [$inmueblesController, 'delete']);

// Rutas: Tipos de Contrato
$router->add('tipos_contrato',        [$tiposContratoController, 'index']);
$router->add('tipo_contrato_new',     [$tiposContratoController, 'new']);
$router->add('tipo_contrato_create',  [$tiposContratoController, 'create']);
$router->add('tipo_contrato_edit',    [$tiposContratoController, 'edit']);
$router->add('tipo_contrato_update',  [$tiposContratoController, 'update']);
$router->add('tipo_contrato_delete',  [$tiposContratoController, 'delete']);

// Rutas: Contratos
$router->add('contratos',        [$contratosController, 'index']);
$router->add('contrato_new',     [$contratosController, 'new']);
$router->add('contrato_create',  [$contratosController, 'create']);
$router->add('contrato_edit',    [$contratosController, 'edit']);
$router->add('contrato_update',  [$contratosController, 'update']);
$router->add('contrato_delete',  [$contratosController, 'delete']);

// Rutas: Pagos de Alquiler
$router->add('pagos_alquiler',      [$pagosAlquilerController, 'index']);
$router->add('pago_new',            [$pagosAlquilerController, 'new']);
$router->add('pago_create',         [$pagosAlquilerController, 'create']);
$router->add('pago_edit',           [$pagosAlquilerController, 'edit']);
$router->add('pago_update',         [$pagosAlquilerController, 'update']);
$router->add('pago_delete',         [$pagosAlquilerController, 'delete']);

//Ruta para auntenticar
$router->add('login',              [$authController, 'index']);
$router->add('auntenticar',        [$authController, 'auntenticar']);
$router->add('logout',             [$authController, 'logout']);

// Rutas: Bitácora (solo lectura)
$router->add('bitacora',         [$bitacoraController, 'index']);
$router->add('bitacora_filtrar', [$bitacoraController, 'filtrar']);

// --- rutas nuevas: Conceptos de Facturación ---
$router->add('conceptos_facturacion',        [$conceptosFacturacionController, 'index']);
$router->add('concepto_facturacion_new',     [$conceptosFacturacionController, 'new']);
$router->add('concepto_facturacion_create',  [$conceptosFacturacionController, 'create']);
$router->add('concepto_facturacion_edit',    [$conceptosFacturacionController, 'edit']);
$router->add('concepto_facturacion_update',  [$conceptosFacturacionController, 'update']);
$router->add('concepto_facturacion_delete',  [$conceptosFacturacionController, 'delete']);


// --- rutas nuevas: Facturas ---
$router->add('facturas',        [$facturasController, 'index']);
$router->add('factura_new',     [$facturasController, 'new']);
$router->add('factura_create',  [$facturasController, 'create']);
$router->add('factura_show',    [$facturasController, 'show']);
$router->add('factura_edit',    [$facturasController, 'edit']);
$router->add('factura_update',  [$facturasController, 'update']);
$router->add('factura_delete',  [$facturasController, 'delete']);

// --- rutas nuevas: Pagos de Factura ---
$router->add('pagos_factura',        [$pagosFacturaController, 'index']);
$router->add('pago_factura_new',     [$pagosFacturaController, 'new']);
$router->add('pago_factura_create',  [$pagosFacturaController, 'create']);
$router->add('pago_factura_anular',  [$pagosFacturaController, 'anular']);


// Ruta por defecto al entrar al sistema
$routeDefault = 'usuarios';

$rutasPublicas = ['login', 'auntenticar'];

$accionActual = $_GET['action'] ?? 'login';

if (!in_array($accionActual, $rutasPublicas) && empty($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$router->dispatch($routeDefault);
?>