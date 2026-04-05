<?php /** Edit Pengeluaran View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Edit Pengeluaran</h5>
        <small class="text-muted">Perbarui data transaksi pengeluaran</small>
    </div>
    <a href="<?= BASE_URL ?>/pengeluaran" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="<?= BASE_URL ?>/pengeluaran/edit/<?= $record['id'] ?>" enctype="multipart/form-data">
            <?= CSRF::tokenField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" value="<?= e($record['tanggal']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select" required>
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= $record['kategori_id'] == $k['id'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="text" name="jumlah" class="form-control input-rupiah" value="<?= formatNumber((float)$record['jumlah']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Penerima</label>
                    <input type="text" name="penerima" class="form-control" value="<?= e($record['penerima'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Akun Kas / Bank <span class="text-danger">*</span></label>
                    <select name="account_ref" class="form-select" required>
                        <?php foreach (($accountOptions ?? []) as $option): ?>
                            <option value="<?= e($option['value']) ?>" <?= accountReferenceValue($record['account_type'] ?? '', $record['account_id'] ?? 0) === $option['value'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Bukti Nota</label>
                    <input type="file" name="bukti_nota" class="form-control" accept="image/*,.pdf">
                    <?php if (!empty($record['bukti_nota'])): ?>
                        <small class="text-muted">File saat ini: <?= e($record['bukti_nota']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" value="<?= e($record['keterangan'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <hr>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
