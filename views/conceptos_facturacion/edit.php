<?php
$pageTitle = 'Editar Concepto de Facturación';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=conceptos_facturacion" >Conceptos de Facturación</a>
    <span>›</span> Editar Concepto
</div>

<div class="page-header">
    <h2>Editar Concepto de Facturación</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=concepto_facturacion_update" method="post">

            <input type="hidden" name="id" value="<?php echo htmlspecialchars($concepto['id'] ?? ''); ?>">

            <div class="form-grid">

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                           value="<?php echo htmlspecialchars($concepto['nombre'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="precio_base">Precio Base</label>
                    <input type="number" step="0.01" id="precio_base" name="precio_base"
                           value="<?php echo htmlspecialchars($concepto['precio_base'] ?? '0.00'); ?>">
                </div>

                <div class="form-group">
                    <label for="aplica_impuesto">Aplica Impuesto</label>
                    <select id="aplica_impuesto" name="aplica_impuesto">
                        <option value="N" <?= ($concepto['aplica_impuesto'] ?? '') === 'N' ? 'selected' : '' ?>>No</option>
                        <option value="S" <?= ($concepto['aplica_impuesto'] ?? '') === 'S' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <option value="A" <?= ($concepto['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
                        <option value="I" <?= ($concepto['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion"
                           value="<?php echo htmlspecialchars($concepto['descripcion'] ?? ''); ?>">
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php?action=conceptos_facturacion" class="btn btn-outline">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
