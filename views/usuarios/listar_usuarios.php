<?php
$pageTitle = 'Usuarios';
ob_start();
?>

<div class="page-header">
    <div>
        <h2>Usuarios</h2>
        <p class="text-muted">Gestión de usuarios del sistema</p>
    </div>
    <a href="index.php?action=usuario_new" class="btn btn-primary">+ Nuevo Usuario</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Username</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usuarios as $user) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($user['codigo']); ?></td>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <?php if($user['rol'] === 'admin'): ?>
                                <span class="badge badge-active">Administrador</span>
                            <?php elseif($user['rol'] === 'supervisor'): ?>
                                <span class="badge badge-warning">Supervisor</span>
                            <?php else: ?>
                                <span class="badge">Usuario</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($user['estado'] === 'A'): ?>
                                <span class="badge badge-active">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="flex gap-8">
                            <a href="index.php?action=usuario_edit&codigo=<?php echo urlencode($user['codigo']); ?>" class="btn btn-edit">Editar</a>
                            <a href="index.php?action=usuario_delete&codigo=<?php echo urlencode($user['codigo']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
