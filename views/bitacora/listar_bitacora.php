<?php
$pageTitle = 'Bitácora del Sistema';
ob_start();
?>

<div class="page-header">
    <div>
        <h2>Bitácora del Sistema</h2>
        <p class="text-muted">Historial de acciones realizadas — solo lectura</p>
    </div>
</div>

<!-- Filtro por tabla -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 24px;">
        <form action="index.php" method="get" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <input type="hidden" name="action" value="bitacora_filtrar">
            <label for="tabla" style="margin:0; font-weight:500;">Filtrar por tabla:</label>
            <select id="tabla" name="tabla" style="width:auto;">
                <option value="">— Todas —</option>
                <option value="usuarios"      <?= ($_GET['tabla'] ?? '') === 'usuarios'      ? 'selected' : '' ?>>Usuarios</option>
                <option value="propietarios"  <?= ($_GET['tabla'] ?? '') === 'propietarios'  ? 'selected' : '' ?>>Propietarios</option>
                <option value="clientes"      <?= ($_GET['tabla'] ?? '') === 'clientes'      ? 'selected' : '' ?>>Clientes</option>
                <option value="inmuebles"     <?= ($_GET['tabla'] ?? '') === 'inmuebles'     ? 'selected' : '' ?>>Inmuebles</option>
                <option value="contratos"     <?= ($_GET['tabla'] ?? '') === 'contratos'     ? 'selected' : '' ?>>Contratos</option>
                <option value="pagos_alquiler"<?= ($_GET['tabla'] ?? '') === 'pagos_alquiler'? 'selected' : '' ?>>Pagos Alquiler</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
            <a href="index.php?action=bitacora" class="btn btn-outline btn-sm">Ver todos</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tabla</th>
                    <th>Acción</th>
                    <th>Datos Antes</th>
                    <th>Datos Después</th>
                    <th>Usuario</th>
                    <th>Fecha y Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($registros as $registro) : ?>
                    <tr>
                        <td class="text-mono"><?php echo htmlspecialchars($registro['id']); ?></td>

                        <td>
                            <span class="badge" style="background:#EEF0F8; color:#3C4891;">
                                <?php echo htmlspecialchars($registro['tabla_afectada']); ?>
                            </span>
                        </td>

                        <td>
                            <?php
                            $accion = $registro['accion'];
                            $badgeStyle = match($accion) {
                                'INSERT' => 'badge-active',
                                'UPDATE' => 'badge-warning',
                                'DELETE' => 'badge-inactive',
                                default  => ''
                            };
                            ?>
                            <span class="badge <?php echo $badgeStyle; ?>">
                                <?php echo htmlspecialchars($accion); ?>
                            </span>
                        </td>

                        <td style="max-width: 280px;">
                            <?php
                            $antes = $registro['datos_antes'] ?? '';
                            if (!empty($antes)) {
                                $decoded = json_decode($antes, true);
                                if ($decoded) {
                                    echo '<div class="datos-json">';
                                    foreach ($decoded as $key => $value) {
                                        echo '<div class="dato-fila">';
                                        echo '<span class="dato-key">' . htmlspecialchars($key) . '</span>';
                                        echo '<span class="dato-value">' . htmlspecialchars($value ?? '—') . '</span>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<span class="text-muted">—</span>';
                                }
                            } else {
                                echo '<span class="text-muted">—</span>';
                            }
                            ?>
                        </td>

                        <td style="max-width: 280px;">
                            <?php
                            $despues = $registro['datos_despues'] ?? '';
                            if (!empty($despues)) {
                                $decoded = json_decode($despues, true);
                                if ($decoded) {
                                    echo '<div class="datos-json">';
                                    foreach ($decoded as $key => $value) {
                                        echo '<div class="dato-fila">';
                                        echo '<span class="dato-key">' . htmlspecialchars($key) . '</span>';
                                        echo '<span class="dato-value">' . htmlspecialchars($value ?? '—') . '</span>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<span class="text-muted">—</span>';
                                }
                            } else {
                                echo '<span class="text-muted">—</span>';
                            }
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars($registro['usuario_nombre'] ?? '—'); ?></td>

                        <td class="text-mono" style="white-space:nowrap;">
                            <?php echo htmlspecialchars($registro['fecha_hora']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Estilos específicos para la bitácora -->
<style>
.datos-json {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 0.8rem;
}

.dato-fila {
    display: flex;
    gap: 6px;
    align-items: baseline;
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 3px;
}

.dato-fila:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.dato-key {
    font-family: var(--font-mono);
    font-size: 0.72rem;
    color: var(--color-text-muted);
    min-width: 80px;
    flex-shrink: 0;
}

.dato-value {
    color: var(--color-text);
    word-break: break-word;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../assets/layout/layout.php';
?>