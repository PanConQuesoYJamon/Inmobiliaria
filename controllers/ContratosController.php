<?php

require_once __DIR__ . '/../models/ContratoModel.php';
require_once __DIR__ . '/../models/InmuebleModel.php';
require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/TipoContratoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class ContratosController {

    private $modelo;
    private $modeloInmuebles;
    private $modeloClientes;
    private $modeloTipos;
    private $modeloUsuarios;
    private $bitacora;

    public function __construct() {
        $conexion              = (new Conexion)->conectar();
        $this->modelo          = new ContratoModel($conexion);
        $this->modeloInmuebles = new InmuebleModel($conexion);
        $this->modeloClientes  = new ClienteModel($conexion);
        $this->modeloTipos     = new TipoContratoModel($conexion);
        $this->modeloUsuarios  = new UsuarioModel($conexion);
        $this->bitacora        = new BitacoraModel($conexion);
    }

    /* -------------------------------------------------------
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * ----------------------------------------------------- */

    public function index(): void {
        $contratos = $this->modelo->getContratos();
        include __DIR__ . '/../views/contratos/listar_contratos.php';
    }

    public function new(): void {
        $inmuebles = $this->modeloInmuebles->getInmuebles();
        $clientes  = $this->modeloClientes->getClientes();
        $tipos     = $this->modeloTipos->getTiposContrato();
        $usuarios  = $this->modeloUsuarios->getUsuarios();
        include __DIR__ . '/../views/contratos/new.php';
    }

    public function edit(): void {
        $numero = $_GET['numero_contrato'] ?? null;
        if (!$numero) {
            header('Location: index.php?action=contratos');
            exit;
        }

        $contrato  = $this->modelo->getContrato($numero);
        $inmuebles = $this->modeloInmuebles->getInmuebles();
        $clientes  = $this->modeloClientes->getClientes();
        $tipos     = $this->modeloTipos->getTiposContrato();
        $usuarios  = $this->modeloUsuarios->getUsuarios();
        include __DIR__ . '/../views/contratos/edit.php';
    }

    /* -------------------------------------------------------
     * MÉTODOS DE ACCIÓN
     * ----------------------------------------------------- */

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=contratos');
            exit;
        }

        $datos = [
            'numero_contrato'  => trim($_POST['numero_contrato']   ?? ''),
            'inmueble_id'      => (int)($_POST['inmueble_id']      ?? 0),
            'cliente_id'       => (int)($_POST['cliente_id']       ?? 0),
            'tipo_contrato_id' => (int)($_POST['tipo_contrato_id'] ?? 0),
            'usuario_id'       => (int)($_POST['usuario_id']       ?? 0),
            'fecha_inicio'     => trim($_POST['fecha_inicio']      ?? ''),
            'fecha_fin'        => trim($_POST['fecha_fin']         ?? ''),
            'monto_mensual'    => (float)($_POST['monto_mensual']  ?? 0),
            'deposito'         => (float)($_POST['deposito']       ?? 0),
            'dia_pago'         => (int)($_POST['dia_pago']         ?? 1),
            'observaciones'    => trim($_POST['observaciones']     ?? '')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $resultado = $this->modelo->crearContrato($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'contratos',
                $this->modelo->getConexion()->insert_id,
                'INSERT',
                '',
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=contratos');
        exit;
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=contratos');
            exit;
        }

        $numeroOriginal = trim($_POST['numero_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? null;
        $antes          = $this->modelo->getContrato($numeroOriginal);

        $datos = [
            'numero_contrato'  => trim($_POST['numero_contrato']   ?? ''),
            'inmueble_id'      => (int)($_POST['inmueble_id']      ?? 0),
            'cliente_id'       => (int)($_POST['cliente_id']       ?? 0),
            'tipo_contrato_id' => (int)($_POST['tipo_contrato_id'] ?? 0),
            'usuario_id'       => (int)($_POST['usuario_id']       ?? 0),
            'fecha_inicio'     => trim($_POST['fecha_inicio']      ?? ''),
            'fecha_fin'        => trim($_POST['fecha_fin']         ?? ''),
            'monto_mensual'    => (float)($_POST['monto_mensual']  ?? 0),
            'deposito'         => (float)($_POST['deposito']       ?? 0),
            'dia_pago'         => (int)($_POST['dia_pago']         ?? 1),
            'observaciones'    => trim($_POST['observaciones']     ?? ''),
            'estado'           => trim($_POST['estado']            ?? 'A')
        ];

        $resultado = $this->modelo->actualizarContrato($datos, $numeroOriginal, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'contratos',
                $antes['id'],
                'UPDATE',
                json_encode($antes),
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=contratos');
        exit;
    }

    public function delete(): void {
        $numero = $_GET['numero_contrato'] ?? null;
        if (!$numero) {
            header('Location: index.php?action=contratos');
            exit;
        }

        $antes     = $this->modelo->getContrato($numero);
        $resultado = $this->modelo->eliminarContrato($numero);

        if ($resultado) {
            $this->bitacora->registrar(
                'contratos',
                $antes['id'],
                'DELETE',
                json_encode($antes),
                '',
                $_SESSION['usuario_id'] ?? 0
            );
        }

        header('Location: index.php?action=contratos');
        exit;
    }
}
