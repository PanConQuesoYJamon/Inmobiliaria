<?php
$pageTitle = 'Editar Zonas';

ob_start();
?>

<body>
    <p><a href="index.php?action=zonas">Volver a Zonas</a></p>

    <form action="index.php?action=zona_update" method="post">

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($zona['id'] ?? ''); ?>">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($zona['nombre'] ?? ''); ?>">

        <label for="municipio">Municipio</label>
        <input type="text" id="municipio" name="municipio" value="<?php echo htmlspecialchars($zona['municipio'] ?? ''); ?>">

        <label for="departamento">Departamento</label>
        <input type="text" id="departamento" name="departamento" value="<?php echo htmlspecialchars($zona['departamento'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($zona['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($zona['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>