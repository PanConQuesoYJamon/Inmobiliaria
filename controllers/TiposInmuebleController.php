<?php

require_once __DIR__ . '/../models/TipoInmuebleModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class TiposInmuebleController {

    private $modelo;

    public function __construct() {
        $conexion = (new Conexion)->conectar();
        $this->modelo = new TipoInmuebleModel($conexion);
    }

    /* -------------------------------------------------------
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * ----------------------------------------------------- */

    public function index(): void {
        $tipos = $this->modelo->getTiposInmueble();
        include __DIR__ . '/../views/tipos_inmueble/listar_tipos_inmueble.php';
    }

    public function new(): void {
        include __DIR__ . '/../views/tipos_inmueble/new.php';
    }

    public function edit(): void {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=tipos_inmueble');
            exit;
        }

        $tipo = $this->modelo->getTipoInmueble($id);
        include __DIR__ . '/../views/tipos_inmueble/edit.php';
    }

    /* -------------------------------------------------------
     * MÉTODOS DE ACCIÓN
     * ----------------------------------------------------- */

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=tipos_inmueble');
            exit;
        }

        $datos = [
            'nombre'      => trim($_POST['nombre']      ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        $this->modelo->crearTipoInmueble($datos);
        header('Location: index.php?action=tipos_inmueble');
        exit;
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=tipos_inmueble');
            exit;
        }

        $id = (int)trim($_POST['id'] ?? 0);

        $datos = [
            'nombre'      => trim($_POST['nombre']      ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'estado'      => trim($_POST['estado']      ?? 'A')
        ];

        $this->modelo->actualizarTipoInmueble($datos, $id);
        header('Location: index.php?action=tipos_inmueble');
        exit;
    }

    public function delete(): void {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=tipos_inmueble');
            exit;
        }

        $this->modelo->eliminarTipoInmueble($id);
        header('Location: index.php?action=tipos_inmueble');
        exit;
    }
}
