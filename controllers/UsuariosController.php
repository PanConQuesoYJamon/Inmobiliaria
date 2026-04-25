<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/Conexion.php';

class UsuariosController {

    private $modelo;
    public function __construct(){

        $conexion = (new Conexion)->conectar();
        $this->modelo = new UsuarioModel($conexion);
    }


    /* METODOS DE PREPARACION DE VISTA*/
    /**
     * INDEX
     */
    public function index(): void {
        $usuarios = $this->modelo->getUsuarios();
        include __DIR__ . '/../views/usuarios/listar_usuarios.php';
    }

    /**
     * NEW - levanta el formulario vacio preparar la vista para ejecutar la vista
     */
    public function new(): void {
        include __DIR__ . '/../views/usuarios/new.php';
    }

    /**
     * SHOW muestra el detalle por medio de un boton
     */

    /**
     * EDIT preparacion de vistas
     */
    public function edit(): void {
        $codigo = $_GET['codigo'] ?? null;
        if(!$codigo){
            header('Location: index.php?action=usuarios');
            exit;
        }

        $usuario = $this->modelo->getUsuario($codigo);
        include __DIR__ . '/../views/usuarios/edit.php';
    }

    /** METODOS DE ACCION */
    /**
     * CREATE captura los datos cuando el usuario le da crear 
     */
    public function create(): void {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: index.php?action=usuarios');
            exit;
        }

        $datos = [
            'codigo'    => trim($_POST['codigo'] ?? ''),
            'nombre'    => trim($_POST['nombre'] ?? ''),
            'username'  => trim($_POST['username'] ?? ''),
            'clave'     => trim($_POST['clave'] ?? '')
        ];

        $this->modelo->crearUsuario($datos);
        header('Location: index.php?action=usuarios');
    }

    /**
     * UPDATE
     */
    public function update(): void {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: index.php?action=usuarios');
            exit;
        }

        $codigoOriginal = trim($_POST['codigo_original'] ?? '');

        $datos = [
            'codigo'    => trim($_POST['codigo']    ?? ''),
            'nombre'    => trim($_POST['nombre']    ?? ''),
            'username'  => trim($_POST['username']  ?? ''),
            'clave'     => trim($_POST['clave']     ?? ''),
            'estado'    => trim($_POST['estado']    ?? 'A')
        ];

        $this->modelo->actualizarUsuario($datos, $codigoOriginal);
        header('Location: index.php?action=usuarios');
        exit;
    }

    /**
     * DELETE
     */

    public function delete(): void {
        $codigo = $_GET['codigo'] ?? null;
        if(!$codigo){
            header('Location: index.php?action=usuarios');
            exit;
        }
        $this->modelo->eliminarUsuario($codigo);
        header('Location: index.php?action=usuarios');
        exit;
    }
}
?>