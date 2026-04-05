<?php /** Create Kegiatan */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1 fw-bold">Tambah Kegiatan</h5><small class="text-muted">Buat program kegiatan baru</small></div>
    <a href="<?= BASE_URL ?>/kegiatan" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card-custom">
    <div class="card-body-custom">
        <form method="POST" action="<?= BASE_URL ?>/kegiatan/create" enctype="multipart/form-data">
            <?= CSRF::tokenField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kegiatan" class="form-control" required placeholder="Masukkan nama kegiatan">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Waktu & Tempat <span class="text-danger">*</span></label>
                    <input type="text" name="waktu_tempat" class="form-control" required placeholder="Contoh: Sabtu, 20 Maret 2026 - Aula Masjid">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Penanggung Jawab (PJ) <span class="text-danger">*</span></label>
                    <input type="text" name="penanggung_jawab" class="form-control" required placeholder="Masukkan nama penanggung jawab">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Sumber Dana <span class="text-danger">*</span></label>
                    <input type="text" name="sumber_dana" class="form-control" required placeholder="Contoh: Kas Masjid, Donatur, Sponsor">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Jumlah Anggaran <span class="text-danger">*</span></label>
                    <input type="text" name="jumlah_anggaran" class="form-control input-rupiah" value="0" required>
                    <small class="text-muted">Pemasukan kegiatan nantinya akan menambah total anggaran ini.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Status Kegiatan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="aktif" selected>Aktif (Berjalan)</option>
                        <option value="selesai">Tidak Aktif (Selesai)</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Tampil Publik <span class="text-danger">*</span></label>
                    <select name="tampil_publik" class="form-select" required>
                        <option value="1" selected>Tampil (Dashboard Publik)</option>
                        <option value="0">Tidak Tampil</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Gambar <span class="text-danger">*</span></label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Keterangan <span class="text-danger">*</span></label>
                    <textarea name="keterangan" class="form-control" rows="3" required placeholder="Deskripsi kegiatan..."></textarea>
                </div>
                <div class="col-12"><hr><button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button></div>
            </div>
        </form>
    </div>
</div>
