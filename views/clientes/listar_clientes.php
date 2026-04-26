<?php
$pageTitle = 'Clientes';

ob_start();
?>

<body>
    <a href="index.php?action=cliente_new" class="btn btn-primary">CREAR NUEVO CLIENTE</a>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Codigo</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>DPI</th>
                <th>NIT</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Creado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($clientes as $cliente) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['dpi']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nit']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['estado']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['creado_por_nombre'] ?? ''); ?></td>
                    <td class="flex gap-8">
                        <a href="index.php?action=cliente_edit&codigo=<?php echo urlencode($cliente['codigo']); ?>"class="btn btn-edit">Actualizar</a>
                        <a href="index.php?action=cliente_delete&codigo=<?php echo urlencode($cliente['codigo']); ?>"  class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este cliente?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>