<?php
$pageTitle = 'Zonas';

ob_start();
?>

<body>
    <a href="index.php?action=zona_new">CREAR NUEVA ZONA</a>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Municipio</th>
                <th>Departamento</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($zonas as $zona) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($zona['id']); ?></td>
                    <td><?php echo htmlspecialchars($zona['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($zona['municipio']); ?></td>
                    <td><?php echo htmlspecialchars($zona['departamento']); ?></td>
                    <td><?php echo htmlspecialchars($zona['estado']); ?></td>
                    <td>
                        <a href="index.php?action=zona_edit&id=<?php echo urlencode($zona['id']); ?>">Actualizar</a>
                        <a href="index.php?action=zona_delete&id=<?php echo urlencode($zona['id']); ?>" onclick="return confirm('¿Está seguro que desea eliminar esta zona?')">Eliminar</a>
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