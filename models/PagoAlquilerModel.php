<?php

class PagoAlquilerModel {
    private $conexion;

    public function getConexion() {
        return $this->conexion;
    }

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los pagos con sus relaciones
     * @return array
     */
    public function getPagos(): array {
        $sql = "SELECT pa.*,
                       c.numero_contrato,
                       cl.nombres AS cliente_nombres, cl.apellidos AS cliente_apellidos,
                       i.codigo   AS inmueble_codigo,
                       u.nombre   AS usuario_nombre,
                       u1.nombre  AS creado_por_nombre,
                       u2.nombre  AS modificado_por_nombre
                FROM pagos_alquiler pa
                LEFT JOIN contratos  c  ON c.id  = pa.contrato_id
                LEFT JOIN clientes   cl ON cl.id = c.cliente_id
                LEFT JOIN inmuebles  i  ON i.id  = c.inmueble_id
                LEFT JOIN usuarios   u  ON u.id  = pa.usuario_id
                LEFT JOIN usuarios   u1 ON u1.id = pa.creado_por
                LEFT JOIN usuarios   u2 ON u2.id = pa.modificado_por
                ORDER BY pa.fecha_pago DESC";

        $resultado = $this->conexion->query($sql);

        $pagos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $pagos[] = $fila;
        }
        return $pagos;
    }

    /**
     * Obtener pagos filtrados por contrato
     * @param int $contratoId
     * @return array
     */
    public function getPagosPorContrato(int $contratoId): array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM pagos_alquiler WHERE contrato_id = ? ORDER BY periodo_mes DESC"
        );

        if (!$stmt) return [];

        $stmt->bind_param("i", $contratoId);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $pagos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $pagos[] = $fila;
        }
        return $pagos;
    }

    /**
     * Obtener un pago por su numero de recibo
     * @param string $numeroRecibo
     * @return array|null
     */
    public function getPago(string $numeroRecibo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM pagos_alquiler WHERE numero_recibo = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $numeroRecibo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Registrar un nuevo pago
     * @param array $datos
     * @param int $usuarioId
     * @return bool
     */
    public function crearPago(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO pagos_alquiler 
                (numero_recibo, contrato_id, usuario_id, fecha_pago, periodo_mes,
                 monto_esperado, monto_pagado, mora, metodo_pago, observaciones,
                 estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'A', ?, NOW())"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "siissdddssi",
            $datos['numero_recibo'],
            $datos['contrato_id'],
            $datos['usuario_id'],
            $datos['fecha_pago'],
            $datos['periodo_mes'],
            $datos['monto_esperado'],
            $datos['monto_pagado'],
            $datos['mora'],
            $datos['metodo_pago'],
            $datos['observaciones'],
            $usuarioId
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un pago
     * @param array $datos
     * @param string $reciboOriginal
     * @param int $usuarioId
     * @return bool
     */
    public function actualizarPago(array $datos, string $reciboOriginal, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE pagos_alquiler
             SET numero_recibo = ?, contrato_id = ?, usuario_id = ?, fecha_pago = ?,
                 periodo_mes = ?, monto_esperado = ?, monto_pagado = ?, mora = ?,
                 metodo_pago = ?, observaciones = ?, estado = ?,
                 modificado_por = ?, modificado_en = NOW()
             WHERE numero_recibo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "siissdddsssis",
            $datos['numero_recibo'],
            $datos['contrato_id'],
            $datos['usuario_id'],
            $datos['fecha_pago'],
            $datos['periodo_mes'],
            $datos['monto_esperado'],
            $datos['monto_pagado'],
            $datos['mora'],
            $datos['metodo_pago'],
            $datos['observaciones'],
            $datos['estado'],
            $usuarioId,
            $reciboOriginal
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un pago
     * @param string $numeroRecibo
     * @return bool
     */
    public function eliminarPago(string $numeroRecibo): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM pagos_alquiler WHERE numero_recibo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("s", $numeroRecibo);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
