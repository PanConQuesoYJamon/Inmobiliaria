<?php
$pageTitle = 'Editar el Tipo de Contrato';

ob_start();
?>

<body>
    <p><a href="index.php?action=tipos_contrato" class="btn btn-primary">Volver a Tipos de Contrato</a></p>

    <form action="index.php?action=tipo_contrato_update" method="post">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($tipo['id'] ?? ''); ?>">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tipo['nombre'] ?? ''); ?>">

        <label for="duracion_meses">Duración (meses)</label>
        <input type="number" id="duracion_meses" name="duracion_meses" value="<?php echo htmlspecialchars($tipo['duracion_meses'] ?? 1); ?>">

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($tipo['descripcion'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($tipo['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($tipo['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit" class="btn btn-edit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>