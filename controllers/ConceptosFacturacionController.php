<?php

require_once __DIR__ . '/../models/ConceptoFacturacionModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class ConceptosFacturacionController {

    private $modelo;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion            = (new Conexion)->conectar();
        $this->modelo        = new ConceptoFacturacionModel($conexion);
        $this->bitacora      = new BitacoraModel($conexion);
        $this->sessionHelper = new SessionHelper();
    }

    public function index(): void {
        $this->sessionHelper->adminOSupervisor();
        $conceptos = $this->modelo->getConceptos();
        include __DIR__ . '/../views/conceptos_facturacion/listar_conceptos.php';
    }

    public function new(): void {
        $this->sessionHelper->adminOSupervisor();
        include __DIR__ . '/../views/conceptos_facturacion/new.php';
    }

    public function edit(): void {
        $this->sessionHelper->adminOSupervisor();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=conceptos_facturacion');
            exit;
        }
        $concepto = $this->modelo->getConcepto($id);
        include __DIR__ . '/../views/conceptos_facturacion/edit.php';
    }

    public function create(): void {
        $this->sessionHelper->adminOSupervisor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=conceptos_facturacion');
            exit;
        }

        $datos = [
            'nombre'          => trim($_POST['nombre']          ?? ''),
            'descripcion'     => trim($_POST['descripcion']     ?? ''),
            'precio_base'     => (float)($_POST['precio_base']  ?? 0),
            'aplica_impuesto' => trim($_POST['aplica_impuesto'] ?? 'N')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $resultado = $this->modelo->crearConcepto($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'conceptos_facturacion',
                $this->modelo->getConexion()->insert_id,
                'INSERT', '',
                json_encode($datos, JSON_UNESCAPED_UNICODE),
                $usuarioId
            );
        }

        header('Location: index.php?action=conceptos_facturacion');
        exit;
    }

    public function update(): void {
        $this->sessionHelper->adminOSupervisor();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=conceptos_facturacion');
            exit;
        }

        $id        = (int)trim($_POST['id'] ?? 0);
        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $antes     = $this->modelo->getConcepto($id);

        $datos = [
            'nombre'          => trim($_POST['nombre']          ?? ''),
            'descripcion'     => trim($_POST['descripcion']     ?? ''),
            'precio_base'     => (float)($_POST['precio_base']  ?? 0),
            'aplica_impuesto' => trim($_POST['aplica_impuesto'] ?? 'N'),
            'estado'          => trim($_POST['estado']          ?? 'A')
        ];

        $resultado = $this->modelo->actualizarConcepto($datos, $id, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'conceptos_facturacion', $id, 'UPDATE',
                json_encode($antes, JSON_UNESCAPED_UNICODE),
                json_encode($datos, JSON_UNESCAPED_UNICODE),
                $usuarioId
            );
        }

        header('Location: index.php?action=conceptos_facturacion');
        exit;
    }

    public function delete(): void {
        $this->sessionHelper->soloAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            header('Location: index.php?action=conceptos_facturacion');
            exit;
        }

        $antes     = $this->modelo->getConcepto($id);
        $usuarioId = $_SESSION['usuario_id'] ?? 0;
        $resultado = $this->modelo->eliminarConcepto($id);

        if ($resultado) {
            $this->bitacora->registrar(
                'conceptos_facturacion', $id, 'DELETE',
                json_encode($antes, JSON_UNESCAPED_UNICODE),
                '', $usuarioId
            );
        }

        header('Location: index.php?action=conceptos_facturacion');
        exit;
    }
}