<?php
// Ejemplo de cómo usar el layout en cualquier vista
// Aplica el mismo patrón para todas las demás vistas

$pageTitle = 'Usuarios';

ob_start(); // Inicia captura del contenido
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
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Username</th>
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
$content = ob_get_clean(); // Captura el HTML generado
include __DIR__ . '/../../assets/layout/layout.php'; // Aplica el layout
?>
