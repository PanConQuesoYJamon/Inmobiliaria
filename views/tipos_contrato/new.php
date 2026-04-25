<?php
$pageTitle = 'Crear nuevos Tipos de Contrato';

ob_start();
?>

<body>
    <p><a href="index.php?action=tipos_contrato">Volver a Tipos de Contrato</a></p>

    <form action="index.php?action=tipo_contrato_create" method="post">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre">

        <label for="duracion_meses">Duración (meses)</label>
        <input type="number" id="duracion_meses" name="duracion_meses" value="1">

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion">

        <button type="submit">Guardar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>