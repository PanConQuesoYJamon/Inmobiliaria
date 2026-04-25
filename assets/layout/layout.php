<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Inmobiliaria'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ── Sidebar ─────────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>Inmobiliaria</h1>
        <span>Sistema de gestión</span>
    </div>

    <nav class="sidebar-nav">

        <div class="sidebar-section">Catálogos</div>
        <a href="index.php?action=zonas"          class="<?= (($_GET['action'] ?? '') === 'zonas')          ? 'active' : '' ?>">
            <span class="nav-icon">&#9635;</span> Zonas
        </a>
        <a href="index.php?action=tipos_inmueble"  class="<?= (($_GET['action'] ?? '') === 'tipos_inmueble') ? 'active' : '' ?>">
            <span class="nav-icon">&#9636;</span> Tipos de Inmueble
        </a>
        <a href="index.php?action=tipos_contrato"  class="<?= (($_GET['action'] ?? '') === 'tipos_contrato') ? 'active' : '' ?>">
            <span class="nav-icon">&#9636;</span> Tipos de Contrato
        </a>

        <div class="sidebar-section">Personas</div>
        <a href="index.php?action=propietarios"    class="<?= (($_GET['action'] ?? '') === 'propietarios')   ? 'active' : '' ?>">
            <span class="nav-icon">&#9654;</span> Propietarios
        </a>
        <a href="index.php?action=clientes"        class="<?= (($_GET['action'] ?? '') === 'clientes')       ? 'active' : '' ?>">
            <span class="nav-icon">&#9654;</span> Clientes
        </a>

        <div class="sidebar-section">Operaciones</div>
        <a href="index.php?action=inmuebles"       class="<?= (($_GET['action'] ?? '') === 'inmuebles')      ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span> Inmuebles
        </a>
        <a href="index.php?action=contratos"       class="<?= (($_GET['action'] ?? '') === 'contratos')      ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span> Contratos
        </a>
        <a href="index.php?action=pagos_alquiler"  class="<?= (($_GET['action'] ?? '') === 'pagos_alquiler') ? 'active' : '' ?>">
            <span class="nav-icon">&#9679;</span> Pagos de Alquiler
        </a>

        <div class="sidebar-section">Sistema</div>
        <a href="index.php?action=usuarios"        class="<?= (($_GET['action'] ?? '') === 'usuarios')       ? 'active' : '' ?>">
            <span class="nav-icon">&#9670;</span> Usuarios
        </a>
        <a href="index.php?action=bitacora"        class="<?= (($_GET['action'] ?? '') === 'bitacora')       ? 'active' : '' ?>">
            <span class="nav-icon">&#9670;</span> Bitácora
        </a>

    </nav>
</aside>

<!-- ── Contenido principal ─────────────────────────────── -->
<div class="main-wrapper">

    <header class="topbar">
        <span class="topbar-title"><?php echo $pageTitle ?? ''; ?></span>
        <span class="topbar-user">
            <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?>
        </span>
    </header>

    <main class="page-content">
        <?php echo $content ?? ''; ?>
    </main>

</div>

</body>
</html>
