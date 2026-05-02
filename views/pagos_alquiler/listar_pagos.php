<?php
$pageTitle = 'Pagos de Alquiler';
ob_start();
?>

<div class="page-header">
    <div>
        <h2>Pagos de Alquiler</h2>
        <p class="text-muted">Al registrar un pago se genera su factura automáticamente</p>
    </div>
    <a href="index.php?action=pago_new" class="btn btn-primary">+ Registrar Pago</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No. Recibo</th>
                    <th>Contrato</th>
                    <th>Cliente</th>
                    <th>Inmueble</th>
                    <th>Fecha Pago</th>
                    <th>Período</th>
                    <th>Monto Esperado</th>
                    <th>Monto Pagado</th>
                    <th>Mora</th>
                    <th>Total</th>
                    <th>Método</th>
                    <th>Factura Generada</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pagos as $pago) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($pago['numero_recibo']); ?></td>
                        <td><?php echo htmlspecialchars($pago['numero_contrato']); ?></td>
                        <td><?php echo htmlspecialchars($pago['cliente_nombres'] . ' ' . $pago['cliente_apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($pago['inmueble_codigo']); ?></td>
                        <td><?php echo htmlspecialchars($pago['fecha_pago']); ?></td>
                        <td><?php echo htmlspecialchars(date('m/Y', strtotime($pago['periodo_mes']))); ?></td>
                        <td><?php echo number_format($pago['monto_esperado'], 2); ?></td>
                        <td><?php echo number_format($pago['monto_pagado'], 2); ?></td>
                        <td><?php echo number_format($pago['mora'], 2); ?></td>
                        <td><?php echo number_format($pago['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($pago['metodo_pago']); ?></td>
                        <td>
                            <?php if (!empty($pago['numero_factura'])): ?>
                                <a href="index.php?action=factura_show&numero_factura=<?php echo urlencode($pago['numero_factura']); ?>"
                                   class="badge badge-active" style="text-decoration:none;">
                                    <?php echo htmlspecialchars($pago['numero_factura']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($pago['estado'] === 'A'): ?>
                                <span class="badge badge-active">Aplicado</span>
                            <?php elseif($pago['estado'] === 'P'): ?>
                                <span class="badge badge-warning">Pendiente</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Anulado</span>
                            <?php endif; ?>
                        </td>
                        <td class="flex gap-8">
                            <a href="index.php?action=pago_edit&numero_recibo=<?php echo urlencode($pago['numero_recibo']); ?>" class="btn btn-edit">Editar</a>
                            <a href="index.php?action=pago_delete&numero_recibo=<?php echo urlencode($pago['numero_recibo']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este pago?')">Eliminar</a>
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
