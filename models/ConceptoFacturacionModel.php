<?php

class ConceptoFacturacionModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    public function getConexion() {
        return $this->conexion;
    }

    /**
     * Obtener todos los conceptos
     */
    public function getConceptos(): array {
        $sql = "SELECT * FROM conceptos_facturacion ORDER BY nombre ASC";
        $resultado = $this->conexion->query($sql);

        $conceptos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $conceptos[] = $fila;
        }
        return $conceptos;
    }

    /**
     * Obtener solo conceptos activos — para el select del formulario
     */
    public function getConceptosActivos(): array {
        $sql = "SELECT * FROM conceptos_facturacion WHERE estado = 'A' ORDER BY nombre ASC";
        $resultado = $this->conexion->query($sql);

        $conceptos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $conceptos[] = $fila;
        }
        return $conceptos;
    }

    /**
     * Obtener un concepto por su ID
     */
    public function getConcepto(int $id): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM conceptos_facturacion WHERE id = ? LIMIT 1"
        );
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo concepto
     */
    public function crearConcepto(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO conceptos_facturacion
                (nombre, descripcion, precio_base, aplica_impuesto, estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, 'A', ?, NOW())"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssdsi",
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio_base'],
            $datos['aplica_impuesto'],
            $usuarioId
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un concepto
     */
    public function actualizarConcepto(array $datos, int $id, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE conceptos_facturacion
             SET nombre = ?, descripcion = ?, precio_base = ?, aplica_impuesto = ?,
                 estado = ?, modificado_por = ?, modificado_en = NOW()
             WHERE id = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "ssdssii",
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio_base'],
            $datos['aplica_impuesto'],
            $datos['estado'],
            $usuarioId,
            $id
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un concepto
     */
    public function eliminarConcepto(int $id): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM conceptos_facturacion WHERE id = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
