<?php
$pageTitle = 'Crear nuevos Pagos??';

ob_start();
?>

<body>
    <p><a href="index.php?action=pagos_alquiler">Volver a Pagos</a></p>

    <form action="index.php?action=pago_create" method="post">

        <label for="numero_recibo">No. Recibo</label>
        <input type="text" id="numero_recibo" name="numero_recibo">

        <label for="contrato_id">Contrato</label>
        <select id="contrato_id" name="contrato_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($contratos as $contrato) : ?>
                <option value="<?php echo $contrato['id']; ?>">
                    <?php echo htmlspecialchars($contrato['numero_contrato'] . ' - ' . $contrato['cliente_nombres'] . ' ' . $contrato['cliente_apellidos']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="usuario_id">Usuario Responsable</label>
        <select id="usuario_id" name="usuario_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($usuarios as $usuario) : ?>
                <option value="<?php echo $usuario['id']; ?>">
                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_pago">Fecha de Pago</label>
        <input type="date" id="fecha_pago" name="fecha_pago">

        <label for="periodo_mes">Período (mes que se paga)</label>
        <input type="date" id="periodo_mes" name="periodo_mes">

        <label for="monto_esperado">Monto Esperado</label>
        <input type="number" step="0.01" id="monto_esperado" name="monto_esperado" value="0.00">

        <label for="monto_pagado">Monto Pagado</label>
        <input type="number" step="0.01" id="monto_pagado" name="monto_pagado" value="0.00">

        <label for="mora">Mora</label>
        <input type="number" step="0.01" id="mora" name="mora" value="0.00">

        <label for="metodo_pago">Método de Pago</label>
        <select id="metodo_pago" name="metodo_pago">
            <option value="">-- Seleccione --</option>
            <option value="Efectivo">Efectivo</option>
            <option value="Transferencia">Transferencia</option>
            <option value="Cheque">Cheque</option>
            <option value="Deposito">Depósito</option>
        </select>

        <label for="observaciones">Observaciones</label>
        <input type="text" id="observaciones" name="observaciones">

        <button type="submit">Guardar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>