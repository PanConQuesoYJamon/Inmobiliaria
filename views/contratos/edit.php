<?php
$pageTitle = 'Editar Contrato';

ob_start();
?>

<body>
    <p><a href="index.php?action=contratos">Volver a Contratos</a></p>

    <form action="index.php?action=contrato_update" method="post">

        <input type="hidden" name="numero_original" value="<?php echo htmlspecialchars($contrato['numero_contrato'] ?? ''); ?>">

        <label for="numero_contrato">No. Contrato</label>
        <input type="text" id="numero_contrato" name="numero_contrato" value="<?php echo htmlspecialchars($contrato['numero_contrato'] ?? ''); ?>">

        <label for="inmueble_id">Inmueble</label>
        <select id="inmueble_id" name="inmueble_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($inmuebles as $inmueble) : ?>
                <option value="<?php echo $inmueble['id']; ?>"
                    <?= $inmueble['id'] == ($contrato['inmueble_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($inmueble['codigo'] . ' - ' . $inmueble['descripcion']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="cliente_id">Cliente</label>
        <select id="cliente_id" name="cliente_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($clientes as $cliente) : ?>
                <option value="<?php echo $cliente['id']; ?>"
                    <?= $cliente['id'] == ($contrato['cliente_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apellidos']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tipo_contrato_id">Tipo de Contrato</label>
        <select id="tipo_contrato_id" name="tipo_contrato_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($tipos as $tipo) : ?>
                <option value="<?php echo $tipo['id']; ?>"
                    <?= $tipo['id'] == ($contrato['tipo_contrato_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="usuario_id">Usuario Responsable</label>
        <select id="usuario_id" name="usuario_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($usuarios as $usuario) : ?>
                <option value="<?php echo $usuario['id']; ?>"
                    <?= $usuario['id'] == ($contrato['usuario_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($usuario['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_inicio">Fecha Inicio</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($contrato['fecha_inicio'] ?? ''); ?>">

        <label for="fecha_fin">Fecha Fin</label>
        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($contrato['fecha_fin'] ?? ''); ?>">

        <label for="monto_mensual">Monto Mensual</label>
        <input type="number" step="0.01" id="monto_mensual" name="monto_mensual" value="<?php echo htmlspecialchars($contrato['monto_mensual'] ?? '0.00'); ?>">

        <label for="deposito">Depósito</label>
        <input type="number" step="0.01" id="deposito" name="deposito" value="<?php echo htmlspecialchars($contrato['deposito'] ?? '0.00'); ?>">

        <label for="dia_pago">Día de Pago</label>
        <input type="number" id="dia_pago" name="dia_pago" min="1" max="31" value="<?php echo htmlspecialchars($contrato['dia_pago'] ?? 1); ?>">

        <label for="observaciones">Observaciones</label>
        <input type="text" id="observaciones" name="observaciones" value="<?php echo htmlspecialchars($contrato['observaciones'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($contrato['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($contrato['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
            <option value="V" <?= ($contrato['estado'] ?? '') === 'V' ? 'selected' : '' ?>>Vencido</option>
            <option value="R" <?= ($contrato['estado'] ?? '') === 'R' ? 'selected' : '' ?>>Rescindido</option>
        </select>

        <button type="submit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>