<?php

class ClienteModel {
    private $conexion;

    public function getConexion() {
        return $this->conexion;
    }

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los clientes
     * @return array
     */
    public function getClientes(): array {
        $sql = "SELECT c.*, u1.nombre AS creado_por_nombre, u2.nombre AS modificado_por_nombre
                FROM clientes c
                LEFT JOIN usuarios u1 ON u1.id = c.creado_por
                LEFT JOIN usuarios u2 ON u2.id = c.modificado_por
                ORDER BY c.codigo ASC";
        $resultado = $this->conexion->query($sql);

        $clientes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $clientes[] = $fila;
        }
        return $clientes;
    }

    /**
     * Obtener un cliente por su codigo
     * @param string $codigo
     * @return array|null
     */
    public function getCliente(string $codigo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM clientes WHERE codigo = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo cliente
     * @param array $datos
     * @param int $usuarioId
     * @return bool
     */
    public function crearCliente(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO clientes 
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
     * Actualizar un cliente
     * @param array $datos
     * @param string $codigoOriginal
     * @param int $usuarioId
     * @return bool
     */
    public function actualizarCliente(array $datos, string $codigoOriginal, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE clientes 
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
     * Eliminar un cliente
     * @param string $codigo
     * @return bool
     */
    public function eliminarCliente(string $codigo): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM clientes WHERE codigo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
