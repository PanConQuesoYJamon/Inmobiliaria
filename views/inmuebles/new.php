<?php
$pageTitle = 'Crear nuevos Clientes';

ob_start();
?>

<body>
    <p><a href="index.php?action=inmuebles">Volver a Inmuebles</a></p>

    <form action="index.php?action=inmueble_create" method="post">

        <label for="codigo">Codigo</label>
        <input type="text" id="codigo" name="codigo">

        <label for="propietario_id">Propietario</label>
        <select id="propietario_id" name="propietario_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($propietarios as $propietario) : ?>
                <option value="<?php echo $propietario['id']; ?>">
                    <?php echo htmlspecialchars($propietario['nombres'] . ' ' . $propietario['apellidos']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tipo_inmueble_id">Tipo de Inmueble</label>
        <select id="tipo_inmueble_id" name="tipo_inmueble_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($tipos as $tipo) : ?>
                <option value="<?php echo $tipo['id']; ?>">
                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="zona_id">Zona</label>
        <select id="zona_id" name="zona_id">
            <option value="">-- Seleccione --</option>
            <?php foreach($zonas as $zona) : ?>
                <option value="<?php echo $zona['id']; ?>">
                    <?php echo htmlspecialchars($zona['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion">

        <label for="precio_alquiler">Precio de Alquiler</label>
        <input type="number" step="0.01" id="precio_alquiler" name="precio_alquiler">

        <label for="habitaciones">Habitaciones</label>
        <input type="number" id="habitaciones" name="habitaciones" value="0">

        <label for="banos">Baños</label>
        <input type="number" id="banos" name="banos" value="0">

        <label for="metros_cuadrados">Metros Cuadrados</label>
        <input type="number" step="0.01" id="metros_cuadrados" name="metros_cuadrados">

        <label for="direccion">Dirección</label>
        <input type="text" id="direccion" name="direccion">

        <button type="submit">Guardar</button>
    </form>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>