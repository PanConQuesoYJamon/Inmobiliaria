<?php
$pageTitle = 'Pagos de Facturas';
ob_start();
?>

<div class="page-header">
    <div>
        <h2>Pagos de Facturas</h2>
        <p class="text-muted">Historial de pagos aplicados a facturas</p>
    </div>
    <a href="index.php?action=pago_factura_new" class="btn btn-primary">+ Registrar Pago</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No. Recibo</th>
                    <th>Factura</th>
                    <th>Cliente</th>
                    <th>Fecha Pago</th>
                    <th>Monto Pagado</th>
                    <th>Método</th>
                    <th>Referencia</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pagos as $pago) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($pago['numero_recibo']); ?></td>
                        <td>
                            <a href="index.php?action=factura_show&numero_factura=<?php echo urlencode($pago['numero_factura']); ?>">
                                <?php echo htmlspecialchars($pago['numero_factura']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($pago['cliente_nombres'] . ' ' . $pago['cliente_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($pago['fecha_pago']); ?></td>
                        <td><strong><?php echo number_format($pago['monto_pagado'], 2); ?></strong></td>
                        <td><?php echo htmlspecialchars($pago['metodo_pago'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($pago['referencia'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($pago['usuario_nombre'] ?? '—'); ?></td>
                        <td>
                            <?php if($pago['estado'] === 'A'): ?>
                                <span class="badge badge-active">Aplicado</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Anulado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($pago['estado'] === 'A'): ?>
                                <a href="index.php?action=pago_factura_anular&numero_recibo=<?php echo urlencode($pago['numero_recibo']); ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('¿Está seguro que desea anular este pago?')">
                                   Anular
                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
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
