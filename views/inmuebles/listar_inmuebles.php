<?php
$pageTitle = 'Inmuebles';

ob_start();
?>

<body>
    <a href="index.php?action=inmueble_new" class="btn btn-primary">CREAR NUEVO INMUEBLE</a>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Codigo</th>
                <th>Propietario</th>
                <th>Tipo</th>
                <th>Zona</th>
                <th>Descripción</th>
                <th>Precio Alquiler</th>
                <th>Habitaciones</th>
                <th>Baños</th>
                <th>M²</th>
                <th>Estado</th>
                <th>Creado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($inmuebles as $inmueble) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($inmueble['id']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['codigo']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['propietario_nombres'] . ' ' . $inmueble['propietario_apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['tipo_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['zona_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['precio_alquiler']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['habitaciones']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['banos']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['metros_cuadrados']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['estado']); ?></td>
                    <td><?php echo htmlspecialchars($inmueble['creado_por_nombre'] ?? ''); ?></td>
                    <td>
                        <a href="index.php?action=inmueble_edit&codigo=<?php echo urlencode($inmueble['codigo']); ?>"class="btn btn-edit">Actualizar</a>
                        <a href="index.php?action=inmueble_delete&codigo=<?php echo urlencode($inmueble['codigo']); ?>"class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este inmueble?')">Eliminar</a>
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