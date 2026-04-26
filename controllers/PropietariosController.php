<?php

require_once __DIR__ . '/../models/PropietarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class PropietariosController {

    private $modelo;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion            = (new Conexion)->conectar();
        $this->modelo        = new PropietarioModel($conexion);
        $this->bitacora      = new BitacoraModel($conexion);
        $this->sessionHelper = new SessionHelper();
    }

    public function index(): void {
        $this->sessionHelper->verficarSession();
        $propietarios = $this->modelo->getPropietarios();
        include __DIR__ . '/../views/propietarios/listar_propietarios.php';
    }

    public function new(): void {
        $this->sessionHelper->verficarSession();
        include __DIR__ . '/../views/propietarios/new.php';
    }

    public function edit(): void {
        $this->sessionHelper->verficarSession();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=propietarios');
            exit;
        }
        $propietario = $this->modelo->getPropietario($codigo);
        include __DIR__ . '/../views/propietarios/edit.php';
    }

    public function create(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=propietarios');
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
        $resultado = $this->modelo->crearPropietario($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'propietarios',
                $this->modelo->getConexion()->insert_id,
                'INSERT',
                '',
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=propietarios');
        exit;
    }

    public function update(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=propietarios');
            exit;
        }

        $codigoOriginal = trim($_POST['codigo_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? null;
        $antes          = $this->modelo->getPropietario($codigoOriginal);

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

        $resultado = $this->modelo->actualizarPropietario($datos, $codigoOriginal, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'propietarios',
                $antes['id'],
                'UPDATE',
                json_encode($antes),
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=propietarios');
        exit;
    }

    public function delete(): void {
        $this->sessionHelper->verficarSession();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=propietarios');
            exit;
        }

        $antes     = $this->modelo->getPropietario($codigo);
        $resultado = $this->modelo->eliminarPropietario($codigo);

        if ($resultado) {
            $this->bitacora->registrar(
                'propietarios',
                $antes['id'],
                'DELETE',
                json_encode($antes),
                '',
                $_SESSION['usuario_id'] ?? 0
            );
        }

        header('Location: index.php?action=propietarios');
        exit;
    }
}
