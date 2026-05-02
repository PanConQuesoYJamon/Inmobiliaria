<?php

class InmuebleModel {
    private $conexion;

    public function getConexion() {
        return $this->conexion;
    }
    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los inmuebles con sus relaciones
     * @return array
     */
    public function getInmuebles(): array {
        $sql = "SELECT i.*,
                       p.nombres AS propietario_nombres, p.apellidos AS propietario_apellidos,
                       t.nombre  AS tipo_nombre,
                       z.nombre  AS zona_nombre,
                     u1.nombre AS creado_por_nombre,
                    u2.nombre AS modificado_por_nombre
                FROM inmuebles i
                LEFT JOIN propietarios  p  ON p.id  = i.propietario_id
                LEFT JOIN tipos_inmueble t ON t.id  = i.tipo_inmueble_id
                LEFT JOIN zonas          z  ON z.id  = i.zona_id
                LEFT JOIN usuarios       u1 ON u1.id = i.creado_por
                LEFT JOIN usuarios       u2 ON u2.id = i.modificado_por
                WHERE i.estado = 'A'  -- 👈 ESTA ES LA CLAVE
                ORDER BY i.codigo ASC";

        $resultado = $this->conexion->query($sql);

        $inmuebles = [];
        while ($fila = $resultado->fetch_assoc()) {
            $inmuebles[] = $fila;
        }
        return $inmuebles;
    }

    /**
     * Obtener un inmueble por su codigo
     * @param string $codigo
     * @return array|null
     */
    public function getInmueble(string $codigo): ?array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM inmuebles WHERE codigo = ? LIMIT 1"
        );

        if (!$stmt) return null;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc() ?: null;
    }

    /**
     * Crear un nuevo inmueble
     * @param array $datos
     * @param int $usuarioId
     * @return bool
     */
    public function crearInmueble(array $datos, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "INSERT INTO inmuebles 
                (codigo, propietario_id, tipo_inmueble_id, zona_id, descripcion,
                 precio_alquiler, habitaciones, banos, metros_cuadrados, direccion,
                 estado, creado_por, creado_en)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'A', ?, NOW())"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "siiiisdddsi",
            $datos['codigo'],
            $datos['propietario_id'],
            $datos['tipo_inmueble_id'],
            $datos['zona_id'],
            $datos['descripcion'],
            $datos['precio_alquiler'],
            $datos['habitaciones'],
            $datos['banos'],
            $datos['metros_cuadrados'],
            $datos['direccion'],
            $usuarioId
        );
        return $stmt->execute();
    }

    /**
     * Actualizar un inmueble
     * @param array $datos
     * @param string $codigoOriginal
     * @param int $usuarioId
     * @return bool
     */
    public function actualizarInmueble(array $datos, string $codigoOriginal, int $usuarioId): bool {
        $stmt = $this->conexion->prepare(
            "UPDATE inmuebles
             SET codigo = ?, propietario_id = ?, tipo_inmueble_id = ?, zona_id = ?,
                 descripcion = ?, precio_alquiler = ?, habitaciones = ?, banos = ?,
                 metros_cuadrados = ?, direccion = ?, estado = ?,
                 modificado_por = ?, modificado_en = NOW()
             WHERE codigo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "siiisdddsisss",
            $datos['codigo'],
            $datos['propietario_id'],
            $datos['tipo_inmueble_id'],
            $datos['zona_id'],
            $datos['descripcion'],
            $datos['precio_alquiler'],
            $datos['habitaciones'],
            $datos['banos'],
            $datos['metros_cuadrados'],
            $datos['direccion'],
            $datos['estado'],
            $usuarioId,
            $codigoOriginal
        );
        return $stmt->execute();
    }


    public function tieneContratos(int $inmuebleId): bool {
        $stmt = $this->conexion->prepare(
            "SELECT COUNT(*) as total FROM contratos WHERE inmueble_id = ?"
        );

        $stmt->bind_param("i", $inmuebleId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        return $res['total'] > 0;
    }

    /**
     * Eliminar un inmueble
     * @param string $codigo
     * @return bool
     */
    public function eliminarInmueble(string $codigo): bool {
        $stmt = $this->conexion->prepare(
            "DELETE FROM inmuebles WHERE codigo = ?"
        );

        if (!$stmt) return false;

        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
