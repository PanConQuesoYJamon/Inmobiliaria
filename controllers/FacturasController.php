<?php

require_once __DIR__ . '/../models/FacturaModel.php';
require_once __DIR__ . '/../models/DetalleFacturaModel.php';
require_once __DIR__ . '/../models/ContratoModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/ConceptoFacturacionModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class FacturasController {

    private $modelo;
    private $modeloDetalle;
    private $modeloContratos;
    private $modeloClientes;
    private $modeloUsuarios;
    private $modeloConceptos;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion               = (new Conexion)->conectar();
        $this->modelo           = new FacturaModel($conexion);
        $this->modeloDetalle    = new DetalleFacturaModel($conexion);
        $this->modeloContratos  = new ContratoModel($conexion);
        $this->modeloClientes   = new ClienteModel($conexion);
        $this->modeloUsuarios   = new UsuarioModel($conexion);
        $this->modeloConceptos  = new ConceptoFacturacionModel($conexion);
        $this->bitacora         = new BitacoraModel($conexion);
        $this->sessionHelper    = new SessionHelper();
    }

    public function index(): void {
        $this->sessionHelper->verficarSession();
        $facturas = $this->modelo->getFacturas();
        include __DIR__ . '/../views/facturas/listar_facturas.php';
    }

    public function new(): void {
        $this->sessionHelper->verficarSession();
        $contratos = $this->modeloContratos->getContratos();
        $clientes  = $this->modeloClientes->getClientes();
        $usuarios  = $this->modeloUsuarios->getUsuarios();
        $conceptos = $this->modeloConceptos->getConceptosActivos();
        include __DIR__ . '/../views/facturas/new.php';
    }

    public function show(): void {
        $this->sessionHelper->verficarSession();

        $numero = $_GET['numero_factura'] ?? null;
        if (!$numero) {
            header('Location: index.php?action=facturas');
            exit;
        }

        $factura  = $this->modelo->getFactura($numero);
        $detalles = $this->modeloDetalle->getDetallesPorFactura($factura['id']);

        require_once __DIR__ . '/../models/PagoFacturaModel.php';
        $modeloPagos = new PagoFacturaModel($this->modelo->getConexion());
        $pagos = $modeloPagos->getPagosPorFactura($factura['id']);

        include __DIR__ . '/../views/facturas/show.php';
    }

    public function edit(): void {
        $this->sessionHelper->verficarSession();
        $numero = $_GET['numero_factura'] ?? null;
        if (!$numero) {
            header('Location: index.php?action=facturas');
            exit;
        }

        $factura   = $this->modelo->getFactura($numero);
        $detalles  = $this->modeloDetalle->getDetallesPorFactura($factura['id']);
        $contratos = $this->modeloContratos->getContratos();
        $clientes  = $this->modeloClientes->getClientes();
        $usuarios  = $this->modeloUsuarios->getUsuarios();
        $conceptos = $this->modeloConceptos->getConceptosActivos();
        include __DIR__ . '/../views/facturas/edit.php';
    }

    public function create(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=facturas');
            exit;
        }

        $datos = [
            'numero_factura'    => trim($_POST['numero_factura']    ?? ''),
            'contrato_id'       => (int)($_POST['contrato_id']      ?? 0),
            'cliente_id'        => (int)($_POST['cliente_id']       ?? 0),
            'usuario_id'        => (int)($_POST['usuario_id']       ?? 0),
            'fecha_emision'     => trim($_POST['fecha_emision']     ?? ''),
            'fecha_vencimiento' => !empty($_POST['fecha_vencimiento']) ? trim($_POST['fecha_vencimiento']) : null,
            'subtotal'          => (float)($_POST['subtotal']       ?? 0),
            'impuesto'          => (float)($_POST['impuesto']       ?? 0),
            'total'             => (float)($_POST['total']          ?? 0),
            'observaciones'     => trim($_POST['observaciones']     ?? '')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $resultado = $this->modelo->crearFactura($datos, $usuarioId);

        if ($resultado) {
            $facturaId = $this->modelo->getConexion()->insert_id;

            // Procesar renglones enviados desde el formulario
            $this->procesarRenglones($facturaId);

            $this->bitacora->registrar(
                'facturas', $facturaId, 'INSERT', '',
                json_encode($datos, JSON_UNESCAPED_UNICODE),
                $usuarioId
            );
        }

        header('Location: index.php?action=facturas');
        exit;
    }

    public function update(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=facturas');
            exit;
        }

        $numeroOriginal = trim($_POST['numero_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? 0;
        $antes          = $this->modelo->getFactura($numeroOriginal);

        $datos = [
            'numero_factura'    => trim($_POST['numero_factura']    ?? ''),
            'contrato_id'       => (int)($_POST['contrato_id']      ?? 0),
            'cliente_id'        => (int)($_POST['cliente_id']       ?? 0),
            'usuario_id'        => (int)($_POST['usuario_id']       ?? 0),
            'fecha_emision'     => trim($_POST['fecha_emision']     ?? ''),
            'fecha_vencimiento' => !empty($_POST['fecha_vencimiento']) ? trim($_POST['fecha_vencimiento']) : null,
            'subtotal'          => (float)($_POST['subtotal']       ?? 0),
            'impuesto'          => (float)($_POST['impuesto']       ?? 0),
            'total'             => (float)($_POST['total']          ?? 0),
            'observaciones'     => trim($_POST['observaciones']     ?? ''),
            'estado'            => trim($_POST['estado']            ?? 'borrador')
        ];

        $resultado = $this->modelo->actualizarFactura($datos, $numeroOriginal, $usuarioId);

        if ($resultado) {
            // Eliminar renglones anteriores y reemplazar con los nuevos
            $this->modeloDetalle->eliminarDetallesPorFactura($antes['id']);
            $this->procesarRenglones($antes['id']);

            $this->bitacora->registrar(
                'facturas', $antes['id'], 'UPDATE',
                json_encode($antes, JSON_UNESCAPED_UNICODE),
                json_encode($datos, JSON_UNESCAPED_UNICODE),
                $usuarioId
            );
        }

        header('Location: index.php?action=facturas');
        exit;
    }

    public function delete(): void {
        $this->sessionHelper->soloAdmin();
        $numero = $_GET['numero_factura'] ?? null;
        if (!$numero) {
            header('Location: index.php?action=facturas');
            exit;
        }

        $antes     = $this->modelo->getFactura($numero);
        $usuarioId = $_SESSION['usuario_id'] ?? 0;

        $this->modeloDetalle->eliminarDetallesPorFactura($antes['id']);
        $resultado = $this->modelo->eliminarFactura($numero);

        if ($resultado) {
            $this->bitacora->registrar(
                'facturas', $antes['id'], 'DELETE',
                json_encode($antes, JSON_UNESCAPED_UNICODE),
                '', $usuarioId
            );
        }

        header('Location: index.php?action=facturas');
        exit;
    }

    /* -------------------------------------------------------
     * Método privado — procesa los renglones del POST
     * ----------------------------------------------------- */
    private function procesarRenglones(int $facturaId): void {
        $conceptos      = $_POST['concepto']       ?? [];
        $conceptoIds    = $_POST['concepto_id']    ?? [];
        $cantidades     = $_POST['cantidad']       ?? [];
        $precios        = $_POST['precio_unitario']?? [];

        foreach ($conceptos as $i => $concepto) {
            if (empty(trim($concepto))) continue;

            $this->modeloDetalle->crearDetalle([
                'factura_id'      => $facturaId,
                'concepto_id'     => !empty($conceptoIds[$i]) ? (int)$conceptoIds[$i] : null,
                'concepto'        => trim($concepto),
                'cantidad'        => (float)($cantidades[$i] ?? 1),
                'precio_unitario' => (float)($precios[$i]    ?? 0)
            ]);
        }
    }
}
