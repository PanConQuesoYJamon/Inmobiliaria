<?php

class ContratoModel {
    private $conexion;

    public function getConexion() {
        return $this->conexion;
    }

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los contratos con sus relaciones
     * @return array
     */
    public function getContratos(): array {
        $sql = "SELECT c.*,
                       i.codigo   AS inmueble_codigo, i.descripcion AS inmueble_descripcion,
                       cl.nombres AS cliente_nombres, cl.apellidos  AS cliente_apellidos,
                       tc.nombre  AS tipo_contrato_nombre,
                       u.nombre   AS usuario_nombre,
                       u1.nombre  AS creado_por_nombre,
                       u2.nombre  AS modificado_por_nombre
                FROM contratos c
                LEFT JOIN inmuebles      i  ON i.id  = c.inmueble_id
                LEFT JOIN clientes       cl ON cl.id = c.cliente_id
                LEFT JOIN tipos_contrato tc ON tc.id = c.tipo_contrato_id
                LEFT JOIN usuarios       u  ON u.id  = c.usuario_id
                LEFT JOIN usuarios       u1 ON u1.id = c.creado_por
                LEFT JOIN usuarios       u2 ON u2.id = c.modificado_por
                ORDER BY c.numero_contrato ASC";

        $resultado = $this->conexion->query($sql);

        $contratos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $contratos[] = $fila;
        }
        return $contratos;
    }

    /**
     * Obtener un contrato por su numero
     * @param string $numeroContrato
     * @return array|null
     */
    public function getContrato(string $numeroContrato): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM contratos WHERE numero_contrato = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $numeroContrato);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo contrato
     * @param array $datos
     * @param int $usuarioId
     * @return bool
     */
    public function crearContrato(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO contratos 
                (numero_contrato, inmueble_id, cliente_id, tipo_contrato_id, usuario_id,
                 fecha_inicio, fecha_fin, monto_mensual, deposito, dia_pago,
                 observaciones, estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'A', ?, NOW())"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "siiiissddisi",
            $datos['numero_contrato'],
            $datos['inmueble_id'],
            $datos['cliente_id'],
            $datos['tipo_contrato_id'],
            $datos['usuario_id'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['monto_mensual'],
            $datos['deposito'],
            $datos['dia_pago'],
            $datos['observaciones'],
            $usuarioId
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un contrato
     * @param array $datos
     * @param string $numeroOriginal
     * @param int $usuarioId
     * @return bool
     */
    public function actualizarContrato(array $datos, string $numeroOriginal, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE contratos
             SET numero_contrato = ?, inmueble_id = ?, cliente_id = ?, tipo_contrato_id = ?,
                 usuario_id = ?, fecha_inicio = ?, fecha_fin = ?, monto_mensual = ?,
                 deposito = ?, dia_pago = ?, observaciones = ?, estado = ?,
                 modificado_por = ?, modificado_en = NOW()
             WHERE numero_contrato = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "siiiissddissis",
            $datos['numero_contrato'],
            $datos['inmueble_id'],
            $datos['cliente_id'],
            $datos['tipo_contrato_id'],
            $datos['usuario_id'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['monto_mensual'],
            $datos['deposito'],
            $datos['dia_pago'],
            $datos['observaciones'],
            $datos['estado'],
            $usuarioId,
            $numeroOriginal
        );
        return $stmt->execute();
    }

    /**
     * Eliminar un contrato
     * @param string $numeroContrato
     * @return bool
     */
    public function eliminarContrato(string $numeroContrato): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM contratos WHERE numero_contrato = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("s", $numeroContrato);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
