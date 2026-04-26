<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class UsuariosController {

    private $modelo;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion            = (new Conexion)->conectar();
        $this->modelo        = new UsuarioModel($conexion);
        $this->bitacora      = new BitacoraModel($conexion);
        $this->sessionHelper = new SessionHelper();
    }

    /* -------------------------------------------------------
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * ----------------------------------------------------- */

    public function index(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->adminOSupervisor();
        $usuarios = $this->modelo->getUsuarios();
        include __DIR__ . '/../views/usuarios/listar_usuarios.php';
    }

    public function new(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->adminOSupervisor();
        include __DIR__ . '/../views/usuarios/new.php';
    }

    public function edit(): void {
        $this->sessionHelper->soloAdmin();
        $this->sessionHelper->verficarSession();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=usuarios');
            exit;
        }

        $usuario = $this->modelo->getUsuario($codigo);
        include __DIR__ . '/../views/usuarios/edit.php';
    }

    /* -------------------------------------------------------
     * MÉTODOS DE ACCIÓN
     * ----------------------------------------------------- */

    public function create(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->adminOSupervisor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=usuarios');
            exit;
        }

        $datos = [
            'codigo'   => trim($_POST['codigo']   ?? ''),
            'nombre'   => trim($_POST['nombre']   ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'clave'    => trim($_POST['clave']    ?? ''),
            'rol'      => trim($_POST['rol']      ?? 'usuario')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $resultado = $this->modelo->crearUsuario($datos);

        if ($resultado) {
            $this->bitacora->registrar(
                'usuarios',
                $this->modelo->getConexion()->insert_id,
                'INSERT',
                '',
                json_encode([
                    'codigo'   => $datos['codigo'],
                    'nombre'   => $datos['nombre'],
                    'username' => $datos['username'],
                    'rol'      => $datos['rol']
                    // la clave nunca se registra en bitácora
                ]),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=usuarios');
        exit;
    }

    public function update(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->soloAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=usuarios');
            exit;
        }

        $codigoOriginal = trim($_POST['codigo_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? null;
        $antes          = $this->modelo->getUsuario($codigoOriginal);

        $datos = [
            'codigo'   => trim($_POST['codigo']   ?? ''),
            'nombre'   => trim($_POST['nombre']   ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'clave'    => trim($_POST['clave']    ?? ''),
            'estado'   => trim($_POST['estado']   ?? 'A'),
            'rol'      => trim($_POST['rol']      ?? 'usuario')
        ];

        $resultado = $this->modelo->actualizarUsuario($datos, $codigoOriginal);

        if ($resultado) {
            $this->bitacora->registrar(
                'usuarios',
                $antes['id'],
                'UPDATE',
                json_encode([
                    'codigo'   => $antes['codigo'],
                    'nombre'   => $antes['nombre'],
                    'username' => $antes['username'],
                    'estado'   => $antes['estado'],
                    'rol'      => $antes['rol']
                ]),
                json_encode([
                    'codigo'   => $datos['codigo'],
                    'nombre'   => $datos['nombre'],
                    'username' => $datos['username'],
                    'estado'   => $datos['estado'],
                    'rol'      => $datos['rol']
                ]),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=usuarios');
        exit;
    }

    /**DELETE */
    public function delete(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->soloAdmin();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=usuarios');
            exit;
        }

        $antes     = $this->modelo->getUsuario($codigo);
        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $resultado = $this->modelo->eliminarUsuario($codigo);

        if ($resultado) {
            $this->bitacora->registrar(
                'usuarios',
                $antes['id'],
                'DELETE',
                json_encode([
                    'codigo'   => $antes['codigo'],
                    'nombre'   => $antes['nombre'],
                    'username' => $antes['username'],
                    'rol'      => $antes['rol']
                ]),
                '',
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=usuarios');
        exit;
    }
}
