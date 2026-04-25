<?php

class TipoInmuebleModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los tipos de inmueble
     * @return array
     */
    public function getTiposInmueble(): array {
        $sql = "SELECT * FROM tipos_inmueble ORDER BY nombre ASC";
        $resultado = $this->conexion->query($sql);

        $tipos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $tipos[] = $fila;
        }
        return $tipos;
    }

    /**
     * Obtener un tipo de inmueble por su ID
     * @param int $id
     * @return array|null
     */
    public function getTipoInmueble(int $id): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM tipos_inmueble WHERE id = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo tipo de inmueble
     * @param array $datos
     * @return bool
     */
    public function crearTipoInmueble(array $datos): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO tipos_inmueble (nombre, descripcion, estado)
             VALUES (?, ?, 'A')"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "ss",
            $datos['nombre'],
            $datos['descripcion']
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un tipo de inmueble
     * @param array $datos
     * @param int $id
     * @return bool
     */
    public function actualizarTipoInmueble(array $datos, int $id): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE tipos_inmueble SET nombre = ?, descripcion = ?, estado = ?
             WHERE id = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "sssi",
            $datos['nombre'],
            $datos['descripcion'],
            $datos['estado'],
            $id
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un tipo de inmueble
     * @param int $id
     * @return bool
     */
    public function eliminarTipoInmueble(int $id): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM tipos_inmueble WHERE id = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
