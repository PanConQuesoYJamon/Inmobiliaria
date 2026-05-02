<?php
$pageTitle = 'Conceptos de Facturación';
ob_start();
?>

<div class="page-header">
    <div>
        <h2>Conceptos de Facturación</h2>
        <p class="text-muted">Catálogo de conceptos disponibles para agregar a las facturas</p>
    </div>
    <a href="index.php?action=concepto_facturacion_new" class="btn btn-primary">+ Nuevo Concepto</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio Base</th>
                    <th>Aplica Impuesto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($conceptos as $concepto) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($concepto['id']); ?></td>
                        <td><?php echo htmlspecialchars($concepto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($concepto['descripcion'] ?? '—'); ?></td>
                        <td><?php echo number_format($concepto['precio_base'], 2); ?></td>
                        <td>
                            <?php if($concepto['aplica_impuesto'] === 'S'): ?>
                                <span class="badge badge-active">Sí</span>
                            <?php else: ?>
                                <span class="badge">No</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($concepto['estado'] === 'A'): ?>
                                <span class="badge badge-active">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="flex gap-8">
                            <a href="index.php?action=concepto_facturacion_edit&id=<?php echo urlencode($concepto['id']); ?>" class="btn btn-edit">Editar</a>
                            <a href="index.php?action=concepto_facturacion_delete&id=<?php echo urlencode($concepto['id']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este concepto?')">Eliminar</a>
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
