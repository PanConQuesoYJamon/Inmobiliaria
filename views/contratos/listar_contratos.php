<?php
$pageTitle = 'Contratos';

ob_start();
?>

<body>
    <a href="index.php?action=contrato_new">CREAR NUEVO CONTRATO</a>
    <table>
        <thead>
            <tr>
                <th>No. Contrato</th>
                <th>Inmueble</th>
                <th>Cliente</th>
                <th>Tipo Contrato</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Monto Mensual</th>
                <th>Depósito</th>
                <th>Día de Pago</th>
                <th>Estado</th>
                <th>Creado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($contratos as $contrato) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($contrato['numero_contrato']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['inmueble_codigo']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['cliente_nombres'] . ' ' . $contrato['cliente_apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['tipo_contrato_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['fecha_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['fecha_fin']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['monto_mensual']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['deposito']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['dia_pago']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['estado']); ?></td>
                    <td><?php echo htmlspecialchars($contrato['creado_por_nombre'] ?? ''); ?></td>
                    <td>
                        <a href="index.php?action=contrato_edit&numero_contrato=<?php echo urlencode($contrato['numero_contrato']); ?>">Actualizar</a>
                        <a href="index.php?action=contrato_delete&numero_contrato=<?php echo urlencode($contrato['numero_contrato']); ?>" onclick="return confirm('¿Está seguro que desea eliminar este contrato?')">Eliminar</a>
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