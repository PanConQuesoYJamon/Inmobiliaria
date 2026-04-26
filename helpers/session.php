<?php

class SessionHelper {

    public function __construct() {}

    public function verficarSession(): void {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    // Verifica que sea admin
    public function soloAdmin(): void {
        $this->verficarSession();
        if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
            header('Location: index.php?action=usuarios');
            exit;
        }
    }

    // Verifica que sea admin o supervisor
    public function adminOSupervisor(): void {
        $this->verficarSession();
        $rol = $_SESSION['usuario_rol'] ?? '';
        if (!in_array($rol, ['admin', 'supervisor'])) {
            header('Location: index.php?action=clientes');
            exit;
        }
    }

    // Devuelve el rol actual — útil para las vistas
    public function getRol(): string {
        return $_SESSION['usuario_rol'] ?? '';
    }
}