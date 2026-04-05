<?php /** Edit Donatur View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Edit Donatur</h5>
        <small class="text-muted">Perbarui data donatur</small>
    </div>
    <a href="<?= BASE_URL ?>/donatur" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="<?= BASE_URL ?>/donatur/edit/<?= $donatur['id'] ?>">
            <?= CSRF::tokenField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Donatur <span class="text-danger">*</span></label>
                    <input type="text" name="nama_donatur" class="form-control" value="<?= e($donatur['nama_donatur']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= e($donatur['no_hp'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Jenis Donatur</label>
                    <select name="jenis_donatur" class="form-select">
                        <option value="tidak_tetap" <?= $donatur['jenis_donatur'] === 'tidak_tetap' ? 'selected' : '' ?>>Tidak Tetap</option>
                        <option value="tetap" <?= $donatur['jenis_donatur'] === 'tetap' ? 'selected' : '' ?>>Tetap</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= e($donatur['alamat'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3"><?= e($donatur['catatan'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <hr>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Perbarui</button>
                </div>
            </div>
        </form>
    </div>
</div>
