<?php

class UsuarioModel {
    private $conexion;

    public function __construct($p_conexion){
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los usuarios
     * @return array
     */
    public function getUsuarios(): array {
        $sql = "SELECT * FROM usuarios ORDER BY codigo ASC";
        $resultado = $this->conexion->query($sql);

        $usuarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }
        return $usuarios;
    }

    /**
     * Obtener un usuario por su codigo
     * @param string $codigo
     * @return array|null
     */
    public function getUsuario(string $codigo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuarios WHERE codigo = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Obtener un usuario por su username — usado por AuthController para el login
     * @param string $username
     * @return array|null
     */
    public function getUsuarioByUsername(string $username): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuarios WHERE username = ? AND estado = 'A' LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo usuario
     * @param array $datos
     * @return bool
     */
    public function crearUsuario(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO usuarios (codigo, nombre, username, clave, estado)
             VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) return false;

        $codigo   = $datos['codigo'];
        $nombre   = $datos['nombre'];
        $username = $datos['username'];
        $clave    = password_hash($datos['clave'], PASSWORD_DEFAULT); // siempre hasheada
        $estado   = 'A';

        $stmt->bind_param("sssss", $codigo, $nombre, $username, $clave, $estado);
        return $stmt->execute();
    }

    /**
     * Actualizar un usuario
     * @param array $datos
     * @param string $codigoOriginal
     * @return bool
     */
    public function actualizarUsuario(array $datos, string $codigoOriginal): bool {
        // Si viene clave vacía, no la actualiza
        if (!empty($datos['clave'])) {
            $stmt = $this->conexion->prepare(
                "UPDATE usuarios SET codigo = ?, nombre = ?, username = ?, clave = ?, estado = ?
                 WHERE codigo = ?"
            );

            if (!$stmt) return false;

            $clave = password_hash($datos['clave'], PASSWORD_DEFAULT);

            $stmt->bind_param(
                "ssssss",
                $datos['codigo'],
                $datos['nombre'],
                $datos['username'],
                $clave,
                $datos['estado'],
                $codigoOriginal
            );
        } else {
            // Actualiza todo menos la clave
            $stmt = $this->conexion->prepare(
                "UPDATE usuarios SET codigo = ?, nombre = ?, username = ?, estado = ?
                 WHERE codigo = ?"
            );

            if (!$stmt) return false;

            $stmt->bind_param(
                "sssss",
                $datos['codigo'],
                $datos['nombre'],
                $datos['username'],
                $datos['estado'],
                $codigoOriginal
            );
        }

        return $stmt->execute();
    }

    /**
     * Eliminar un usuario
     * @param string $codigo
     * @return bool
     */
    public function eliminarUsuario(string $codigo): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM usuarios WHERE codigo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}