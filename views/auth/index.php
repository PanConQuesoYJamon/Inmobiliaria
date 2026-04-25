<?php
$pageTitle = 'Iniciar Sesión';
ob_start();
?>

<div style="max-width:400px; margin: 80px auto;">
    <div class="card">
        <div class="card-header">
            <h3>Iniciar Sesión</h3>
        </div>
        <div class="card-body">

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=auntenticar">

                <!-- token CSRF corregido: input type="hidden", no <hidden> -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>">

                <div class="form-group" style="margin-bottom:16px;">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label for="clave">Contraseña</label>
                    <input type="password" id="clave" name="clave" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">
                    Iniciar Sesión
                </button>

            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
// La vista está en views/auth/ — sube dos niveles para llegar a assets/layout/
include __DIR__ . '/../../assets/layout/layout.php';
?>