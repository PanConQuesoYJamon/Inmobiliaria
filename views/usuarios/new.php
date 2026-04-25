<?php
$pageTitle = 'Crear nuevos Usuarios';

ob_start();
?>

<body>
    <p><a href="index.php?action=usuarios">Volver a Usuarios</a></p>

    <!--<a href="index.php?action=new">CREAR NUEVO USUARIO</a> -->

    <form action="index.php?action=usuario_create" method="post">
        
        <label for="codigo">Codigo</label>
        <input type="text" id="codigo" name="codigo">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre">

        <label for="username">Username</label>
        <input type="text" id="username" name="username">

        <label for="clave">Clave</label>
        <input type="text" id="clave" name="clave">

        <button type="submit">Guardar</button>
</form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>