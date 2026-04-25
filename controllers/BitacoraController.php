<?php

require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class BitacoraController {

    private $modelo;

    public function __construct() {
        $conexion = (new Conexion)->conectar();
        $this->modelo = new BitacoraModel($conexion);
    }

    /* -------------------------------------------------------
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * La bitácora es solo de lectura, no permite
     * crear, editar ni eliminar registros desde la vista.
     * Los registros se insertan desde los demás controllers.
     * ----------------------------------------------------- */

    /**
     * INDEX - muestra todos los registros de auditoría
     */
    public function index(): void {
        $registros = $this->modelo->getBitacora();
        include __DIR__ . '/../views/bitacora/listar_bitacora.php';
    }

    /**
     * FILTRAR - muestra registros de una tabla específica
     */
    public function filtrar(): void {
        $tabla     = $_GET['tabla'] ?? '';
        $registros = $tabla
            ? $this->modelo->getBitacoraPorTabla($tabla)
            : $this->modelo->getBitacora();

        include __DIR__ . '/../views/bitacora/listar_bitacora.php';
    }
}
