<?php
$pageTitle = 'Registrar Pago de Factura';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=facturas">Facturas</a>
    <span>›</span> Registrar Pago
</div>

<div class="page-header">
    <h2>Registrar Pago de Factura</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=pago_factura_create" method="post">

            <div class="form-grid">

                <div class="form-group">
                    <label for="numero_recibo">No. Recibo</label>
                    <input type="text" id="numero_recibo" name="numero_recibo" required>
                </div>

                <div class="form-group">
                    <label for="factura_id">Factura</label>
                    <select id="factura_id" name="factura_id">
                        <option value="">-- Seleccione --</option>
                        <?php foreach($facturas as $factura) : ?>
                            <option value="<?php echo $factura['id']; ?>"
                                <?= isset($_GET['factura_id']) && $_GET['factura_id'] == $factura['id'] ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($factura['numero_factura'] . ' — ' . $factura['cliente_nombres'] . ' ' . $factura['cliente_apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="usuario_id">Usuario Responsable</label>
                    <select id="usuario_id" name="usuario_id">
                        <option value="">-- Seleccione --</option>
                        <?php foreach($usuarios as $usuario) : ?>
                            <option value="<?php echo $usuario['id']; ?>">
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_pago">Fecha de Pago</label>
                    <input type="date" id="fecha_pago" name="fecha_pago" required>
                </div>

                <div class="form-group">
                    <label for="monto_pagado">Monto Pagado</label>
                    <input type="number" step="0.01" id="monto_pagado" name="monto_pagado" value="0.00" required>
                </div>

                <div class="form-group">
                    <label for="metodo_pago">Método de Pago</label>
                    <select id="metodo_pago" name="metodo_pago">
                        <option value="">-- Seleccione --</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Deposito">Depósito</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="referencia">Referencia</label>
                    <input type="text" id="referencia" name="referencia" placeholder="No. cheque, transferencia, etc.">
                </div>

                <div class="form-group full-width">
                    <label for="observaciones">Observaciones</label>
                    <input type="text" id="observaciones" name="observaciones">
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Pago</button>
                <a href="index.php?action=facturas" class="btn btn-outline">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
