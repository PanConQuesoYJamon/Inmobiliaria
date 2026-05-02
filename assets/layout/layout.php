<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Inmobiliaria'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ── Sidebar colapsable ─────────────────────────────── */
        .nav-group {}

        .nav-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 20px;
            cursor: pointer;
            user-select: none;
            border-left: 3px solid transparent;
            transition: background 0.15s;
        }

        .nav-group-header:hover {
            background: rgba(255,255,255,0.06);
        }

        .nav-group-header .nav-group-label {
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
        }

        .nav-group-header .nav-arrow {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.35);
            transition: transform 0.2s;
        }

        .nav-group.open .nav-arrow {
            transform: rotate(180deg);
        }

        .nav-group-items {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.25s ease;
        }

        .nav-group.open .nav-group-items {
            max-height: 500px;
        }

        .nav-group-items a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px 8px 28px;
            font-size: 0.855rem;
            color: rgba(255,255,255,0.7);
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }

        .nav-group-items a:hover,
        .nav-group-items a.active {
            background: rgba(255,255,255,0.08);
            color: #FFFFFF;
            border-left-color: var(--color-accent);
        }

        .nav-group-items a .nav-icon {
            font-size: 0.85rem;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        /* Enlace directo sin grupo (logout, etc.) */
        .sidebar-nav > a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.75);
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav > a:hover {
            background: rgba(255,255,255,0.08);
            color: #FFFFFF;
            border-left-color: var(--color-accent);
        }
    </style>
</head>
<body>

