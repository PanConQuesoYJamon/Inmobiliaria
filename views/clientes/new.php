<?php
$pageTitle = 'Crear nuevos Clientes';

ob_start();
?>

<body>
    <p><a href="index.php?action=clientes">Volver a Clientes</a></p>

    <form action="index.php?action=cliente_create" method="post">

        <label for="codigo">Codigo</label>
        <input type="text" id="codigo" name="codigo">

        <label for="nombres">Nombres</label>
        <input type="text" id="nombres" name="nombres">

        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos">

        <label for="dpi">DPI</label>
        <input type="text" id="dpi" name="dpi">

        <label for="nit">NIT</label>
        <input type="text" id="nit" name="nit">

        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono">

        <label for="email">Email</label>
        <input type="email" id="email" name="email">

        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion">

        <button type="submit">Guardar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>