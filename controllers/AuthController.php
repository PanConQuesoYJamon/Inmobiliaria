<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class AuthController {

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS    = 300; // 5 min

    private $modelo;

    public function __construct() {
        $conexion     = (new Conexion)->conectar();
        $this->modelo = new UsuarioModel($conexion);
    }

    /**
     * Muestra el formulario de login
     */
    public function index(): void {
        $this->secureTokenCsrf();
        $csrfToken = $_SESSION['csrf_token'];
        include __DIR__ . '/../views/auth/index.php';
    }

    /**
     * Procesa el formulario de login
     * Nota: el nombre del método coincide con la ruta 'auntenticar' del router
     */
    public function auntenticar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        if (!$this->verifyTokenCsrf()) {
            $error = 'La sesión ha expirado. Por favor, inténtalo de nuevo.';
            header('Location: index.php?action=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $clave    = trim($_POST['clave']    ?? '');

        $usuario = $this->modelo->getUsuarioByUsername($username);
        

        // Después — soporta tanto texto plano como hash
        if ($usuario && password_verify($clave, $usuario['clave'])) {

            $_SESSION['usuario_id']     = $usuario['id'];      // usado por auditoría
            $_SESSION['usuario_codigo'] = $usuario['codigo'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_username']= $usuario['username'];

            header('Location: index.php?action=usuarios');
            exit;

        } else {
            // Autenticación fallida
            $error = 'Credenciales inválidas. Inténtalo de nuevo.';
            $this->secureTokenCsrf();
            $csrfToken = $_SESSION['csrf_token'];
            include __DIR__ . '/../views/auth/index.php';
            exit;
        }
    }

    /**
     * Cierra la sesión
     */
    public function logout(): void {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }

    /*
     * Métodos privados — manejo del token CSRF
    */

    private function secureTokenCsrf(): void {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    private function verifyTokenCsrf(): bool {
        $token = $_POST['csrf_token'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}