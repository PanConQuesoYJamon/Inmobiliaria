<?php

class ZonaModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todas las zonas
     * @return array
     * */
    public function getZonas(): array {
        $sql = "SELECT * FROM zonas ORDER BY nombre ASC";
        $resultado = $this->conexion->query($sql);

        $zonas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $zonas[] = $fila;
        }
        return $zonas;
    }

    /**
     * Obtener una zona por su ID
     * @param int $id
     * @return array|null
     */
    public function getZona(int $id): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM zonas WHERE id = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear una nueva zona
     * @param array $datos
     * @return bool
     */
    public function crearZona(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO zonas (nombre, municipio, departamento, estado)
             VALUES (?, ?, ?, 'A')"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "sss",
            $datos['nombre'],
            $datos['municipio'],
            $datos['departamento']
        );
        return $stmt->execute();
    }

    /**
     * Actualizar una zona
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarZona(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE zonas SET nombre = ?, municipio = ?, departamento = ?, estado = ?
             WHERE id = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "ssssi",
            $datos['nombre'],
            $datos['municipio'],
            $datos['departamento'],
            $datos['estado'],
            $id
        );
        return $stmt->execute();
    }

    /**
     * Eliminar una zona
     * @param int $id
     * @return bool
     */
    public function eliminarZona(int $id): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM zonas WHERE id = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
