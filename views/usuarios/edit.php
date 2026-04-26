<?php
$pageTitle = 'Editar Usuario';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=usuarios">Usuarios</a>
    <span>›</span> Editar Usuario
</div>

<div class="page-header">
    <div>
        <h2>Editar Usuario</h2>
        <p class="text-muted">Modifique los datos del usuario</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=usuario_update" method="post">

            <input type="hidden" name="codigo_original" value="<?php echo htmlspecialchars($usuario['codigo'] ?? ''); ?>">

            <div class="form-grid">

                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($usuario['codigo'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usuario['username'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="clave">Contraseña <span class="text-muted">(dejar vacío para no cambiar)</span></label>
                    <input type="password" id="clave" name="clave" placeholder="Nueva contraseña">
                </div>

                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select id="rol" name="rol">
                        <option value="usuario"    <?= ($usuario['rol'] ?? '') === 'usuario'    ? 'selected' : '' ?>>Usuario</option>
                        <option value="admin"      <?= ($usuario['rol'] ?? '') === 'admin'      ? 'selected' : '' ?>>Administrador</option>
                        <option value="supervisor" <?= ($usuario['rol'] ?? '') === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="A" <?= ($usuario['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
                        <option value="I" <?= ($usuario['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php?action=usuarios" class="btn btn-outline">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
