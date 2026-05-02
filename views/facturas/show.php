<?php
$pageTitle = 'Detalle de Factura';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=facturas">Facturas</a>
    <span>›</span> <?php echo htmlspecialchars($factura['numero_factura']); ?>
</div>

<div class="page-header">
    <div>
        <h2>Factura <?php echo htmlspecialchars($factura['numero_factura']); ?></h2>
        <p class="text-muted">Emitida el <?php echo htmlspecialchars($factura['fecha_emision']); ?></p>
    </div>
    <div class="flex gap-8">
        <a href="index.php?action=factura_edit&numero_factura=<?php echo urlencode($factura['numero_factura']); ?>" class="btn btn-outline">Editar</a>
        <a href="index.php?action=pago_factura_new&factura_id=<?php echo $factura['id']; ?>" class="btn btn-primary">Registrar Pago</a>
    </div>
</div>

<!-- Datos de la factura -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><h3>Información General</h3></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Cliente</label>
                <p><?php echo htmlspecialchars($factura['cliente_nombres'] . ' ' . $factura['cliente_apellidos']); ?></p>
            </div>
            <div class="form-group">
                <label>Contrato</label>
                <p><?php echo htmlspecialchars($factura['numero_contrato']); ?></p>
            </div>
            <div class="form-group">
                <label>Fecha Vencimiento</label>
                <p><?php echo htmlspecialchars($factura['fecha_vencimiento'] ?? '—'); ?></p>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <p><?php echo ucfirst(htmlspecialchars($factura['estado'])); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Detalle de renglones -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <h3>Detalle</h3>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($detalles as $detalle) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detalle['concepto']); ?></td>
                        <td><?php echo number_format($detalle['cantidad'], 2); ?></td>
                        <td><?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                        <td><?php echo number_format($detalle['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right; padding:12px 16px; font-weight:600;">Subtotal</td>
                    <td style="padding:12px 16px;"><?php echo number_format($factura['subtotal'], 2); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right; padding:4px 16px; font-weight:600;">Impuesto</td>
                    <td style="padding:4px 16px;"><?php echo number_format($factura['impuesto'], 2); ?></td>
                    <td></td>
                </tr>
                <tr style="background:var(--color-bg);">
                    <td colspan="3" style="text-align:right; padding:12px 16px; font-weight:700; font-size:1rem;">TOTAL</td>
                    <td style="padding:12px 16px; font-weight:700; font-size:1rem;"><?php echo number_format($factura['total'], 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Pagos aplicados -->
<div class="card">
    <div class="card-header"><h3>Pagos Aplicados</h3></div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No. Recibo</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Referencia</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($pagos as $pago) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($pago['numero_recibo']); ?></td>
                        <td><?php echo htmlspecialchars($pago['fecha_pago']); ?></td>
                        <td><?php echo number_format($pago['monto_pagado'], 2); ?></td>
                        <td><?php echo htmlspecialchars($pago['metodo_pago']); ?></td>
                        <td><?php echo htmlspecialchars($pago['referencia'] ?? '—'); ?></td>
                        <td>
                            <?php if($pago['estado'] === 'A'): ?>
                                <span class="badge badge-active">Aplicado</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Anulado</span>
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
