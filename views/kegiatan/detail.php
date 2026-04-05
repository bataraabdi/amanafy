<?php
/** @var array $record */
/** @var array $pemasukanList */
/** @var array $pengeluaranList */
/** @var array $accountOptions */

$anggaranAwal = (float)($record['jumlah_anggaran'] ?? 0);
$pemasukanKegiatan = (float)($record['total_pemasukan'] ?? 0);
$totalAnggaran = (float)($record['total_anggaran'] ?? 0);
$totalPengeluaran = (float)($record['total_pengeluaran'] ?? 0);
$sisaAnggaran = (float)($record['sisa_anggaran'] ?? 0);
$accountOptions = $accountOptions ?? [];
?>

<div class="detail-page">
    <div class="detail-page-header">
        <div class="detail-page-heading">
            <span class="detail-page-kicker">Program Kegiatan</span>
            <h5><?= e($record['nama_kegiatan']) ?></h5>
            <p class="detail-page-subtitle">Detail transaksi pemasukan dan pengeluaran untuk kegiatan ini. Pemasukan kegiatan menambah total anggaran, lalu pengeluaran akan mengurangi total anggaran tersebut.</p>
        </div>
        <a href="<?= BASE_URL ?>/kegiatan" class="btn btn-outline-secondary detail-page-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-custom mb-4">
        <div class="card-body-custom">
            <div class="row g-3">
                <div class="col-md-4 col-xl">
                    <div class="text-muted small mb-1">Waktu & Tempat</div>
                    <div class="fw-semibold"><?= e($record['waktu_tempat'] ?? '-') ?></div>
                </div>
                <div class="col-md-4 col-xl">
                    <div class="text-muted small mb-1">Penanggung Jawab (PJ)</div>
                    <div class="fw-semibold"><?= e($record['penanggung_jawab'] ?? '-') ?></div>
                </div>
                <div class="col-md-4 col-xl">
                    <div class="text-muted small mb-1">Sumber Dana</div>
                    <div class="fw-semibold"><?= e($record['sumber_dana'] ?? '-') ?></div>
                </div>
                <div class="col-md-4 col-xl">
                    <div class="text-muted small mb-1">Status Kegiatan</div>
                    <div class="fw-semibold">
                        <span class="badge-status <?= ($record['status'] ?? 'aktif') === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>"><?= ucfirst($record['status'] ?? 'aktif') ?></span>
                    </div>
                </div>
                <div class="col-md-4 col-xl">
                    <div class="text-muted small mb-1">Status Publikasi</div>
                    <div class="fw-semibold">
                        <span class="badge <?= (int)($record['tampil_publik'] ?? 1) === 1 ? 'bg-info' : 'bg-secondary' ?>" style="font-size:0.75rem;"><i class="bi <?= (int)($record['tampil_publik'] ?? 1) === 1 ? 'bi-globe' : 'bi-eye-slash' ?>"></i> <?= (int)($record['tampil_publik'] ?? 1) === 1 ? 'Tampil Publik' : 'Hidden' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 detail-stats-row">
        <div class="col-md-6 col-xl">
            <div class="stat-card info">
                <div class="stat-label">Anggaran Awal</div>
                <div class="stat-value"><?= rupiah($anggaranAwal) ?></div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="stat-card income">
                <div class="stat-label">Pemasukan Kegiatan</div>
                <div class="stat-value income-color"><?= rupiah($pemasukanKegiatan) ?></div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="stat-card info">
                <div class="stat-label">Total Anggaran</div>
                <div class="stat-value"><?= rupiah($totalAnggaran) ?></div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="stat-card expense">
                <div class="stat-label">Pengeluaran</div>
                <div class="stat-value expense-color"><?= rupiah($totalPengeluaran) ?></div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="stat-card balance">
                <div class="stat-label">Sisa Anggaran</div>
                <div class="stat-value balance-color"><?= rupiah($sisaAnggaran) ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-custom detail-table-card">
                <div class="card-header-custom detail-table-header">
                    <div>
                        <h6><i class="bi bi-arrow-down-circle text-success"></i> Pemasukan Kegiatan</h6>
                        <small>Setiap pemasukan di sini akan menambah total anggaran kegiatan.</small>
                    </div>
                    <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalPemasukan">
                        <i class="bi bi-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body-custom p-0">
                    <div class="table-responsive detail-table-scroll">
                        <table class="table-custom detail-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Uraian</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Akun</th>
                                    <th>Petugas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pemasukanList)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pemasukan.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($pemasukanList as $p): ?>
                                    <tr>
                                        <td><?= formatDate($p['tanggal']) ?></td>
                                        <td><?= e($p['uraian']) ?></td>
                                        <td class="text-end fw-bold text-success detail-amount-cell"><?= rupiah((float)$p['jumlah']) ?></td>
                                        <td><?= e($p['nama_akun'] ?? '-') ?></td>
                                        <td><?= e($p['petugas'] ?? '-') ?></td>
                                        <td class="text-center detail-action-cell">
                                            <div class="detail-action-group">
                                                <button type="button" class="btn-action view" data-bs-toggle="modal" data-bs-target="#modalViewPem<?= $p['id'] ?>" title="Lihat detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn-action edit" data-bs-toggle="modal" data-bs-target="#modalEditPem<?= $p['id'] ?>" title="Edit data">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST" action="<?= BASE_URL ?>/kegiatan/delete-pemasukan/<?= $p['id'] ?>" id="del-pem-<?= $p['id'] ?>" class="detail-action-form">
                                                    <?= CSRF::tokenField() ?>
                                                    <button type="button" class="btn-action delete" onclick="confirmDelete('del-pem-<?= $p['id'] ?>')" title="Hapus data">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-custom detail-table-card">
                <div class="card-header-custom detail-table-header">
                    <div>
                        <h6><i class="bi bi-arrow-up-circle text-danger"></i> Pengeluaran</h6>
                        <small>Daftar pengeluaran yang akan mengurangi total anggaran kegiatan.</small>
                    </div>
                    <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalPengeluaran">
                        <i class="bi bi-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body-custom p-0">
                    <div class="table-responsive detail-table-scroll">
                        <table class="table-custom detail-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Penerima</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Akun</th>
                                    <th>Petugas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pengeluaranList)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengeluaran.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($pengeluaranList as $p): ?>
                                    <tr>
                                        <td><?= formatDate($p['tanggal']) ?></td>
                                        <td><?= e($p['penerima'] ?? '-') ?></td>
                                        <td class="text-end fw-bold text-danger detail-amount-cell"><?= rupiah((float)$p['jumlah']) ?></td>
                                        <td><?= e($p['nama_akun'] ?? '-') ?></td>
                                        <td><?= e($p['petugas'] ?? '-') ?></td>
                                        <td class="text-center detail-action-cell">
                                            <div class="detail-action-group">
                                                <button type="button" class="btn-action view" data-bs-toggle="modal" data-bs-target="#modalViewPeng<?= $p['id'] ?>" title="Lihat detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn-action edit" data-bs-toggle="modal" data-bs-target="#modalEditPeng<?= $p['id'] ?>" title="Edit data">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form method="POST" action="<?= BASE_URL ?>/kegiatan/delete-pengeluaran/<?= $p['id'] ?>" id="del-peng-<?= $p['id'] ?>" class="detail-action-form">
                                                    <?= CSRF::tokenField() ?>
                                                    <button type="button" class="btn-action delete" onclick="confirmDelete('del-peng-<?= $p['id'] ?>')" title="Hapus data">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPemasukan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Tambah Input Pemasukan Kegiatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/kegiatan/tambah-pemasukan/<?= $record['id'] ?>" enctype="multipart/form-data">
                <?= CSRF::tokenField() ?>
                <div class="modal-body">
                    <div class="alert alert-light border small">
                        Total anggaran saat ini: <strong><?= rupiah($totalAnggaran) ?></strong>. Input pemasukan di sini akan menambah total anggaran kegiatan.
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Uraian</label>
                        <input type="text" name="uraian" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Jumlah (Rp)</label>
                        <input type="text" name="jumlah" class="form-control input-rupiah" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Fund Category</label>
                        <select name="fund_category" class="form-select" required>
                            <option value="">Pilih fund category</option>
                            <?php foreach (fundCategoryOptions() as $fundOption): ?>
                                <option value="<?= e($fundOption) ?>"><?= e($fundOption) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Akun Kas / Bank</label>
                        <select name="account_ref" class="form-select" required>
                            <option value="">Pilih akun</option>
                            <?php foreach ($accountOptions as $option): ?>
                                <option value="<?= e($option['value']) ?>"><?= e($option['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Bukti Transfer</label>
                        <input type="file" name="bukti_transfer" class="form-control" accept="image/*,.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPengeluaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--border-radius);">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Tambah Pengeluaran Kegiatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/kegiatan/tambah-pengeluaran/<?= $record['id'] ?>" enctype="multipart/form-data">
                <?= CSRF::tokenField() ?>
                <div class="modal-body">
                    <div class="alert alert-light border small">
                        Sisa anggaran saat ini: <strong><?= rupiah($sisaAnggaran) ?></strong> dari total anggaran <strong><?= rupiah($totalAnggaran) ?></strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Jumlah (Rp)</label>
                        <input type="text" name="jumlah" class="form-control input-rupiah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Penerima</label>
                        <input type="text" name="penerima" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Fund Category</label>
                        <select name="fund_category" class="form-select" required>
                            <option value="">Pilih fund category</option>
                            <?php foreach (fundCategoryOptions() as $fundOption): ?>
                                <option value="<?= e($fundOption) ?>"><?= e($fundOption) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Akun Kas / Bank</label>
                        <select name="account_ref" class="form-select" required>
                            <option value="">Pilih akun</option>
                            <?php foreach ($accountOptions as $option): ?>
                                <option value="<?= e($option['value']) ?>"><?= e($option['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Bukti Nota</label>
                        <input type="file" name="bukti_nota" class="form-control" accept="image/*,.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($pemasukanList as $p): ?>
    <div class="modal fade" id="modalViewPem<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: var(--border-radius);">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Detail Pemasukan Kegiatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-sm detail-modal-table mb-0">
                        <tr><td>Tanggal</td><td>: <?= formatDate($p['tanggal']) ?></td></tr>
                        <tr><td>Uraian</td><td>: <?= e($p['uraian']) ?></td></tr>
                        <tr><td>Jumlah</td><td class="fw-bold text-success">: <?= rupiah((float)$p['jumlah']) ?></td></tr>

                        <tr><td>Fund Category</td><td>: <?= e($p['fund_category'] ?? '-') ?></td></tr>
                        <tr><td>Akun</td><td>: <?= e($p['nama_akun'] ?? '-') ?></td></tr>
                        <tr><td>Keterangan</td><td>: <?= e($p['keterangan'] ?? '-') ?></td></tr>
                        <tr><td>Petugas</td><td>: <?= e($p['petugas'] ?? '-') ?></td></tr>
                    </table>
                    <?php if (!empty($p['bukti_transfer'])): ?>
                        <div class="detail-modal-file text-center">
                            <a href="<?= BASE_URL ?>/uploads/bukti/<?= e($p['bukti_transfer']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cloud-arrow-down"></i> Lihat/Unduh Bukti Transfer
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditPem<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: var(--border-radius);">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Pemasukan Kegiatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/kegiatan/update-pemasukan/<?= $p['id'] ?>" enctype="multipart/form-data">
                    <?= CSRF::tokenField() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label-custom">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= e($p['tanggal']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Uraian</label>
                            <input type="text" name="uraian" class="form-control" value="<?= e($p['uraian']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Jumlah (Rp)</label>
                            <input type="text" name="jumlah" class="form-control input-rupiah" value="<?= e(number_format($p['jumlah'], 0, ',', '.')) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Fund Category</label>
                            <select name="fund_category" class="form-select" required>
                                <?php foreach (fundCategoryOptions() as $fundOption): ?>
                                    <option value="<?= e($fundOption) ?>" <?= ($p['fund_category'] ?? '') === $fundOption ? 'selected' : '' ?>><?= e($fundOption) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Akun Kas / Bank</label>
                            <select name="account_ref" class="form-select" required>
                                <?php foreach ($accountOptions as $option): ?>
                                    <option value="<?= e($option['value']) ?>" <?= accountReferenceValue($p['account_type'] ?? '', $p['account_id'] ?? 0) === $option['value'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="<?= e($p['keterangan'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Bukti (Kosongkan jika tidak diubah)</label>
                            <input type="file" name="bukti_transfer" class="form-control" accept="image/*,.pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php foreach ($pengeluaranList as $p): ?>
    <div class="modal fade" id="modalViewPeng<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: var(--border-radius);">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Detail Pengeluaran Kegiatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-sm detail-modal-table mb-0">
                        <tr><td>Tanggal</td><td>: <?= formatDate($p['tanggal']) ?></td></tr>
                        <tr><td>Penerima</td><td>: <?= e($p['penerima'] ?? '-') ?></td></tr>
                        <tr><td>Jumlah</td><td class="fw-bold text-danger">: <?= rupiah((float)$p['jumlah']) ?></td></tr>
                        <tr><td>Fund Category</td><td>: <?= e($p['fund_category'] ?? '-') ?></td></tr>
                        <tr><td>Akun</td><td>: <?= e($p['nama_akun'] ?? '-') ?></td></tr>
                        <tr><td>Keterangan</td><td>: <?= e($p['keterangan'] ?? '-') ?></td></tr>
                        <tr><td>Petugas</td><td>: <?= e($p['petugas'] ?? '-') ?></td></tr>
                    </table>
                    <?php if (!empty($p['bukti_nota'])): ?>
                        <div class="detail-modal-file text-center">
                            <a href="<?= BASE_URL ?>/uploads/bukti/<?= e($p['bukti_nota']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-cloud-arrow-down"></i> Lihat/Unduh Bukti Nota
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditPeng<?= $p['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: var(--border-radius);">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Pengeluaran Kegiatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/kegiatan/update-pengeluaran/<?= $p['id'] ?>" enctype="multipart/form-data">
                    <?= CSRF::tokenField() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label-custom">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= e($p['tanggal']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Jumlah (Rp)</label>
                            <input type="text" name="jumlah" class="form-control input-rupiah" value="<?= e(number_format($p['jumlah'], 0, ',', '.')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Penerima</label>
                            <input type="text" name="penerima" class="form-control" value="<?= e($p['penerima'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Fund Category</label>
                            <select name="fund_category" class="form-select" required>
                                <?php foreach (fundCategoryOptions() as $fundOption): ?>
                                    <option value="<?= e($fundOption) ?>" <?= ($p['fund_category'] ?? '') === $fundOption ? 'selected' : '' ?>><?= e($fundOption) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Akun Kas / Bank</label>
                            <select name="account_ref" class="form-select" required>
                                <?php foreach ($accountOptions as $option): ?>
                                    <option value="<?= e($option['value']) ?>" <?= accountReferenceValue($p['account_type'] ?? '', $p['account_id'] ?? 0) === $option['value'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="<?= e($p['keterangan'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Bukti (Kosongkan jika tidak diubah)</label>
                            <input type="file" name="bukti_nota" class="form-control" accept="image/*,.pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
