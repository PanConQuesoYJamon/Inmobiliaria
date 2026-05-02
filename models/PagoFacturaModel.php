<?php

class PagoFacturaModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    public function getConexion() {
        return $this->conexion;
    }

    /**
     * Obtener todos los pagos de una factura
     */
    public function getPagosPorFactura(int $facturaId): array {
        $stmt = $this->conexion->prepare(
            "SELECT p.*, u.nombre AS usuario_nombre
             FROM pagos_factura p
             LEFT JOIN usuarios u ON u.id = p.usuario_id
             WHERE p.factura_id = ?
             ORDER BY p.fecha_pago DESC"
        );
        if (!$stmt) return [];

        $stmt->bind_param("i", $facturaId);
        $stmt->execute();

        $pagos = [];
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $pagos[] = $fila;
        }
        return $pagos;
    }

    /**
     * Obtener todos los pagos del sistema
     */
    public function getPagos(): array {
        $sql = "SELECT p.*,
                       f.numero_factura,
                       u.nombre AS usuario_nombre,
                       cl.nombres AS cliente_nombres, cl.apellidos AS cliente_apellidos
                FROM pagos_factura p
                LEFT JOIN facturas  f  ON f.id  = p.factura_id
                LEFT JOIN clientes  cl ON cl.id = f.cliente_id
                LEFT JOIN usuarios  u  ON u.id  = p.usuario_id
                ORDER BY p.fecha_pago DESC";

        $resultado = $this->conexion->query($sql);
        $pagos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $pagos[] = $fila;
        }
        return $pagos;
    }

    /**
     * Obtener un pago por número de recibo
     */
    public function getPago(string $numeroRecibo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM pagos_factura WHERE numero_recibo = ? LIMIT 1"
        );
        if (!$stmt) return null;

        $stmt->bind_param("s", $numeroRecibo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Registrar un pago de factura
     */
    public function crearPago(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO pagos_factura
                (numero_recibo, factura_id, usuario_id, fecha_pago, monto_pagado,
                 metodo_pago, referencia, observaciones, estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'A', ?, NOW())"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "siisdsssi",
            $datos['numero_recibo'],
            $datos['factura_id'],
            $datos['usuario_id'],
            $datos['fecha_pago'],
            $datos['monto_pagado'],
            $datos['metodo_pago'],
            $datos['referencia'],
            $datos['observaciones'],
            $usuarioId
        );
        return $stmt->execute();
    }

    /**
     * Anular un pago
     */
    public function anularPago(string $numeroRecibo, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE pagos_factura
             SET estado = 'I', modificado_por = ?, modificado_en = NOW()
             WHERE numero_recibo = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("is", $usuarioId, $numeroRecibo);
        return $stmt->execute();
    }

    /**
     * Obtener total pagado de una factura
     */
    public function getTotalPagado(int $facturaId): float {
        $stmt = $this->conexion->prepare(
            "SELECT COALESCE(SUM(monto_pagado), 0) AS total
             FROM pagos_factura
             WHERE factura_id = ? AND estado = 'A'"
        );
        if (!$stmt) return 0.0;

        $stmt->bind_param("i", $facturaId);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return (float)($resultado['total'] ?? 0);
    }
}
