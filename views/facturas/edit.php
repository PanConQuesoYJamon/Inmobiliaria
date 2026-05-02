<?php
$pageTitle = 'Editar Factura';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=facturas">Facturas</a>
    <span>›</span> Editar Factura
</div>

<div class="page-header">
    <h2>Editar Factura</h2>
</div>

<form action="index.php?action=factura_update" method="post">

    <input type="hidden" name="numero_original" value="<?php echo htmlspecialchars($factura['numero_factura'] ?? ''); ?>">

    <!-- Datos generales -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header"><h3>Datos Generales</h3></div>
        <div class="card-body">
            <div class="form-grid">

                <div class="form-group">
                    <label for="numero_factura">No. Factura</label>
                    <input type="text" id="numero_factura" name="numero_factura"
                           value="<?php echo htmlspecialchars($factura['numero_factura'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="contrato_id">Contrato</label>
                    <select id="contrato_id" name="contrato_id">
                        <option value="">-- Seleccione --</option>
                        <?php foreach($contratos as $contrato) : ?>
                            <option value="<?php echo $contrato['id']; ?>"
                                <?= $contrato['id'] == ($factura['contrato_id'] ?? 0) ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($contrato['numero_contrato'] . ' — ' . $contrato['cliente_nombres'] . ' ' . $contrato['cliente_apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cliente_id">Cliente</label>
                    <select id="cliente_id" name="cliente_id">
                        <option value="">-- Seleccione --</option>
                        <?php foreach($clientes as $cliente) : ?>
                            <option value="<?php echo $cliente['id']; ?>"
                                <?= $cliente['id'] == ($factura['cliente_id'] ?? 0) ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($cliente['nombres'] . ' ' . $cliente['apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuario_id">Usuario Responsable</label>
                    <select id="usuario_id" name="usuario_id">
                        <option value="">-- Seleccione --</option>
                        <?php foreach($usuarios as $usuario) : ?>
                            <option value="<?php echo $usuario['id']; ?>"
                                <?= $usuario['id'] == ($factura['usuario_id'] ?? 0) ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_emision">Fecha de Emisión</label>
                    <input type="date" id="fecha_emision" name="fecha_emision"
                           value="<?php echo htmlspecialchars($factura['fecha_emision'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                    <input type="date" id="fecha_vencimiento" name="fecha_vencimiento"
                           value="<?php echo htmlspecialchars($factura['fecha_vencimiento'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="impuesto">Impuesto</label>
                    <input type="number" step="0.01" id="impuesto" name="impuesto"
                           value="<?php echo htmlspecialchars($factura['impuesto'] ?? '0.00'); ?>"
                           oninput="recalcularTotales()">
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="borrador" <?= ($factura['estado'] ?? '') === 'borrador' ? 'selected' : '' ?>>Borrador</option>
                        <option value="emitida"  <?= ($factura['estado'] ?? '') === 'emitida'  ? 'selected' : '' ?>>Emitida</option>
                        <option value="pagada"   <?= ($factura['estado'] ?? '') === 'pagada'   ? 'selected' : '' ?>>Pagada</option>
                        <option value="anulada"  <?= ($factura['estado'] ?? '') === 'anulada'  ? 'selected' : '' ?>>Anulada</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="observaciones">Observaciones</label>
                    <input type="text" id="observaciones" name="observaciones"
                           value="<?php echo htmlspecialchars($factura['observaciones'] ?? ''); ?>">
                </div>

            </div>
        </div>
    </div>

    <!-- Renglones -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h3>Renglones</h3>
            <button type="button" class="btn btn-outline btn-sm" onclick="agregarRenglon()">+ Agregar renglón</button>
        </div>
        <div class="table-wrapper">
            <table id="tabla-renglones">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th style="width:120px;">Cantidad</th>
                        <th style="width:150px;">Precio Unitario</th>
                        <th style="width:130px;">Subtotal</th>
                        <th style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody id="renglones-body"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; padding:12px 16px; font-weight:600;">Subtotal</td>
                        <td style="padding:12px 16px;" id="total-subtotal">0.00</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right; padding:4px 16px; font-weight:600;">Impuesto</td>
                        <td style="padding:4px 16px;" id="total-impuesto">0.00</td>
                        <td></td>
                    </tr>
                    <tr style="background:var(--color-bg);">
                        <td colspan="3" style="text-align:right; padding:12px 16px; font-weight:700;">TOTAL</td>
                        <td style="padding:12px 16px; font-weight:700;" id="total-general">0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <input type="hidden" name="subtotal" id="input-subtotal" value="0">
    <input type="hidden" name="total"    id="input-total"    value="0">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Actualizar Factura</button>
        <a href="index.php?action=facturas" class="btn btn-outline">Cancelar</a>
    </div>

</form>

<script>
const conceptos = <?php echo json_encode($conceptos, JSON_UNESCAPED_UNICODE); ?>;
let renglonIndex = 0;

function agregarRenglon(conceptoId = '', concepto = '', cantidad = 1, precio = 0) {
    const tbody = document.getElementById('renglones-body');
    const i     = renglonIndex++;
    const subtotal = (cantidad * precio).toFixed(2);

    let opciones = '<option value="">-- Seleccione o escriba --</option>';
    conceptos.forEach(c => {
        const sel = c.id == conceptoId ? 'selected' : '';
        opciones += `<option value="${c.id}" data-precio="${c.precio_base}" ${sel}>${c.nombre}</option>`;
    });

    const fila = document.createElement('tr');
    fila.innerHTML = `
        <td>
            <input type="hidden" name="concepto_id[]" id="concepto_id_${i}" value="${conceptoId}">
            <select onchange="seleccionarConcepto(this, ${i})" style="margin-bottom:4px; width:100%;">
                ${opciones}
            </select>
            <input type="text" name="concepto[]" id="concepto_${i}"
                   value="${concepto}" placeholder="O escriba el concepto" required style="width:100%;">
        </td>
        <td>
            <input type="number" step="0.01" name="cantidad[]" id="cantidad_${i}"
                   value="${cantidad}" min="0.01" oninput="calcularRenglon(${i})" style="width:100%;">
        </td>
        <td>
            <input type="number" step="0.01" name="precio_unitario[]" id="precio_${i}"
                   value="${precio}" min="0" oninput="calcularRenglon(${i})" style="width:100%;">
        </td>
        <td id="subtotal_${i}" style="padding:12px 16px;">${subtotal}</td>
        <td style="padding:8px;">
            <button type="button" class="btn btn-danger" onclick="eliminarRenglon(this)">✕</button>
        </td>
    `;
    tbody.appendChild(fila);
    recalcularTotales();
}

function seleccionarConcepto(select, i) {
    const opcion = select.options[select.selectedIndex];
    document.getElementById(`concepto_id_${i}`).value = select.value;
    if (select.value) {
        document.getElementById(`concepto_${i}`).value = opcion.text;
        document.getElementById(`precio_${i}`).value   = opcion.dataset.precio;
        calcularRenglon(i);
    }
}

function calcularRenglon(i) {
    const cantidad = parseFloat(document.getElementById(`cantidad_${i}`).value) || 0;
    const precio   = parseFloat(document.getElementById(`precio_${i}`).value)   || 0;
    const subtotal = (cantidad * precio).toFixed(2);
    document.getElementById(`subtotal_${i}`).textContent = subtotal;
    recalcularTotales();
}

function eliminarRenglon(btn) {
    btn.closest('tr').remove();
    recalcularTotales();
}

function recalcularTotales() {
    let subtotal = 0;
    document.querySelectorAll('[id^="subtotal_"]').forEach(td => {
        subtotal += parseFloat(td.textContent) || 0;
    });

    const impuesto = parseFloat(document.getElementById('impuesto').value) || 0;
    const total    = subtotal + impuesto;

    document.getElementById('total-subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('total-impuesto').textContent = impuesto.toFixed(2);
    document.getElementById('total-general').textContent  = total.toFixed(2);
    document.getElementById('input-subtotal').value       = subtotal.toFixed(2);
    document.getElementById('input-total').value          = total.toFixed(2);
}

// Precargar renglones existentes
<?php foreach($detalles as $detalle): ?>
agregarRenglon(
    <?php echo (int)($detalle['concepto_id'] ?? 0); ?>,
    <?php echo json_encode($detalle['concepto'], JSON_UNESCAPED_UNICODE); ?>,
    <?php echo (float)$detalle['cantidad']; ?>,
    <?php echo (float)$detalle['precio_unitario']; ?>
);
<?php endforeach; ?>

<?php if (empty($detalles)): ?>
agregarRenglon();
<?php endif; ?>
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
