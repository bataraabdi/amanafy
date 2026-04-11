<?php
/**
 * Login Page
 */
$error = $error ?? null;
$flashError = getFlash('error');
$flashSuccess = getFlash('success');
$error = $error ?? $flashError;
$appSettings = $appSettings ?? [];
$appLogo = trim($appSettings['logo'] ?? '');
$appName = trim($appSettings['nama_masjid'] ?? '') ?: APP_NAME;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Amanafy</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-icon<?= $appLogo !== '' ? ' has-logo' : '' ?>">
                <?php if ($appLogo !== ''): ?>
                    <img src="<?= BASE_URL ?>/uploads/logo/<?= e($appLogo) ?>" alt="Logo <?= e($appName) ?>">
                <?php else: ?>
                    🕌
                <?php endif; ?>
            </div>
            <h3>Amanafy</h3>
            <p class="login-subtitle">Sistem Manajemen Keuangan Masjid</p>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-flash">
                    <i class="bi bi-exclamation-circle"></i> <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login" id="loginForm">
                <?= CSRF::tokenField() ?>
                <?= Security::generateHoneypotField() ?>
                
                <div class="mb-3">
                    <label class="form-label-custom">Username</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid var(--gray-300); border-right:none;"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus style="border-left:none;" autocomplete="username">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom">Password</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius: 8px 0 0 8px; border: 1.5px solid var(--gray-300); border-right:none;"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required style="border-left:none;" autocomplete="current-password">
                        <button type="button" class="input-group-text" onclick="togglePassword()" style="border-radius: 0 8px 8px 0; border: 1.5px solid var(--gray-300); border-left:none; cursor:pointer;">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3" style="font-size:0.95rem;" id="loginBtn">
                    <span class="btn-text"><i class="bi bi-box-arrow-in-right"></i> Masuk</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">Amanafy v<?= APP_VERSION ?> &copy; <?= date('Y') ?></small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($flashSuccess): ?>
    <script>
        Swal.fire({toast:true,position:'top-end',icon:'success',title:'<?= e($flashSuccess) ?>',showConfirmButton:false,timer:3000});
    </script>
    <?php endif; ?>
    <script>
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = loginBtn.querySelector('.btn-text');
        const spinner = loginBtn.querySelector('.spinner-border');

        loginForm.addEventListener('submit', function() {
            loginBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');
        });

        function togglePassword() {
            const pw = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                pw.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</body>
</html>
