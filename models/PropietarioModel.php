<?php

class PropietarioModel {
    private $conexion;

    public function getConexion() {
        return $this->conexion;
    }

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los propietarios
     * @return array
     */
    public function getPropietarios(): array {
        $sql = "SELECT p.*, u1.nombre AS creado_por_nombre, u2.nombre AS modificado_por_nombre
                FROM propietarios p
                LEFT JOIN usuarios u1 ON u1.id = p.creado_por
                LEFT JOIN usuarios u2 ON u2.id = p.modificado_por
                ORDER BY p.codigo ASC";
        $resultado = $this->conexion->query($sql);

        $propietarios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $propietarios[] = $fila;
        }
        return $propietarios;
    }

    /**
     * Obtener un propietario por su codigo
     * @param string $codigo
     * @return array|null
     */
    public function getPropietario(string $codigo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM propietarios WHERE codigo = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo propietario
     * @param array $datos
     * @param int $usuarioId  ID del usuario en sesión
     * @return bool
     */
    public function crearPropietario(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO propietarios 
                (codigo, nombres, apellidos, dpi, nit, telefono, email, direccion, estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'A', ?, NOW())"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "ssssssssi",
            $datos['codigo'],
            $datos['nombres'],
            $datos['apellidos'],
            $datos['dpi'],
            $datos['nit'],
            $datos['telefono'],
            $datos['email'],
            $datos['direccion'],
            $usuarioId
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un propietario
     * @param array $datos
     * @param string $codigoOriginal
     * @param int $usuarioId  ID del usuario en sesión
     * @return bool
     */
    public function actualizarPropietario(array $datos, string $codigoOriginal, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE propietarios 
             SET codigo = ?, nombres = ?, apellidos = ?, dpi = ?, nit = ?,
                 telefono = ?, email = ?, direccion = ?, estado = ?,
                 modificado_por = ?, modificado_en = NOW()
             WHERE codigo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "sssssssssis",
            $datos['codigo'],
            $datos['nombres'],
            $datos['apellidos'],
            $datos['dpi'],
            $datos['nit'],
            $datos['telefono'],
            $datos['email'],
            $datos['direccion'],
            $datos['estado'],
            $usuarioId,
            $codigoOriginal
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un propietario
     * @param string $codigo
     * @return bool
     */
    public function eliminarPropietario(string $codigo): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM propietarios WHERE codigo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
