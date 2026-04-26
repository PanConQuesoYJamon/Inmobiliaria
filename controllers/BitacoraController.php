<?php

require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class BitacoraController {

    private $modelo;
    private $sessionHelper;

    public function __construct() {
        $conexion = (new Conexion)->conectar();
        $this->modelo = new BitacoraModel($conexion);
        $this->sessionHelper = new SessionHelper();
    }

    /* 
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * La bitácora es solo de lectura, no permite
     * crear, editar ni eliminar registros desde la vista.
     * Los registros se insertan desde los demás controllers.
     *  */

    /**
     * INDEX - muestra todos los registros de auditoría
     */
    public function index(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->adminOSupervisor();
        $registros = $this->modelo->getBitacora();
        include __DIR__ . '/../views/bitacora/listar_bitacora.php';
    }

    /**
     * FILTRAR - muestra registros de una tabla específica
     */
    public function filtrar(): void {
        $this->sessionHelper->verficarSession();
        $this->sessionHelper->adminOSupervisor();
        $tabla     = $_GET['tabla'] ?? '';
        $registros = $tabla
            ? $this->modelo->getBitacoraPorTabla($tabla)
            : $this->modelo->getBitacora();

        include __DIR__ . '/../views/bitacora/listar_bitacora.php';
    }
}
