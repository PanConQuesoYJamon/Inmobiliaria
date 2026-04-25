<?php
$pageTitle = 'Editar Usuarios';

ob_start();
?>

<body>
    <p><a href="index.php?action=usuarios">Volver a Usuarios</a></p>

    <form action="index.php?action=usuario_update" method="post">

        <input type="hidden" name="codigo_original" value="<?php echo htmlspecialchars($usuario['codigo'] ?? ''); ?>">

        <label for="codigo">Codigo</label>
        <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($usuario['codigo'] ?? ''); ?>">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usuario['username'] ?? ''); ?>">

        <label for="clave">Clave</label>
        <input type="password" id="clave" name="clave" placeholder="Ingrese nueva clave">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($usuario['estado']?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($usuario['estado']?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>