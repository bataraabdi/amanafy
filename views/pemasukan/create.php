<?php /** Create Pemasukan View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Tambah Pemasukan</h5>
        <small class="text-muted">Input transaksi pemasukan kas masjid</small>
    </div>
    <a href="<?= BASE_URL ?>/pemasukan" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="<?= BASE_URL ?>/pemasukan/create" enctype="multipart/form-data">
            <?= CSRF::tokenField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= $k['id'] ?>" data-fund="<?= e($k['fund_category'] ?? '') ?>"><?= e($k['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Donatur Terdaftar</label>
                    <select name="donatur_id" class="form-select">
                        <option value="">-- Pilih Donatur (opsional) --</option>
                        <?php foreach ($donaturList as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= e($d['nama_donatur']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Atau Nama Donatur Manual</label>
                    <input type="text" name="nama_donatur_manual" class="form-control" placeholder="Nama donatur jika tidak terdaftar">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="text" name="jumlah" class="form-control input-rupiah" required placeholder="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Akun Kas / Bank <span class="text-danger">*</span></label>
                    <select name="account_ref" class="form-select" required>
                        <option value="">Pilih akun</option>
                        <?php foreach (($accountOptions ?? []) as $option): ?>
                            <option value="<?= e($option['value']) ?>"><?= e($option['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Bukti Transfer (opsional)</label>
                    <input type="file" name="bukti_transfer" class="form-control" accept="image/*,.pdf">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Keterangan tambahan">
                </div>
                <div class="col-12">
                    <hr>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan Pemasukan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $extraJs = ""; ?>
