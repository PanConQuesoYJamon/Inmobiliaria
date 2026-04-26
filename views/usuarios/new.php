<?php
$pageTitle = 'Crear Usuario';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=usuarios">Usuarios</a>
    <span>›</span> Nuevo Usuario
</div>

<div class="page-header">
    <div>
        <h2>Nuevo Usuario</h2>
        <p class="text-muted">Complete los datos para crear un nuevo usuario</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=usuario_create" method="post">

            <div class="form-grid">

                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="clave">Contraseña</label>
                    <input type="password" id="clave" name="clave" required>
                </div>

                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol">
                        <option value="usuario">Usuario</option>
                        <option value="admin">Administrador</option>
                        <option value="supervisor">Supervisor</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php?action=usuarios" class="btn btn-outline">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
