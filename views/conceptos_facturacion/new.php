<?php
$pageTitle = 'Nuevo Concepto de Facturación';
ob_start();
?>

<div class="breadcrumb">
    <a href="index.php?action=conceptos_facturacion" >Conceptos de Facturación</a>
    <span>›</span> Nuevo Concepto
</div>

<div class="page-header">
    <h2>Nuevo Concepto de Facturación</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="index.php?action=concepto_facturacion_create" method="post">

            <div class="form-grid">

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="precio_base">Precio Base</label>
                    <input type="number" step="0.01" id="precio_base" name="precio_base" value="0.00">
                </div>

                <div class="form-group">
                    <label for="aplica_impuesto">Aplica Impuesto</label>
                    <select id="aplica_impuesto" name="aplica_impuesto">
                        <option value="N">No</option>
                        <option value="S">Sí</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion">
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php?action=conceptos_facturacion" class="btn btn-outline">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>
