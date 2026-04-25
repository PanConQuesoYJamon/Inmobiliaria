<?php

class TipoContratoModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los tipos de contrato
     * @return array
     */
    public function getTiposContrato(): array {
        $sql = "SELECT * FROM tipos_contrato ORDER BY nombre ASC";
        $resultado = $this->conexion->query($sql);

        $tipos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $tipos[] = $fila;
        }
        return $tipos;
    }

    /**
     * Obtener un tipo de contrato por su ID
     * @param int $id
     * @return array|null
     */
    public function getTipoContrato(int $id): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM tipos_contrato WHERE id = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo tipo de contrato
     * @param array $datos
     * @return bool
     */
    public function crearTipoContrato(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO tipos_contrato (nombre, duracion_meses, descripcion, estado)
             VALUES (?, ?, ?, 'A')"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "sis",
            $datos['nombre'],
            $datos['duracion_meses'],
            $datos['descripcion']
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un tipo de contrato
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarTipoContrato(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE tipos_contrato SET nombre = ?, duracion_meses = ?, descripcion = ?, estado = ?
             WHERE id = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "sissi",
            $datos['nombre'],
            $datos['duracion_meses'],
            $datos['descripcion'],
            $datos['estado'],
            $id
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un tipo de contrato
     * @param int $id
     * @return bool
     */
    public function eliminarTipoContrato(int $id): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM tipos_contrato WHERE id = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
