<?php

require_once __DIR__ . '/../models/TipoContratoModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class TiposContratoController {

    private $modelo;

    public function __construct() {
        $conexion = (new Conexion)->conectar();
        $this->modelo = new TipoContratoModel($conexion);
    }

    /* -------------------------------------------------------
     * MÉTODOS DE PREPARACIÓN DE VISTA
     * ----------------------------------------------------- */

    public function index(): void {
        $tipos = $this->modelo->getTiposContrato();
        include __DIR__ . '/../views/tipos_contrato/listar_tipos_contrato.php';
    }

    public function new(): void {
        include __DIR__ . '/../views/tipos_contrato/new.php';
    }

    public function edit(): void {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=tipos_contrato');
            exit;
        }

        $tipo = $this->modelo->getTipoContrato($id);
        include __DIR__ . '/../views/tipos_contrato/edit.php';
    }

    /* -------------------------------------------------------
     * MÉTODOS DE ACCIÓN
     * ----------------------------------------------------- */

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=tipos_contrato');
            exit;
        }

        $datos = [
            'nombre'         => trim($_POST['nombre']         ?? ''),
            'duracion_meses' => (int)($_POST['duracion_meses'] ?? 0),
            'descripcion'    => trim($_POST['descripcion']    ?? '')
        ];

        $this->modelo->crearTipoContrato($datos);
        header('Location: index.php?action=tipos_contrato');
        exit;
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=tipos_contrato');
            exit;
        }

        $id = (int)trim($_POST['id'] ?? 0);

        $datos = [
            'nombre'         => trim($_POST['nombre']         ?? ''),
            'duracion_meses' => (int)($_POST['duracion_meses'] ?? 0),
            'descripcion'    => trim($_POST['descripcion']    ?? ''),
            'estado'         => trim($_POST['estado']         ?? 'A')
        ];

        $this->modelo->actualizarTipoContrato($datos, $id);
        header('Location: index.php?action=tipos_contrato');
        exit;
    }

    public function delete(): void {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=tipos_contrato');
            exit;
        }

        $this->modelo->eliminarTipoContrato($id);
        header('Location: index.php?action=tipos_contrato');
        exit;
    }
}
