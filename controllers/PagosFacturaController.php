<?php

require_once __DIR__ . '/../models/PagoFacturaModel.php';
require_once __DIR__ . '/../models/FacturaModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class PagosFacturaController {

    private $modelo;
    private $modeloFacturas;
    private $modeloUsuarios;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion             = (new Conexion)->conectar();
        $this->modelo         = new PagoFacturaModel($conexion);
        $this->modeloFacturas = new FacturaModel($conexion);
        $this->modeloUsuarios = new UsuarioModel($conexion);
        $this->bitacora       = new BitacoraModel($conexion);
        $this->sessionHelper  = new SessionHelper();
    }

    public function index(): void {
        $this->sessionHelper->verficarSession();
        $pagos = $this->modelo->getPagos();
        include __DIR__ . '/../views/pagos_factura/listar_pagos_factura.php';
    }

    public function new(): void {
        $this->sessionHelper->verficarSession();
        $facturas = $this->modeloFacturas->getFacturas();
        $usuarios = $this->modeloUsuarios->getUsuarios();
        include __DIR__ . '/../views/pagos_factura/new.php';
    }

    public function create(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pagos_factura');
            exit;
        }

        $datos = [
            'numero_recibo' => trim($_POST['numero_recibo'] ?? ''),
            'factura_id'    => (int)($_POST['factura_id']   ?? 0),
            'usuario_id'    => (int)($_POST['usuario_id']   ?? 0),
            'fecha_pago'    => trim($_POST['fecha_pago']    ?? ''),
            'monto_pagado'  => (float)($_POST['monto_pagado'] ?? 0),
            'metodo_pago'   => trim($_POST['metodo_pago']   ?? ''),
            'referencia'    => trim($_POST['referencia']    ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? '')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $resultado = $this->modelo->crearPago($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'pagos_factura',
                $this->modelo->getConexion()->insert_id,
                'INSERT', '',
                json_encode($datos, JSON_UNESCAPED_UNICODE),
                $usuarioId
            );
        }

        header('Location: index.php?action=pagos_factura');
        exit;
    }

    public function anular(): void {
        $this->sessionHelper->soloAdmin();
        $recibo = $_GET['numero_recibo'] ?? null;
        if (!$recibo) {
            header('Location: index.php?action=pagos_factura');
            exit;
        }

        $antes     = $this->modelo->getPago($recibo);
        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $resultado = $this->modelo->anularPago($recibo, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'pagos_factura', $antes['id'], 'UPDATE',
                json_encode($antes, JSON_UNESCAPED_UNICODE),
                json_encode(['estado' => 'I'], JSON_UNESCAPED_UNICODE),
                $usuarioId
            );
        }

        header('Location: index.php?action=pagos_factura');
        exit;
    }
}
