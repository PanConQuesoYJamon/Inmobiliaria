<?php

require_once __DIR__ . '/../models/ClienteModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class ClientesController {

    private $modelo;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion            = (new Conexion)->conectar();
        $this->modelo        = new ClienteModel($conexion);
        $this->bitacora      = new BitacoraModel($conexion);
        $this->sessionHelper = new SessionHelper();
    }

    public function index(): void {
        $this->sessionHelper->verficarSession();
        $clientes = $this->modelo->getClientes();
        include __DIR__ . '/../views/clientes/listar_clientes.php';
    }

    public function new(): void {
        $this->sessionHelper->verficarSession();
        include __DIR__ . '/../views/clientes/new.php';
    }

    public function edit(): void {
        $this->sessionHelper->verficarSession();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=clientes');
            exit;
        }
        $cliente = $this->modelo->getCliente($codigo);
        include __DIR__ . '/../views/clientes/edit.php';
    }

    public function create(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=clientes');
            exit;
        }

        $datos = [
            'codigo'    => trim($_POST['codigo']    ?? ''),
            'nombres'   => trim($_POST['nombres']   ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'dpi'       => trim($_POST['dpi']       ?? ''),
            'nit'       => trim($_POST['nit']       ?? ''),
            'telefono'  => trim($_POST['telefono']  ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
            'direccion' => trim($_POST['direccion'] ?? '')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $resultado = $this->modelo->crearCliente($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'clientes',
                $this->modelo->getConexion()->insert_id,
                'INSERT',
                '',
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=clientes');
        exit;
    }

    public function update(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=clientes');
            exit;
        }

        $codigoOriginal = trim($_POST['codigo_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? null;
        $antes          = $this->modelo->getCliente($codigoOriginal);

        $datos = [
            'codigo'    => trim($_POST['codigo']    ?? ''),
            'nombres'   => trim($_POST['nombres']   ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'dpi'       => trim($_POST['dpi']       ?? ''),
            'nit'       => trim($_POST['nit']       ?? ''),
            'telefono'  => trim($_POST['telefono']  ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'estado'    => trim($_POST['estado']    ?? 'A')
        ];

        $resultado = $this->modelo->actualizarCliente($datos, $codigoOriginal, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'clientes',
                $antes['id'],
                'UPDATE',
                json_encode($antes),
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=clientes');
        exit;
    }

    public function delete(): void {
        $this->sessionHelper->verficarSession();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=clientes');
            exit;
        }

        $antes     = $this->modelo->getCliente($codigo);
        $resultado = $this->modelo->eliminarCliente($codigo);

        if ($resultado) {
            $this->bitacora->registrar(
                'clientes',
                $antes['id'],
                'DELETE',
                json_encode($antes),
                '',
                $_SESSION['usuario_id'] ?? 0
            );
        }

        header('Location: index.php?action=clientes');
        exit;
    }
}
