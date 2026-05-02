<?php
$pageTitle = 'Facturas';
ob_start();
?>

<div class="page-header">
    <div>
        <h2>Facturas</h2>
        <p class="text-muted">Gestión de facturación del sistema</p>
    </div>
    <a href="index.php?action=factura_new" class="btn btn-primary">+ Nueva Factura</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No. Factura</th>
                    <th>Contrato</th>
                    <th>Cliente</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Vencimiento</th>
                    <th>Subtotal</th>
                    <th>Impuesto</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($facturas as $factura) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($factura['numero_factura']); ?></td>
                        <td><?php echo htmlspecialchars($factura['numero_contrato']); ?></td>
                        <td><?php echo htmlspecialchars($factura['cliente_nombres'] . ' ' . $factura['cliente_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($factura['fecha_emision']); ?></td>
                        <td><?php echo htmlspecialchars($factura['fecha_vencimiento'] ?? '—'); ?></td>
                        <td><?php echo number_format($factura['subtotal'], 2); ?></td>
                        <td><?php echo number_format($factura['impuesto'], 2); ?></td>
                        <td><strong><?php echo number_format($factura['total'], 2); ?></strong></td>
                        <td>
                            <?php
                            $badgeEstado = match($factura['estado']) {
                                'pagada'   => 'badge-active',
                                'emitida'  => 'badge-warning',
                                'anulada'  => 'badge-inactive',
                                default    => ''
                            };
                            ?>
                            <span class="badge <?php echo $badgeEstado; ?>">
                                <?php echo ucfirst(htmlspecialchars($factura['estado'])); ?>
                            </span>
                        </td>
                        <td class="flex gap-8">
                            <a href="index.php?action=factura_show&numero_factura=<?php echo urlencode($factura['numero_factura']); ?>" class="btn btn-outline btn-sm">Ver</a>
                            <a href="index.php?action=factura_edit&numero_factura=<?php echo urlencode($factura['numero_factura']); ?>" class="btn btn-edit">Editar</a>
                            <a href="index.php?action=factura_delete&numero_factura=<?php echo urlencode($factura['numero_factura']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar esta factura?')">Eliminar</a>
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
