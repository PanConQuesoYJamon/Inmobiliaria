<?php
$pageTitle = 'Editar Pagos';

ob_start();
?>

<body>
    <p><a href="index.php?action=pagos_alquiler" class= "btn btn-primary">Volver a Pagos</a></p>

    <form action="index.php?action=pago_update" method="post">

        <input type="hidden" name="recibo_original" value="<?php echo htmlspecialchars($pago['numero_recibo'] ?? ''); ?>">

        <label for="numero_recibo">No. Recibo</label>
        <input type="text" id="numero_recibo" name="numero_recibo" value="<?php echo htmlspecialchars($pago['numero_recibo'] ?? ''); ?>">

        <label for="contrato_id">Contrato</label>
        <select id="contrato_id" name="contrato_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($contratos as $contrato) : ?>
                <option value="<?php echo $contrato['id']; ?>"
                    <?= $contrato['id'] == ($pago['contrato_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($contrato['numero_contrato'] . ' - ' . $contrato['cliente_nombres'] . ' ' . $contrato['cliente_apellidos']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="usuario_id">Usuario Responsable</label>
        <select id="usuario_id" name="usuario_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($usuarios as $usuario) : ?>
                <option value="<?php echo $usuario['id']; ?>"
                    <?= $usuario['id'] == ($pago['usuario_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_pago">Fecha de Pago</label>
        <input type="date" id="fecha_pago" name="fecha_pago" value="<?php echo htmlspecialchars($pago['fecha_pago'] ?? ''); ?>">

        <label for="periodo_mes">Período (mes que se paga)</label>
        <input type="date" id="periodo_mes" name="periodo_mes" value="<?php echo htmlspecialchars($pago['periodo_mes'] ?? ''); ?>">

        <label for="monto_esperado">Monto Esperado</label>
        <input type="number" step="0.01" min="0" id="monto_esperado" name="monto_esperado" value="<?php echo htmlspecialchars($pago['monto_esperado'] ?? '0.00'); ?>">

        <label for="monto_pagado">Monto Pagado</label>
        <input type="number" step="0.01" min="0" id="monto_pagado" name="monto_pagado" value="<?php echo htmlspecialchars($pago['monto_pagado'] ?? '0.00'); ?>">

        <label for="mora">Mora</label>
        <input type="number" step="0.01" min="0" id="mora" name="mora" value="<?php echo htmlspecialchars($pago['mora'] ?? '0.00'); ?>">

        <label for="metodo_pago">Método de Pago</label>
        <select id="metodo_pago" name="metodo_pago">
            <option value="">-- Seleccione --</option>
            <option value="Efectivo"      <?= ($pago['metodo_pago'] ?? '') === 'Efectivo'      ? 'selected' : '' ?>>Efectivo</option>
            <option value="Transferencia" <?= ($pago['metodo_pago'] ?? '') === 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
            <option value="Cheque"        <?= ($pago['metodo_pago'] ?? '') === 'Cheque'        ? 'selected' : '' ?>>Cheque</option>
            <option value="Deposito"      <?= ($pago['metodo_pago'] ?? '') === 'Deposito'      ? 'selected' : '' ?>>Depósito</option>
        </select>

        <label for="observaciones">Observaciones</label>
        <input type="text" id="observaciones" name="observaciones" value="<?php echo htmlspecialchars($pago['observaciones'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($pago['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Aplicado</option>
            <option value="P" <?= ($pago['estado'] ?? '') === 'P' ? 'selected' : '' ?>>Pendiente</option>
            <option value="I" <?= ($pago['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Anulado</option>
        </select>

        <button type="submit" class = "btn btn-edit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>