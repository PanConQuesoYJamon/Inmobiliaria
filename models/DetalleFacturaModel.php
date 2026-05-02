<?php

class DetalleFacturaModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    public function getConexion() {
        return $this->conexion;
    }

    /**
     * Obtener todos los detalles de una factura
     */
    public function getDetallesPorFactura(int $facturaId): array {
        $stmt = $this->conexion->prepare(
            "SELECT d.*, c.nombre AS concepto_nombre
             FROM detalle_factura d
             LEFT JOIN conceptos_facturacion c ON c.id = d.concepto_id
             WHERE d.factura_id = ?
             ORDER BY d.id ASC"
        );
        if (!$stmt) return [];

        $stmt->bind_param("i", $facturaId);
        $stmt->execute();

        $detalles = [];
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $detalles[] = $fila;
        }
        return $detalles;
    }

    /**
     * Obtener un detalle por su ID
     */
    public function getDetalle(int $id): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM detalle_factura WHERE id = ? LIMIT 1"
        );
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Agregar un renglón a la factura
     */
    public function crearDetalle(array $datos): bool {
        $subtotal = $datos['cantidad'] * $datos['precio_unitario'];

        $stmt = $this->conexion->prepare(
            "INSERT INTO detalle_factura
                (factura_id, concepto_id, concepto, cantidad, precio_unitario, subtotal)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "iisddd",
            $datos['factura_id'],
            $datos['concepto_id'],
            $datos['concepto'],
            $datos['cantidad'],
            $datos['precio_unitario'],
            $subtotal
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un renglón
     */
    public function actualizarDetalle(array $datos, int $id): bool {
        $subtotal = $datos['cantidad'] * $datos['precio_unitario'];

        $stmt = $this->conexion->prepare(
            "UPDATE detalle_factura
             SET concepto_id = ?, concepto = ?, cantidad = ?, precio_unitario = ?, subtotal = ?
             WHERE id = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "isdddі",
            $datos['concepto_id'],
            $datos['concepto'],
            $datos['cantidad'],
            $datos['precio_unitario'],
            $subtotal,
            $id
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un renglón
     */
    public function eliminarDetalle(int $id): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM detalle_factura WHERE id = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Eliminar todos los detalles de una factura
     */
    public function eliminarDetallesPorFactura(int $facturaId): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM detalle_factura WHERE factura_id = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("i", $facturaId);
        return $stmt->execute();
    }
}
