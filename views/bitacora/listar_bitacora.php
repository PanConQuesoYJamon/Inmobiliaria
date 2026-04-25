<?php
$pageTitle = 'Bítacora';

ob_start();
?>

<body>
    <p><strong>Bitácora del Sistema</strong> — solo lectura</p>

    <form action="index.php" method="get">
        <input type="hidden" name="action" value="bitacora_filtrar">
        <label for="tabla">Filtrar por tabla:</label>
        <select id="tabla" name="tabla">
            <option value="">-- Todas --</option>
            <option value="propietarios"  <?= ($_GET['tabla'] ?? '') === 'propietarios'  ? 'selected' : '' ?>>Propietarios</option>
            <option value="clientes"      <?= ($_GET['tabla'] ?? '') === 'clientes'      ? 'selected' : '' ?>>Clientes</option>
            <option value="inmuebles"     <?= ($_GET['tabla'] ?? '') === 'inmuebles'     ? 'selected' : '' ?>>Inmuebles</option>
            <option value="contratos"     <?= ($_GET['tabla'] ?? '') === 'contratos'     ? 'selected' : '' ?>>Contratos</option>
            <option value="pagos_alquiler"<?= ($_GET['tabla'] ?? '') === 'pagos_alquiler'? 'selected' : '' ?>>Pagos Alquiler</option>
        </select>
        <button type="submit">Filtrar</button>
        <a href="index.php?action=bitacora">Ver todos</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Tabla</th>
                <th>ID Registro</th>
                <th>Acción</th>
                <th>Datos Antes</th>
                <th>Datos Después</th>
                <th>Usuario</th>
                <th>Fecha y Hora</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($registros as $registro) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($registro['id']); ?></td>
                    <td><?php echo htmlspecialchars($registro['tabla_afectada']); ?></td>
                    <td><?php echo htmlspecialchars($registro['registro_id']); ?></td>
                    <td><?php echo htmlspecialchars($registro['accion']); ?></td>
                    <td><?php echo htmlspecialchars($registro['datos_antes'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($registro['datos_despues'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($registro['usuario_nombre'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($registro['fecha_hora']); ?></td>
                    <td><?php echo htmlspecialchars($registro['ip_usuario'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>