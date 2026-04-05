<?php /** Create Donatur View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Tambah Donatur</h5>
        <small class="text-muted">Isi data donatur baru</small>
    </div>
    <a href="<?= BASE_URL ?>/donatur" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="<?= BASE_URL ?>/donatur/create">
            <?= CSRF::tokenField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Donatur <span class="text-danger">*</span></label>
                    <input type="text" name="nama_donatur" class="form-control" required placeholder="Nama lengkap donatur">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">No HP</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Jenis Donatur</label>
                    <select name="jenis_donatur" class="form-select">
                        <option value="tidak_tetap">Tidak Tetap</option>
                        <option value="tetap">Tetap</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Alamat</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Alamat donatur">
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>
                <div class="col-12">
                    <hr>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
