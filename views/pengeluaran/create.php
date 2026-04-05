<?php /** Create Pengeluaran View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Tambah Pengeluaran</h5>
        <small class="text-muted">Input transaksi pengeluaran kas masjid</small>
    </div>
    <a href="<?= BASE_URL ?>/pengeluaran" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="<?= BASE_URL ?>/pengeluaran/create" enctype="multipart/form-data">
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
                    <label class="form-label-custom">Jumlah (Rp) <span class="text-danger">*</span></label>
                    <input type="text" name="jumlah" class="form-control input-rupiah" required placeholder="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Penerima</label>
                    <input type="text" name="penerima" class="form-control" placeholder="Nama penerima">
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
                    <label class="form-label-custom">Bukti Nota (opsional)</label>
                    <input type="file" name="bukti_nota" class="form-control" accept="image/*,.pdf">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Keterangan pengeluaran">
                </div>
                <div class="col-12">
                    <hr>
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan Pengeluaran</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $extraJs = ""; ?>
