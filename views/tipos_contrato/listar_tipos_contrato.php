<?php
$pageTitle = 'Tipos de Contratos';

ob_start();
?>

<body>
    <a href="index.php?action=tipo_contrato_new" class="btn btn-primary" >CREAR NUEVO TIPO DE CONTRATO</a>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Duración (meses)</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tipos as $tipo) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($tipo['id']); ?></td>
                    <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($tipo['duracion_meses']); ?></td>
                    <td><?php echo htmlspecialchars($tipo['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($tipo['estado']); ?></td>
                    <td class="flex gap-8">
                        <a href="index.php?action=tipo_contrato_edit&id=<?php echo urlencode($tipo['id']); ?>" class="btn btn-edit">Actualizar</a>
                        <a href="index.php?action=tipo_contrato_delete&id=<?php echo urlencode($tipo['id']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este tipo de contrato?')">Eliminar</a>
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