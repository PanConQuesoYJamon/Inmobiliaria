<?php

require_once __DIR__ . '/../models/InmuebleModel.php';
require_once __DIR__ . '/../models/PropietarioModel.php';
require_once __DIR__ . '/../models/TipoInmuebleModel.php';
require_once __DIR__ . '/../models/ZonaModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';
require_once __DIR__ . '/../models/Conexion.php';
require_once __DIR__ . '/../helpers/session.php';

class InmueblesController {

    private $modelo;
    private $modeloPropietarios;
    private $modeloTipos;
    private $modeloZonas;
    private $bitacora;
    private $sessionHelper;

    public function __construct() {
        $conexion                 = (new Conexion)->conectar();
        $this->modelo             = new InmuebleModel($conexion);
        $this->modeloPropietarios = new PropietarioModel($conexion);
        $this->modeloTipos        = new TipoInmuebleModel($conexion);
        $this->modeloZonas        = new ZonaModel($conexion);
        $this->bitacora           = new BitacoraModel($conexion);
        $this->sessionHelper      = new SessionHelper();
    }

    public function index(): void {
        $this->sessionHelper->verficarSession();
        $inmuebles = $this->modelo->getInmuebles();
        include __DIR__ . '/../views/inmuebles/listar_inmuebles.php';
    }

    public function new(): void {
        $this->sessionHelper->verficarSession();
        $propietarios = $this->modeloPropietarios->getPropietarios();
        $tipos        = $this->modeloTipos->getTiposInmueble();
        $zonas        = $this->modeloZonas->getZonas();
        include __DIR__ . '/../views/inmuebles/new.php';
    }

    public function edit(): void {
        $this->sessionHelper->verficarSession();
        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=inmuebles');
            exit;
        }

        $inmueble     = $this->modelo->getInmueble($codigo);
        $propietarios = $this->modeloPropietarios->getPropietarios();
        $tipos        = $this->modeloTipos->getTiposInmueble();
        $zonas        = $this->modeloZonas->getZonas();
        include __DIR__ . '/../views/inmuebles/edit.php';
    }

    public function create(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=inmuebles');
            exit;
        }

        $datos = [
            'codigo'           => trim($_POST['codigo']             ?? ''),
            'propietario_id'   => (int)($_POST['propietario_id']    ?? 0),
            'tipo_inmueble_id' => (int)($_POST['tipo_inmueble_id']  ?? 0),
            'zona_id'          => (int)($_POST['zona_id']           ?? 0),
            'descripcion'      => trim($_POST['descripcion']        ?? ''),
            'precio_alquiler'  => (float)($_POST['precio_alquiler'] ?? 0),
            'habitaciones'     => (int)($_POST['habitaciones']      ?? 0),
            'banos'            => (int)($_POST['banos']             ?? 0),
            'metros_cuadrados' => (float)($_POST['metros_cuadrados']?? 0),
            'direccion'        => trim($_POST['direccion']          ?? '')
        ];

        $usuarioId = $_SESSION['usuario_id'] ?? null;
        $resultado = $this->modelo->crearInmueble($datos, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'inmuebles',
                $this->modelo->getConexion()->insert_id,
                'INSERT',
                '',
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=inmuebles');
        exit;
    }

    public function update(): void {
        $this->sessionHelper->verficarSession();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=inmuebles');
            exit;
        }

        $codigoOriginal = trim($_POST['codigo_original'] ?? '');
        $usuarioId      = $_SESSION['usuario_id'] ?? null;
        $antes          = $this->modelo->getInmueble($codigoOriginal);

        $datos = [
            'codigo'           => trim($_POST['codigo']             ?? ''),
            'propietario_id'   => (int)($_POST['propietario_id']    ?? 0),
            'tipo_inmueble_id' => (int)($_POST['tipo_inmueble_id']  ?? 0),
            'zona_id'          => (int)($_POST['zona_id']           ?? 0),
            'descripcion'      => trim($_POST['descripcion']        ?? ''),
            'precio_alquiler'  => (float)($_POST['precio_alquiler'] ?? 0),
            'habitaciones'     => (int)($_POST['habitaciones']      ?? 0),
            'banos'            => (int)($_POST['banos']             ?? 0),
            'metros_cuadrados' => (float)($_POST['metros_cuadrados']?? 0),
            'direccion'        => trim($_POST['direccion']          ?? ''),
            'estado'           => trim($_POST['estado']             ?? 'A')
        ];

        $resultado = $this->modelo->actualizarInmueble($datos, $codigoOriginal, $usuarioId);

        if ($resultado) {
            $this->bitacora->registrar(
                'inmuebles',
                $antes['id'],
                'UPDATE',
                json_encode($antes),
                json_encode($datos),
                $usuarioId ?? 0
            );
        }

        header('Location: index.php?action=inmuebles');
        exit;
    }

    public function delete(): void {
        $this->sessionHelper->verficarSession();

        $codigo = $_GET['codigo'] ?? null;
        if (!$codigo) {
            header('Location: index.php?action=inmuebles');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'] ?? 0;

        // Obtener datos antes
        $antes = $this->modelo->getInmueble($codigo);

        if (!$antes) {
            header('Location: index.php?action=inmuebles');
            exit;
        }

        // ❗ En lugar de DELETE → UPDATE estado = 'I'
        $conexion = $this->modelo->getConexion();
        $stmt = $conexion->prepare(
            "UPDATE inmuebles 
            SET estado = 'I', modificado_por = ?, modificado_en = NOW()
            WHERE codigo = ?"
        );

        $stmt->bind_param("is", $usuarioId, $codigo);
        $resultado = $stmt->execute();

        if ($resultado) {
            $despues = $this->modelo->getInmueble($codigo);

            $this->bitacora->registrar(
                'inmuebles',
                $antes['id'],
                'DELETE', // lo dejas así por lógica de negocio
                json_encode($antes),
                json_encode($despues),
                $usuarioId
            );
        }

        header('Location: index.php?action=inmuebles');
        exit;
    }
}
