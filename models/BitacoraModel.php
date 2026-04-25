<?php

class BitacoraModel {
    private $conexion;

    public function __construct($p_conexion) {
        $this->conexion = $p_conexion;
    }

    /**
     * Obtener todos los registros de bitácora con nombre de usuario
     * @return array
     */
    public function getBitacora(): array {
        $sql = "SELECT b.*, u.nombre AS usuario_nombre
                FROM bitacora b
                LEFT JOIN usuarios u ON u.id = b.usuario_id
                ORDER BY b.fecha_hora DESC";

        $resultado = $this->conexion->query($sql);

        $registros = [];
        while ($fila = $resultado->fetch_assoc()) {
            $registros[] = $fila;
        }
        return $registros;
    }

    /**
     * Obtener bitácora filtrada por tabla
     * @param string $tabla
     * @return array
     */
    public function getBitacoraPorTabla(string $tabla): array {
        $stmt = $this->conexion->prepare(
            "SELECT b.*, u.nombre AS usuario_nombre
             FROM bitacora b
             LEFT JOIN usuarios u ON u.id = b.usuario_id
             WHERE b.tabla_afectada = ?
             ORDER BY b.fecha_hora DESC"
        );

        if (!$stmt) return [];

        $stmt->bind_param("s", $tabla);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registros = [];
        while ($fila = $resultado->fetch_assoc()) {
            $registros[] = $fila;
        }
        return $registros;
    }

    /**
     * Registrar una nueva entrada en la bitácora
     * @param string $tabla
     * @param int    $registroId
     * @param string $accion     INSERT | UPDATE | DELETE
     * @param string $datosAntes
     * @param string $datosDespues
     * @param int    $usuarioId
     * @return bool
     */
    public function registrar(
        string $tabla,
        int    $registroId,
        string $accion,
        string $datosAntes,
        string $datosDespues,
        int    $usuarioId
    ): bool {
        $ip   = $_SERVER['REMOTE_ADDR'] ?? null;
        $stmt = $this->conexion->prepare(
            "INSERT INTO bitacora 
                (tabla_afectada, registro_id, accion, datos_antes, datos_despues,
                 usuario_id, fecha_hora, ip_usuario)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)"
        );

        if (!$stmt) return false;

        $stmt->bind_param(
            "sisssis",
            $tabla,
            $registroId,
            $accion,
            $datosAntes,
            $datosDespues,
            $usuarioId,
            $ip
        );
        return $stmt->execute();
    }
}
