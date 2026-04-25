<?php
$pageTitle = 'Propietarios';

ob_start();
?>

<body>
    <a href="index.php?action=propietario_new">CREAR NUEVO PROPIETARIO</a>
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
            <?php foreach($propietarios as $propietario) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($propietario['id']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['nombres']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['dpi']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['nit']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['email']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['estado']); ?></td>
                    <td><?php echo htmlspecialchars($propietario['creado_por_nombre'] ?? ''); ?></td>
                    <td>
                        <a href="index.php?action=propietario_edit&codigo=<?php echo urlencode($propietario['codigo']); ?>">Actualizar</a>
                        <a href="index.php?action=propietario_delete&codigo=<?php echo urlencode($propietario['codigo']); ?>" onclick="return confirm('¿Está seguro que desea eliminar este propietario?')">Eliminar</a>
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