<!-- ── Sidebar ─────────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>Inmobiliaria</h1>
        <span>Sistema de gestión</span>
    </div>

    <nav class="sidebar-nav">

        <?php
        $accion = $_GET['action'] ?? '';

        // Helper para marcar enlace activo
        $activo = fn($a) => $accion === $a ? 'active' : '';

        // Helper para abrir grupo si la acción actual pertenece a él
        $grupoAbierto = fn(array $acciones) =>
            in_array($accion, $acciones) ? 'open' : '';
        ?>

        <!-- ── Catálogos ── -->
        <div class="nav-group <?= $grupoAbierto(['zonas','zona_new','zona_edit','tipos_inmueble','tipo_inmueble_new','tipo_inmueble_edit','tipos_contrato','tipo_contrato_new','tipo_contrato_edit']) ?>">
            <div class="nav-group-header" onclick="toggleGrupo(this)">
                <span class="nav-group-label">Catálogos</span>
                <span class="nav-arrow">▼</span>
            </div>
            <div class="nav-group-items">
                <a href="index.php?action=zonas"          class="<?= $activo('zonas') ?>">
                    <span class="nav-icon">◻</span> Zonas
                </a>
                <a href="index.php?action=tipos_inmueble"  class="<?= $activo('tipos_inmueble') ?>">
                    <span class="nav-icon">◻</span> Tipos de Inmueble
                </a>
                <a href="index.php?action=tipos_contrato"  class="<?= $activo('tipos_contrato') ?>">
                    <span class="nav-icon">◻</span> Tipos de Contrato
                </a>
            </div>
        </div>

        <!-- ── Personas ── -->
        <div class="nav-group <?= $grupoAbierto(['propietarios','propietario_new','propietario_edit','clientes','cliente_new','cliente_edit']) ?>">
            <div class="nav-group-header" onclick="toggleGrupo(this)">
                <span class="nav-group-label">Personas</span>
                <span class="nav-arrow">▼</span>
            </div>
            <div class="nav-group-items">
                <a href="index.php?action=propietarios" class="<?= $activo('propietarios') ?>">
                    <span class="nav-icon">▶</span> Propietarios
                </a>
                <a href="index.php?action=clientes"     class="<?= $activo('clientes') ?>">
                    <span class="nav-icon">▶</span> Clientes
                </a>
            </div>
        </div>

        <!-- ── Operaciones ── -->
        <div class="nav-group <?= $grupoAbierto(['inmuebles','inmueble_new','inmueble_edit','contratos','contrato_new','contrato_edit','pagos_alquiler','pago_new','pago_edit']) ?>">
            <div class="nav-group-header" onclick="toggleGrupo(this)">
                <span class="nav-group-label">Operaciones</span>
                <span class="nav-arrow">▼</span>
            </div>
            <div class="nav-group-items">
                <a href="index.php?action=inmuebles"      class="<?= $activo('inmuebles') ?>">
                    <span class="nav-icon">●</span> Inmuebles
                </a>
                <a href="index.php?action=contratos"      class="<?= $activo('contratos') ?>">
                    <span class="nav-icon">●</span> Contratos
                </a>
                <a href="index.php?action=pagos_alquiler" class="<?= $activo('pagos_alquiler') ?>">
                    <span class="nav-icon">●</span> Pagos de Alquiler
                </a>
            </div>
        </div>

        <!-- ── Facturación ── -->
        <div class="nav-group <?= $grupoAbierto(['facturas','factura_new','factura_edit','factura_show','pagos_factura','pago_factura_new','conceptos_facturacion','concepto_facturacion_new','concepto_facturacion_edit']) ?>">
            <div class="nav-group-header" onclick="toggleGrupo(this)">
                <span class="nav-group-label">Facturación</span>
                <span class="nav-arrow">▼</span>
            </div>
            <div class="nav-group-items">
                <a href="index.php?action=facturas"       class="<?= $activo('facturas') ?>">
                    <span class="nav-icon">◆</span> Facturas
                </a>
                <a href="index.php?action=pagos_factura"  class="<?= $activo('pagos_factura') ?>">
                    <span class="nav-icon">◆</span> Pagos de Facturas
                </a>
                <?php if (in_array($_SESSION['usuario_rol'] ?? '', ['admin', 'supervisor'])): ?>
                <a href="index.php?action=conceptos_facturacion" class="<?= $activo('conceptos_facturacion') ?>">
                    <span class="nav-icon">◆</span> Conceptos
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Sistema (solo admin y supervisor) ── -->
        <?php if (in_array($_SESSION['usuario_rol'] ?? '', ['admin', 'supervisor'])): ?>
        <div class="nav-group <?= $grupoAbierto(['usuarios','usuario_new','usuario_edit','bitacora','bitacora_filtrar']) ?>">
            <div class="nav-group-header" onclick="toggleGrupo(this)">
                <span class="nav-group-label">Sistema</span>
                <span class="nav-arrow">▼</span>
            </div>
            <div class="nav-group-items">
                <a href="index.php?action=usuarios" class="<?= $activo('usuarios') ?>">
                    <span class="nav-icon">◈</span> Usuarios
                </a>
                <a href="index.php?action=bitacora" class="<?= $activo('bitacora') ?>">
                    <span class="nav-icon">◈</span> Bitácora
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Cerrar sesión ── -->
        <a href="index.php?action=logout" style="margin-top:8px; border-top: 1px solid rgba(255,255,255,0.08); padding-top:12px;">
            <span class="nav-icon">⏻</span> Cerrar Sesión
        </a>

    </nav>
</aside>

<!-- ── Contenido principal ─────────────────────────────── -->
<div class="main-wrapper">

    <header class="topbar">
        <span class="topbar-title"><?php echo $pageTitle ?? ''; ?></span>
        <span class="topbar-user">
            <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?>
            &nbsp;·&nbsp;
            <span style="font-size:0.78rem; opacity:0.7;">
                <?php echo htmlspecialchars($_SESSION['usuario_rol'] ?? ''); ?>
            </span>
        </span>
    </header>

    <main class="page-content">
        <?php echo $content ?? ''; ?>
    </main>

</div>

<script>
function toggleGrupo(header) {
    const grupo = header.closest('.nav-group');
    grupo.classList.toggle('open');
}
</script>

</body>
</html>