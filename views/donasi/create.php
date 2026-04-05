<?php /** Create Donasi */ ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="mb-1 fw-bold">Tambah Program Donasi</h5>
        <small class="text-muted">Lengkapi detail program, kanal donasi, serta link publik yang bisa dibagikan.</small>
    </div>
    <a href="<?= BASE_URL ?>/donasi" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php
$record = $formData ?? [];
$formAction = BASE_URL . '/donasi/create';
$submitLabel = 'Simpan Program';
$submitIcon = 'bi-check-lg';
include BASE_PATH . '/views/donasi/_form.php';
?>
