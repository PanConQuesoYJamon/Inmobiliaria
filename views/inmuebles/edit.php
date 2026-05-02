<?php
$pageTitle = 'Editar Inmuebles';

ob_start();
?>

<body>
    <p><a href="index.php?action=inmuebles"class="btn btn-primary">Volver a Inmuebles</a></p>

    <form action="index.php?action=inmueble_update" method="post">

        <input type="hidden" name="codigo_original" value="<?php echo htmlspecialchars($inmueble['codigo'] ?? ''); ?>">

        <label for="codigo">Codigo</label>
        <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($inmueble['codigo'] ?? ''); ?>">

        <label for="propietario_id">Propietario</label>
        <select id="propietario_id" name="propietario_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($propietarios as $propietario) : ?>
                <option value="<?php echo $propietario['id']; ?>"
                    <?= $propietario['id'] == ($inmueble['propietario_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($propietario['nombres'] . ' ' . $propietario['apellidos']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tipo_inmueble_id">Tipo de Inmueble</label>
        <select id="tipo_inmueble_id" name="tipo_inmueble_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($tipos as $tipo) : ?>
                <option value="<?php echo $tipo['id']; ?>"
                    <?= $tipo['id'] == ($inmueble['tipo_inmueble_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="zona_id">Zona</label>
        <select id="zona_id" name="zona_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($zonas as $zona) : ?>
                <option value="<?php echo $zona['id']; ?>"
                    <?= $zona['id'] == ($inmueble['zona_id'] ?? 0) ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($zona['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo htmlspecialchars($inmueble['descripcion'] ?? ''); ?>">

        <label for="precio_alquiler">Precio de Alquiler</label>
        <input type="number" step="0.01" id="precio_alquiler" name="precio_alquiler" value="<?php echo htmlspecialchars($inmueble['precio_alquiler'] ?? ''); ?>">

        <label for="habitaciones">Habitaciones</label>
        <input type="number" id="habitaciones" name="habitaciones" value="<?php echo htmlspecialchars($inmueble['habitaciones'] ?? 0); ?>">

        <label for="banos">Baños</label>
        <input type="number" id="banos" name="banos" value="<?php echo htmlspecialchars($inmueble['banos'] ?? 0); ?>">

        <label for="metros_cuadrados">Metros Cuadrados</label>
        <input type="number" step="0.01" id="metros_cuadrados" name="metros_cuadrados" value="<?php echo htmlspecialchars($inmueble['metros_cuadrados'] ?? ''); ?>">

        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($inmueble['direccion'] ?? ''); ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado">
            <option value="A" <?= ($inmueble['estado'] ?? '') === 'A' ? 'selected' : '' ?>>Activo</option>
            <option value="I" <?= ($inmueble['estado'] ?? '') === 'I' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <button type="submit" class = "btn btn-edit">Actualizar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>