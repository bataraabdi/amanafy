<?php
/**
 * Profil & Ubah Password View
 */
$user = $user ?? [];
?>

<div class="profil-page">
    <div class="page-header-new mb-4 pb-3 border-bottom">
        <h4 class="mb-1 fw-bold text-gray-800">Profil & Data Diri</h4>
        <p class="text-muted small mb-0">Kelola informasi pribadi, email, dan ganti password Anda di sini.</p>
    </div>

    <div class="row g-4">
        <!-- Informasi Profil (Kiri) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-gray-800 mb-0"><i class="bi bi-person-lines-fill text-primary me-2"></i>Ubah Data Diri</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/profil" method="POST">
                        <?= CSRF::tokenField() ?>
                        <input type="hidden" name="action" value="update_profile">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-gray-600">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg bg-light border-2" value="<?= e($user['username'] ?? '') ?>" readonly>
                            <small class="text-muted">Username digunakan untuk login dan tidak dapat diubah.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-gray-600">Peran / Hak Akses</label>
                            <input type="text" class="form-control form-control-lg bg-light border-2" value="<?= e($_SESSION['role_name'] ?? '') ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-gray-600">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg border-2" value="<?= e($user['nama_lengkap'] ?? '') ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-gray-600">Email Utama</label>
                            <input type="email" name="email" class="form-control form-control-lg border-2" value="<?= e($user['email'] ?? '') ?>" placeholder="contoh@gmail.com">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm rounded-3">Simpan Data Diri</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ubah Password (Kanan) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                    <h5 class="fw-bold text-gray-800 mb-0"><i class="bi bi-shield-lock-fill text-danger me-2"></i>Keamanan & Password</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small mb-4">
                        <i class="bi bi-info-circle me-1"></i> Disarankan untuk menggunakan kombinasi huruf (besar/kecil), angka, dan simbol untuk keamanan maksimal.
                    </div>

                    <form action="<?= BASE_URL ?>/profil" method="POST">
                        <?= CSRF::tokenField() ?>
                        <input type="hidden" name="action" value="update_password">

                        <div class="mb-3 position-relative">
                            <label class="form-label small fw-bold text-gray-600">Password Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                                <input type="password" name="password_lama" class="form-control form-control-lg border-start-0" required id="pwdOld">
                                <button type="button" class="btn btn-outline-secondary border-start-0 border text-muted px-3 pwd-toggle" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label small fw-bold text-gray-600">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password_baru" class="form-control form-control-lg border-start-0" required id="pwdNew" minlength="6">
                                <button type="button" class="btn btn-outline-secondary border-start-0 border text-muted px-3 pwd-toggle" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Minimal 6 karakter.</small>
                        </div>

                        <div class="mb-4 position-relative">
                            <label class="form-label small fw-bold text-gray-600">Ulangi Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-check2-circle"></i></span>
                                <input type="password" name="konfirmasi_password" class="form-control form-control-lg border-start-0" required id="pwdConfirm" minlength="6">
                                <button type="button" class="btn btn-outline-secondary border-start-0 border text-muted px-3 pwd-toggle" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger px-4 py-2 shadow-sm rounded-3">Perbarui Password</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <p class="small text-muted mb-1"><i class="bi bi-clock-history me-1"></i> Terakhir Login: <?= e(date('d F Y', $_SESSION['login_time'] ?? time())) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/Hide Password Toggle Functionality
    const toggleBtns = document.querySelectorAll('.pwd-toggle');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
</script>
