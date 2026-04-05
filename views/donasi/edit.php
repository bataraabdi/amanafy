<?php /** Edit Donasi */ ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="mb-1 fw-bold">Edit Program Donasi</h5>
        <small class="text-muted">Perbarui isi kampanye, URL publik, dan media pendukung tanpa mengubah transaksi donasinya.</small>
    </div>
    <a href="<?= BASE_URL ?>/donasi" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php
$record = $record ?? [];
$formAction = BASE_URL . '/donasi/edit/' . (int)($record['id'] ?? 0);
$submitLabel = 'Perbarui Program';
$submitIcon = 'bi-save';
include BASE_PATH . '/views/donasi/_form.php';
?>
