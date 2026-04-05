<?php /** Settings View - Enhanced UI */ $s = $settings; ?>
<div class="settings-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Pengaturan Aplikasi</h5>
            <p class="text-muted small mb-0">Kelola identitas masjid dan kategori transaksi keuangan</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card-custom sticky-top" style="top: 1.5rem;">
                <div class="card-body-custom p-2">
                    <div class="nav flex-column nav-pills settings-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-umum" type="button" role="tab">
                            <i class="bi bi-gear-fill me-2"></i> Identitas Umum
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-pengurus" type="button" role="tab">
                            <i class="bi bi-people-fill me-2"></i> Pengurus
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-kat-masuk" type="button" role="tab">
                            <i class="bi bi-arrow-down-circle-fill me-2"></i> Kategori Pemasukan
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-kat-keluar" type="button" role="tab">
                            <i class="bi bi-arrow-up-circle-fill me-2"></i> Kategori Pengeluaran
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- Tab Umum -->
                <div class="tab-pane fade show active" id="tab-umum" role="tabpanel">
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2 text-primary"></i> Data Identitas</h6>
                        </div>
                        <div class="card-body-custom">
                            <form method="POST" action="<?= BASE_URL ?>/settings" enctype="multipart/form-data">
                                <?= CSRF::tokenField() ?>
                                <input type="hidden" name="action" value="update_umum">
                                
                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label-custom">Nama Masjid / Lembaga</label>
                                            <input type="text" name="nama_masjid" class="form-control form-control-lg fw-bold" value="<?= e($s['nama_masjid'] ?? '') ?>" required>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label-custom">Jenis Lembaga</label>
                                                <select name="jenis_lembaga" class="form-select">
                                                    <option value="masjid" <?= ($s['jenis_lembaga'] ?? '') === 'masjid' ? 'selected' : '' ?>>Masjid</option>
                                                    <option value="lembaga" <?= ($s['jenis_lembaga'] ?? '') === 'lembaga' ? 'selected' : '' ?>>Lembaga</option>
                                                    <option value="dakwah" <?= ($s['jenis_lembaga'] ?? '') === 'dakwah' ? 'selected' : '' ?>>Dakwah / Yayasan</option>
                                                    <option value="sosial" <?= ($s['jenis_lembaga'] ?? '') === 'sosial' ? 'selected' : '' ?>>Sosial / Kemanusiaan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">Status Aplikasi</label>
                                                <select name="status_lembaga" class="form-select">
                                                    <option value="aktif" <?= ($s['status_lembaga'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                    <option value="nonaktif" <?= ($s['status_lembaga'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Maintenance / Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label class="form-label-custom d-block">Logo Masjid</label>
                                        <div class="logo-preview-wrapper mb-2">
                                            <?php if (!empty($s['logo'])): ?>
                                                <img src="<?= BASE_URL ?>/uploads/logo/<?= e($s['logo']) ?>" alt="Logo" class="img-thumbnail" style="max-height: 120px; border-radius: 12px;">
                                            <?php else: ?>
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="height: 120px; width: 120px; margin: 0 auto;">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                                        <small class="text-muted mt-1 d-block">Ukuran kotak direkomendasikan</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Raya No. 123..."><?= e($s['alamat'] ?? '') ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nomor Telepon / WhatsApp</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
                                            <input type="text" name="no_telepon" class="form-control" value="<?= e($s['no_telepon'] ?? '') ?>" placeholder="0812...">
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary-custom px-4 py-2">
                                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab Pengurus -->
                <div class="tab-pane fade" id="tab-pengurus" role="tabpanel">
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i> Data Pengurus</h6>
                        </div>
                        <div class="card-body-custom">
                            <form method="POST" action="<?= BASE_URL ?>/settings">
                                <?= CSRF::tokenField() ?>
                                <input type="hidden" name="action" value="update_pengurus">
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-custom">Jabatan Ketua</label>
                                            <input type="text" name="ketua_jabatan" class="form-control" value="<?= e($s['ketua_jabatan'] ?? 'Ketua') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Nama Ketua</label>
                                            <input type="text" name="ketua_nama" class="form-control fw-bold" value="<?= e($s['ketua_nama'] ?? '') ?>" placeholder="Nama lengkap ketua" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-custom">Jabatan Sekretaris</label>
                                            <input type="text" name="sekretaris_jabatan" class="form-control" value="<?= e($s['sekretaris_jabatan'] ?? 'Sekretaris') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Nama Sekretaris</label>
                                            <input type="text" name="sekretaris_nama" class="form-control fw-bold" value="<?= e($s['sekretaris_nama'] ?? '') ?>" placeholder="Nama lengkap sekretaris" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label-custom">Jabatan Bendahara</label>
                                            <input type="text" name="bendahara_jabatan" class="form-control" value="<?= e($s['bendahara_jabatan'] ?? 'Bendahara') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-custom">Nama Bendahara</label>
                                            <input type="text" name="bendahara_nama" class="form-control fw-bold" value="<?= e($s['bendahara_nama'] ?? '') ?>" placeholder="Nama lengkap bendahara" required>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary-custom px-4 py-2">
                                            <i class="bi bi-save me-2"></i> Simpan Pengurus
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab Kategori Pemasukan -->
                <div class="tab-pane fade" id="tab-kat-masuk" role="tabpanel">
                    <div class="card-custom mb-4">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-success"></i> Tambah Kategori Pemasukan</h6>
                        </div>
                        <div class="card-body-custom">
                            <form method="POST" action="<?= BASE_URL ?>/settings">
                                <?= CSRF::tokenField() ?>
                                <input type="hidden" name="action" value="add_kategori_pemasukan">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Nama Kategori</label>
                                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Infaq Jumat" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">Fund Category (ISAK 35)</label>
                                        <select name="fund_category" class="form-select" required>
                                            <?php foreach(fundCategoryOptions() as $opt): ?>
                                                <option value="<?= $opt ?>"><?= $opt ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                                    </div>
                                    <div class="col-md-2 align-self-end">
                                        <button type="submit" class="btn btn-success w-100 py-2">
                                            <i class="bi bi-plus-lg"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-list-task me-2 text-primary"></i> Daftar Kategori Pemasukan</h6>
                        </div>
                        <div class="card-body-custom p-0">
                            <div class="table-responsive">
                                <table class="table-custom align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Nama Kategori</th>
                                            <th>Struktur Dana (ISAK 35)</th>
                                            <th>Keterangan</th>
                                            <th width="120" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($katPemasukan)): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kategori pemasukan.</td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($katPemasukan as $k): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= e($k['nama_kategori']) ?></td>
                                                <td>
                                                    <span class="badge border <?= $k['fund_category'] === 'Terikat' ? 'bg-info-subtle text-info border-info' : 'bg-success-subtle text-success border-success' ?> rounded-pill">
                                                        <?= e($k['fund_category'] ?? 'Tidak Terikat') ?>
                                                    </span>
                                                </td>
                                                <td class="small text-muted"><?= e($k['keterangan'] ?? '-') ?></td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <button type="button" class="btn-action edit" data-bs-toggle="modal" data-bs-target="#modalKatMasuk<?= $k['id'] ?>" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form method="POST" action="<?= BASE_URL ?>/settings" id="del-kat-masuk-<?= $k['id'] ?>">
                                                            <?= CSRF::tokenField() ?>
                                                            <input type="hidden" name="action" value="delete_kategori_pemasukan">
                                                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                            <button type="button" class="btn-action delete" onclick="confirmDelete('del-kat-masuk-<?= $k['id'] ?>')" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Pemasukan -->
                                            <div class="modal fade" id="modalKatMasuk<?= $k['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Kategori Pemasukan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="<?= BASE_URL ?>/settings">
                                                            <?= CSRF::tokenField() ?>
                                                            <input type="hidden" name="action" value="edit_kategori_pemasukan">
                                                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                            <div class="modal-body py-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label-custom">Nama Kategori</label>
                                                                    <input type="text" name="nama_kategori" class="form-control" value="<?= e($k['nama_kategori']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label-custom">Fund Category (ISAK 35)</label>
                                                                    <select name="fund_category" class="form-select" required>
                                                                        <?php foreach(fundCategoryOptions() as $opt): ?>
                                                                            <option value="<?= $opt ?>" <?= ($k['fund_category'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-0">
                                                                    <label class="form-label-custom">Keterangan</label>
                                                                    <textarea name="keterangan" class="form-control" rows="2"><?= e($k['keterangan'] ?? '') ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 pt-0">
                                                                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary-custom px-4">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Kategori Pengeluaran -->
                <div class="tab-pane fade" id="tab-kat-keluar" role="tabpanel">
                    <div class="card-custom mb-4">
                        <div class="card-header-custom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-danger"></i> Tambah Kategori Pengeluaran</h6>
                        </div>
                        <div class="card-body-custom">
                            <form method="POST" action="<?= BASE_URL ?>/settings">
                                <?= CSRF::tokenField() ?>
                                <input type="hidden" name="action" value="add_kategori_pengeluaran">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label-custom">Nama Kategori</label>
                                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Listrik & Air" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">Fund Category (ISAK 35)</label>
                                        <select name="fund_category" class="form-select" required>
                                            <?php foreach(fundCategoryOptions() as $opt): ?>
                                                <option value="<?= $opt ?>"><?= $opt ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                                    </div>
                                    <div class="col-md-2 align-self-end">
                                        <button type="submit" class="btn btn-danger w-100 py-2">
                                            <i class="bi bi-plus-lg"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-list-task me-2 text-primary"></i> Daftar Kategori Pengeluaran</h6>
                        </div>
                        <div class="card-body-custom p-0">
                            <div class="table-responsive">
                                <table class="table-custom align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Nama Kategori</th>
                                            <th>Struktur Dana (ISAK 35)</th>
                                            <th>Keterangan</th>
                                            <th width="120" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($katPengeluaran)): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kategori pengeluaran.</td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($katPengeluaran as $k): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= e($k['nama_kategori']) ?></td>
                                                <td>
                                                    <span class="badge border <?= $k['fund_category'] === 'Terikat' ? 'bg-info-subtle text-info border-info' : 'bg-success-subtle text-success border-success' ?> rounded-pill">
                                                        <?= e($k['fund_category'] ?? 'Tidak Terikat') ?>
                                                    </span>
                                                </td>
                                                <td class="small text-muted"><?= e($k['keterangan'] ?? '-') ?></td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <button type="button" class="btn-action edit" data-bs-toggle="modal" data-bs-target="#modalKatKeluar<?= $k['id'] ?>" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form method="POST" action="<?= BASE_URL ?>/settings" id="del-kat-keluar-<?= $k['id'] ?>">
                                                            <?= CSRF::tokenField() ?>
                                                            <input type="hidden" name="action" value="delete_kategori_pengeluaran">
                                                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                            <button type="button" class="btn-action delete" onclick="confirmDelete('del-kat-keluar-<?= $k['id'] ?>')" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Pengeluaran -->
                                            <div class="modal fade" id="modalKatKeluar<?= $k['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Kategori Pengeluaran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="<?= BASE_URL ?>/settings">
                                                            <?= CSRF::tokenField() ?>
                                                            <input type="hidden" name="action" value="edit_kategori_pengeluaran">
                                                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                            <div class="modal-body py-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label-custom">Nama Kategori</label>
                                                                    <input type="text" name="nama_kategori" class="form-control" value="<?= e($k['nama_kategori']) ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label-custom">Fund Category (ISAK 35)</label>
                                                                    <select name="fund_category" class="form-select" required>
                                                                        <?php foreach(fundCategoryOptions() as $opt): ?>
                                                                            <option value="<?= $opt ?>" <?= ($k['fund_category'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-0">
                                                                    <label class="form-label-custom">Keterangan</label>
                                                                    <textarea name="keterangan" class="form-control" rows="2"><?= e($k['keterangan'] ?? '') ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 pt-0">
                                                                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary-custom px-4">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-nav .nav-link {
    text-align: left;
    padding: 12px 20px;
    border-radius: 12px;
    color: var(--gray-700);
    font-weight: 500;
    margin-bottom: 5px;
    transition: all 0.2s;
}
.settings-nav .nav-link i {
    font-size: 1.1rem;
}
.settings-nav .nav-link:hover {
    background-color: var(--bs-light);
}
.settings-nav .nav-link.active {
    background: var(--primary);
    color: white !important;
    box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.2);
}
.logo-preview-wrapper {
    position: relative;
    display: inline-block;
}
.badge-subtle {
    padding: 6px 14px;
    font-size: 0.75rem;
    font-weight: 600;
}
</style>
