<?php
$pageTitle = 'Editar Clientes';

ob_start();
?>

<body>
    <p><a href="index.php?action=clientes" class="btn btn-primary">Volver a Clientes</a></p>

    <form action="index.php?action=cliente_update" method="post">

        <input type="hidden" name="codigo_original" value="<?php echo htmlspecialchars($cliente['codigo'] ?? ''); ?>">

        <label for="codigo">Codigo</label>
        <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($cliente['codigo'] ?? ''); ?>">

        <label for="nombres">Nombres</label>
        <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($cliente['nombres'] ?? ''); ?>">

        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($cliente['apellidos'] ?? ''); ?>">

        <label for="dpi">DPI</label>
        <input type="text" id="dpi" name="dpi" value="<?php echo htmlspecialchars($cliente['dpi'] ?? ''); ?>">

        <label for="nit">NIT</label>
        <input type="text" id="nit" name="nit" value="<?php echo htmlspecialchars($cliente['nit'] ?? ''); ?>">

        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>">

        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($cliente['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($cliente['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit"class="btn btn-primary">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>