<?php
$pageTitle = 'Editar el Tipo de Inmueble';

ob_start();
?>

<body>
    <p><a href="index.php?action=tipos_inmueble" class= "btn btn-primary">Volver a Tipos de Inmueble</a></p>

    <form action="index.php?action=tipo_inmueble_update" method="post">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($tipo['id'] ?? ''); ?>">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($tipo['nombre'] ?? ''); ?>">

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($tipo['descripcion'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($tipo['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($tipo['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit" class = "btn btn-edit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>