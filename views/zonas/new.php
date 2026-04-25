<?php
$pageTitle = 'Crear nuevas Zonas';

ob_start();
?>

<body>
    <p><a href="index.php?action=zonas">Volver a Zonas</a></p>

    <form action="index.php?action=zona_create" method="post">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre">

        <label for="municipio">Municipio</label>
        <input type="text" id="municipio" name="municipio">

        <label for="departamento">Departamento</label>
        <input type="text" id="departamento" name="departamento">

        <button type="submit">Guardar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>