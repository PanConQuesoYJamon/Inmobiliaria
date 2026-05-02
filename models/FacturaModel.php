<?php

class FacturaModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    public function getConexion() {
        return $this->conexion;
    }

    /**
     * Obtener todas las facturas con sus relaciones
     */
    public function getFacturas(): array {
        $sql = "SELECT f.*,
                       c.numero_contrato,
                       cl.nombres AS cliente_nombres, cl.apellidos AS cliente_apellidos,
                       u.nombre   AS usuario_nombre,
                       u1.nombre  AS creado_por_nombre
                FROM facturas f
                LEFT JOIN contratos c  ON c.id  = f.contrato_id
                LEFT JOIN clientes  cl ON cl.id = f.cliente_id
                LEFT JOIN usuarios  u  ON u.id  = f.usuario_id
                LEFT JOIN usuarios  u1 ON u1.id = f.creado_por
                ORDER BY f.fecha_emision DESC";

        $resultado = $this->conexion->query($sql);
        $facturas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $facturas[] = $fila;
        }
        return $facturas;
    }

    /**
     * Obtener una factura por su número
     */
    public function getFactura(string $numeroFactura): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT f.*,
                    c.numero_contrato,
                    cl.nombres AS cliente_nombres, cl.apellidos AS cliente_apellidos,
                    cl.dpi, cl.nit, cl.direccion AS cliente_direccion
             FROM facturas f
             LEFT JOIN contratos c  ON c.id  = f.contrato_id
             LEFT JOIN clientes  cl ON cl.id = f.cliente_id
             WHERE f.numero_factura = ? LIMIT 1"
        );
        if (!$stmt) return null;

        $stmt->bind_param("s", $numeroFactura);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Crear una nueva factura
     */
    public function crearFactura(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO facturas
                (numero_factura, contrato_id, cliente_id, usuario_id,
                 fecha_emision, fecha_vencimiento, subtotal, impuesto, total,
                 observaciones, estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'borrador', ?, NOW())"
        );
        if (!$stmt) return false;

        $numeroFactura   = $datos['numero_factura'];
        $contratoId      = (int)$datos['contrato_id'];
        $clienteId       = (int)$datos['cliente_id'];
        $usuarioIdForm   = (int)$datos['usuario_id'];
        $fechaEmision    = $datos['fecha_emision'];
        $fechaVencimiento= $datos['fecha_vencimiento'];
        $subtotal        = (float)$datos['subtotal'];
        $impuesto        = (float)$datos['impuesto'];
        $total           = (float)$datos['total'];
        $observaciones   = $datos['observaciones'];
        
        $stmt->bind_param(
            "siiissdddsi",
            $numeroFactura,
            $contratoId,
            $clienteId,
            $usuarioIdForm,
            $fechaEmision,
            $fechaVencimiento,
            $subtotal,
            $impuesto,
            $total,
            $observaciones,
            $usuarioId
        );

        return $stmt->execute();
    }

    /**
     * Actualizar una factura
     */
    public function actualizarFactura(array $datos, string $numeroOriginal, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE facturas
             SET numero_factura = ?, contrato_id = ?, cliente_id = ?, usuario_id = ?,
                 fecha_emision = ?, fecha_vencimiento = ?, subtotal = ?, impuesto = ?,
                 total = ?, observaciones = ?, estado = ?,
                 modificado_por = ?, modificado_en = NOW()
             WHERE numero_factura = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param(
            "siiissdddssis",
            $datos['numero_factura'],
            $datos['contrato_id'],
            $datos['cliente_id'],
            $datos['usuario_id'],
            $datos['fecha_emision'],
            $datos['fecha_vencimiento'],
            $datos['subtotal'],
            $datos['impuesto'],
            $datos['total'],
            $datos['observaciones'],
            $datos['estado'],
            $usuarioId,
            $numeroOriginal
        );
        return $stmt->execute();
    }

    /**
     * Eliminar una factura
     */
    public function eliminarFactura(string $numeroFactura): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM facturas WHERE numero_factura = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("s", $numeroFactura);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Recalcular y actualizar totales de la factura
     */
    public function recalcularTotales(int $facturaId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE facturas f
             SET subtotal = (
                     SELECT COALESCE(SUM(d.subtotal), 0)
                     FROM detalle_factura d
                     WHERE d.factura_id = f.id
                 ),
                 total = subtotal + impuesto
             WHERE f.id = ?"
        );
        if (!$stmt) return false;

        $stmt->bind_param("i", $facturaId);
        return $stmt->execute();
    }
}
