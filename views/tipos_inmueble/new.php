<?php
$pageTitle = 'Crear un nuevo Tipo de Inmueble';

ob_start();
?>

<body>
    <p><a href="index.php?action=tipos_inmueble" class="btn btn-primary">Volver a Tipos de Inmueble</a></p>

    <form action="index.php?action=tipo_inmueble_create" method="post">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre">

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion">

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>