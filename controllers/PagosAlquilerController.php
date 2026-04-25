<?php

require_once __DIR__ . '/../models/PagoAlquilerModel.php';
require_once __DIR__ . '/../models/ContratoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class PagosAlquilerController {

    private $modelo;
    private $modeloContratos;
    private $modeloUsuarios;
    private $bitacora;

    public function __construct() {
        $conexion              = (new Conexion)->conectar();
        $this->modelo          = new PagoAlquilerModel($conexion);
        $this->modeloContratos = new ContratoModel($conexion);
        $this->modeloUsuarios  = new UsuarioModel($conexion);
        $this->bitacora        = new BitacoraModel($conexion);
    }

    /* -------------------------------------------------------
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * ----------------------------------------------------- */

    public function index(): void {
        $pagos = $this->modelo->getPagos();
        include __DIR__ . '/../views/pagos_alquiler/listar_pagos.php';
    }

    public function new(): void {
        $contratos = $this->modeloContratos->getContratos();
        $usuarios  = $this->modeloUsuarios->getUsuarios();
        include __DIR__ . '/../views/pagos_alquiler/new.php';
    }

    public function edit(): void {
        $recibo = $_GET['numero_recibo'] ?? null;
        if (!$recibo) {
            header('Location: index.php?action=pagos_alquiler');
            exit;
        }

        $pago      = $this->modelo->getPago($recibo);
        $contratos = $this->modeloContratos->getContratos();
        $usuarios  = $this->modeloUsuarios->getUsuarios();
        include __DIR__ . '/../views/pagos_alquiler/edit.php';
    }

    /* -------------------------------------------------------
     * MÉTODOS DE ACCIÓN
     * ----------------------------------------------------- */

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pagos_alquiler');
            exit;
        }

        $datos = [
            'numero_recibo'  => trim($_POST['numero_recibo']     ?? ''),
            'contrato_id'    => (int)($_POST['contrato_id']      ?? 0),
            'usuario_id'     => (int)($_POST['usuario_id']       ?? 0),
            'fecha_pago'     => trim($_POST['fecha_pago']        ?? ''),
            'periodo_mes'    => trim($_POST['periodo_mes']       ?? ''),
            'monto_esperado' => (float)($_POST['monto_esperado'] ?? 0),
            'monto_pagado'   => (float)($_POST['monto_pagado']   ?? 0),
            'mora'           => (float)($_POST['mora']           ?? 0),
            'metodo_pago'    => trim($_POST['metodo_pago']       ?? ''),
            'observaciones'  => trim($_POST['observaciones']     ?? '')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $resultado = $this->modelo->crearPago($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'pagos_alquiler',
                $this->modelo->getConexion()->insert_id,
                'INSERT',
                '',
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=pagos_alquiler');
        exit;
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=pagos_alquiler');
            exit;
        }

        $reciboOriginal = trim($_POST['recibo_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? null;
        $antes          = $this->modelo->getPago($reciboOriginal);

        $datos = [
            'numero_recibo'  => trim($_POST['numero_recibo']     ?? ''),
            'contrato_id'    => (int)($_POST['contrato_id']      ?? 0),
            'usuario_id'     => (int)($_POST['usuario_id']       ?? 0),
            'fecha_pago'     => trim($_POST['fecha_pago']        ?? ''),
            'periodo_mes'    => trim($_POST['periodo_mes']       ?? ''),
            'monto_esperado' => (float)($_POST['monto_esperado'] ?? 0),
            'monto_pagado'   => (float)($_POST['monto_pagado']   ?? 0),
            'mora'           => (float)($_POST['mora']           ?? 0),
            'metodo_pago'    => trim($_POST['metodo_pago']       ?? ''),
            'observaciones'  => trim($_POST['observaciones']     ?? ''),
            'estado'         => trim($_POST['estado']            ?? 'A')
        ];

        $resultado = $this->modelo->actualizarPago($datos, $reciboOriginal, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'pagos_alquiler',
                $antes['id'],
                'UPDATE',
                json_encode($antes),
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=pagos_alquiler');
        exit;
    }

    public function delete(): void {
        $recibo = $_GET['numero_recibo'] ?? null;
        if (!$recibo) {
            header('Location: index.php?action=pagos_alquiler');
            exit;
        }

        $antes     = $this->modelo->getPago($recibo);
        $resultado = $this->modelo->eliminarPago($recibo);

        if ($resultado) {
            $this->bitacora->registrar(
                'pagos_alquiler',
                $antes['id'],
                'DELETE',
                json_encode($antes),
                '',
                $_SESSION['usuario_id'] ?? 0
            );
        }

        header('Location: index.php?action=pagos_alquiler');
        exit;
    }
}
