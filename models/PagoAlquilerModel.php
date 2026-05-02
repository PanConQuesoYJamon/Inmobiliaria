<?php

class PagoAlquilerModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    public function getConexion() {
        return $this->conexion;
    }

    /**
     * Obtener todos los pagos con sus relaciones
     */
    public function getPagos(): array {
        $sql = "SELECT pa.*,
                       c.numero_contrato,
                       cl.nombres AS cliente_nombres, cl.apellidos AS cliente_apellidos,
                       i.codigo   AS inmueble_codigo,
                       u.nombre   AS usuario_nombre,
                       u1.nombre  AS creado_por_nombre,
                       f.numero_factura
                FROM pagos_alquiler pa
                LEFT JOIN contratos  c  ON c.id  = pa.contrato_id
                LEFT JOIN clientes   cl ON cl.id = c.cliente_id
                LEFT JOIN inmuebles  i  ON i.id  = c.inmueble_id
                LEFT JOIN usuarios   u  ON u.id  = pa.usuario_id
                LEFT JOIN usuarios   u1 ON u1.id = pa.creado_por
                LEFT JOIN facturas   f  ON f.id  = pa.factura_id
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
     */
    public function getPagosPorContrato(int $contratoId): array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM pagos_alquiler WHERE contrato_id = ? ORDER BY periodo_mes DESC"
        );
        if (!$stmt) return [];

        $stmt->bind_param("i", $contratoId);
        $stmt->execute();

        $pagos = [];
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $pagos[] = $fila;
        }
        return $pagos;
    }

    /**
     * Obtener un pago por su número de recibo
     */
    public function getPago(string $numeroRecibo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM pagos_alquiler WHERE numero_recibo = ? LIMIT 1"
        );
        if (!$stmt) return null;

        $stmt->bind_param("s", $numeroRecibo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Generar número de factura automático formato FAC-YYYY-NNNN
     */
    public function generarNumeroFactura(): string {
        $anio   = date('Y');
        $patron = "FAC-{$anio}-%";

        $stmt = $this->conexion->prepare(
            "SELECT COUNT(*) AS total FROM facturas WHERE numero_factura LIKE ?"
        );
        $stmt->bind_param("s", $patron);
        $stmt->execute();
        $resultado    = $stmt->get_result()->fetch_assoc();
        $consecutivo  = (int)($resultado['total'] ?? 0) + 1;

        return sprintf("FAC-%s-%04d", $anio, $consecutivo);
    }

    /**
     * Registrar un nuevo pago Y generar su factura automáticamente
     * Usa transacción para que todo o nada se guarde
     */
    public function crearPago(array $datos, ?int $usuarioId): bool {
        // Validar método de pago
        if (empty($datos['metodo_pago'])) {
            throw new Exception("Método de pago obligatorio");
        }

        // Validar duplicados
        $stmtCheck = $this->conexion->prepare(
            "SELECT COUNT(*) as total 
        FROM pagos_alquiler 
        WHERE contrato_id = ? AND periodo_mes = ? AND estado = 'A'"
        );
        $stmtCheck->bind_param("is", $datos['contrato_id'], $datos['periodo_mes']);
        $stmtCheck->execute();
        $res = $stmtCheck->get_result()->fetch_assoc();

        if ($res['total'] > 0) {
            throw new Exception("Este período ya fue pagado");
        }
        $this->conexion->begin_transaction();

        try {
            // 1. Generar número de factura
            $numeroFactura = $this->generarNumeroFactura();

            // 2. Calcular montos
            $datos['monto_pagado'] = round($datos['monto_pagado'], 2);
            $datos['mora'] = round($datos['mora'], 2);

            $subtotal = $datos['monto_pagado'];
            $total    = $datos['monto_pagado'] + $datos['mora'];

            if ($total < 0) {
                throw new Exception("El monto total no puede ser negativo");
            }

            // 3. Crear la factura
            $stmtFac = $this->conexion->prepare(
                "INSERT INTO facturas
                    (numero_factura, contrato_id, cliente_id, usuario_id,
                     fecha_emision, subtotal, impuesto, total,
                     observaciones, estado, creado_por, creado_en)
                 SELECT ?, c.id, c.cliente_id, ?,
                        ?, ?, 0, ?,
                        'Generada automáticamente desde pago de alquiler',
                        'emitida', ?, NOW()
                 FROM contratos c WHERE c.id = ?"
            );

            $stmtFac->bind_param(
                "sisddii",
                $numeroFactura,
                $datos['usuario_id'],
                $datos['fecha_pago'],
                $subtotal,
                $total,
                $usuarioId,
                $datos['contrato_id']
            );

            $stmtFac->execute();
            $facturaId = $this->conexion->insert_id;
            if ($facturaId <= 0) {
                throw new Exception("Error al generar factura");
            }

            // 4. Renglón de alquiler mensual
            $stmtDet = $this->conexion->prepare(
                "INSERT INTO detalle_factura
                    (factura_id, concepto, cantidad, precio_unitario, subtotal)
                 VALUES (?, 'Alquiler mensual', 1, ?, ?)"
            );
            $stmtDet->bind_param(
                "idd",
                $facturaId,
                $datos['monto_esperado'],
                $datos['monto_esperado']
            );
            $stmtDet->execute();

            // 5. Renglón de mora si aplica
            if ($datos['mora'] > 0) {
                $stmtMora = $this->conexion->prepare(
                    "INSERT INTO detalle_factura
                        (factura_id, concepto, cantidad, precio_unitario, subtotal)
                     VALUES (?, 'Mora por atraso', 1, ?, ?)"
                );
                $stmtMora->bind_param(
                    "idd",
                    $facturaId,
                    $datos['mora'],
                    $datos['mora']
                );
                $stmtMora->execute();
            }

            // 6. Pago de factura
            $stmtPagFac = $this->conexion->prepare(
                "INSERT INTO pagos_factura
                    (numero_recibo, factura_id, usuario_id, fecha_pago,
                     monto_pagado, metodo_pago, observaciones, estado,
                     creado_por, creado_en)
                 VALUES (?, ?, ?, ?, ?, ?, '', 'A', ?, NOW())"
            );
            $stmtPagFac->bind_param(
                "siisdsi",
                $datos['numero_recibo'],
                $facturaId,
                $datos['usuario_id'],
                $datos['fecha_pago'],
                $datos['monto_pagado'],
                $datos['metodo_pago'],
                $usuarioId
            );
            $stmtPagFac->execute();

            // 7. Pago de alquiler vinculado a la factura
            $stmtPag = $this->conexion->prepare(
                "INSERT INTO pagos_alquiler
                    (numero_recibo, contrato_id, usuario_id, fecha_pago, periodo_mes,
                     monto_esperado, monto_pagado, mora, metodo_pago, observaciones,
                     estado, factura_id, creado_por, creado_en)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'A', ?, ?, NOW())"
            );
            $stmtPag->bind_param(
                "siissdddssii",
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
                $facturaId,
                $usuarioId
            );
            $stmtPag->execute();

            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollback();
            die("Error: " . $e->getMessage());
        }
    }

    /**
     * Actualizar un pago
     */
    public function actualizarPago(array $datos, string $reciboOriginal, ?int $usuarioId): bool {
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
