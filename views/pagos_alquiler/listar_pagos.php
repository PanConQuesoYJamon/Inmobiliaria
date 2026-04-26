<?php
$pageTitle = 'Pagos';

ob_start();
?>

<body>
    <a href="index.php?action=pago_new" class="btn btn-primary">REGISTRAR NUEVO PAGO</a>
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
                <th>Método Pago</th>
                <th>Estado</th>
                <th>Registrado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pagos as $pago) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($pago['numero_recibo']); ?></td>
                    <td><?php echo htmlspecialchars($pago['numero_contrato']); ?></td>
                    <td><?php echo htmlspecialchars($pago['cliente_nombres'] . ' ' . $pago['cliente_apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($pago['inmueble_codigo']); ?></td>
                    <td><?php echo htmlspecialchars($pago['fecha_pago']); ?></td>
                    <td><?php echo htmlspecialchars(date('m/Y', strtotime($pago['periodo_mes']))); ?></td>
                    <td><?php echo htmlspecialchars($pago['monto_esperado']); ?></td>
                    <td><?php echo htmlspecialchars($pago['monto_pagado']); ?></td>
                    <td><?php echo htmlspecialchars($pago['mora']); ?></td>
                    <td><?php echo htmlspecialchars($pago['metodo_pago']); ?></td>
                    <td><?php echo htmlspecialchars($pago['estado']); ?></td>
                    <td><?php echo htmlspecialchars($pago['creado_por_nombre'] ?? ''); ?></td>
                    <td class="flex gap-8">
                        <a href="index.php?action=pago_edit&numero_recibo=<?php echo urlencode($pago['numero_recibo']); ?>" class="btn btn-edit">Actualizar</a>
                        <a href="index.php?action=pago_delete&numero_recibo=<?php echo urlencode($pago['numero_recibo']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar este pago?')">Eliminar</a>
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