<?php

require_once __DIR__ . '/../models/ZonaModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class ZonasController {

    private $modelo;

    public function __construct() {
        $conexion = (new Conexion)->conectar();
        $this->modelo = new ZonaModel($conexion);
    }

    /* 
     * MÉTODOS DE PREPARACIÓN DE VISTA
     *  */

    public function index(): void {
        $zonas = $this->modelo->getZonas();
        include __DIR__ . '/../views/zonas/listar_zonas.php';
    }

    public function new(): void {
        include __DIR__ . '/../views/zonas/new.php';
    }

    public function edit(): void {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=zonas');
            exit;
        }

        $zona = $this->modelo->getZona($id);
        include __DIR__ . '/../views/zonas/edit.php';
    }

    /* 
     * MÉTODOS DE ACCIÓN
     *  */

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=zonas');
            exit;
        }

        $datos = [
            'nombre'       => trim($_POST['nombre']       ?? ''),
            'municipio'    => trim($_POST['municipio']    ?? ''),
            'departamento' => trim($_POST['departamento'] ?? '')
        ];

        $this->modelo->crearZona($datos);
        header('Location: index.php?action=zonas');
        exit;
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=zonas');
            exit;
        }

        $id = (int)trim($_POST['id'] ?? 0);

        $datos = [
            'nombre'       => trim($_POST['nombre']       ?? ''),
            'municipio'    => trim($_POST['municipio']    ?? ''),
            'departamento' => trim($_POST['departamento'] ?? ''),
            'estado'       => trim($_POST['estado']       ?? 'A')
        ];

        $this->modelo->actualizarZona($datos, $id);
        header('Location: index.php?action=zonas');
        exit;
    }

    public function delete(): void {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=zonas');
            exit;
        }

        $this->modelo->eliminarZona($id);
        header('Location: index.php?action=zonas');
        exit;
    }
}
