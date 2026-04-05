<?php
/** @var array $record */
/** @var array $pemasukanList */
/** @var array $pengeluaranList */
/** @var array $dokumentasiFiles */
/** @var string $shareUrl */
/** @var array $accountOptions */

$saldo = ((float)($record['total_pemasukan'] ?? 0)) - ((float)($record['total_pengeluaran'] ?? 0));
$progress = $record['target_nominal'] > 0
    ? min(100, round(($record['total_pemasukan'] / $record['target_nominal']) * 100))
    : 0;
$deadlineLabel = !empty($record['deadline']) ? formatDate($record['deadline']) : 'Tanpa deadline';
$plainDescription = htmlToPlainText($record['deskripsi_lengkap'] ?? ($record['uraian'] ?? ''));
$accountOptions = $accountOptions ?? [];
?>

<div class="detail-page">
    <div class="detail-page-header">
        <div class="detail-page-heading">
            <span class="detail-page-kicker">Program Donasi</span>
            <h5><?= e($record['nama_donasi']) ?></h5>
            <p class="detail-page-subtitle">Pantau progres pengumpulan dana, kelengkapan publikasi, dan seluruh transaksi program ini.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <?php if (!empty($shareUrl)): ?>
                <button type="button" class="btn btn-outline-secondary detail-page-back" onclick='copyTextValue(<?= json_encode($shareUrl) ?>)'>
                    <i class="bi bi-copy"></i> Copy Link
                </button>
                <a href="<?= e($shareUrl) ?>" target="_blank" class="btn btn-primary-custom detail-page-back">
                    <i class="bi bi-box-arrow-up-right"></i> Buka Halaman Publik
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/donasi" class="btn btn-outline-secondary detail-page-back">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card-custom detail-progress-card">
        <div class="card-body-custom">
            <div class="detail-progress-head">
                <span class="fw-bold">Progress Donasi</span>
                <span class="detail-progress-value"><?= $progress ?>%</span>
            </div>
            <div class="progress-custom">
                <div class="progress-bar-custom" style="width: <?= $progress ?>%"></div>
            </div>
            <div class="detail-progress-meta">
                <span>Terkumpul: <strong class="text-success"><?= rupiah((float)$record['total_pemasukan']) ?></strong></span>
                <span>Target: <strong><?= rupiah((float)$record['target_nominal']) ?></strong></span>
            </div>
        </div>
    </div>

    <div class="row g-3 detail-stats-row">
        <div class="col-md-3">
            <div class="stat-card income">
                <div class="stat-label">Pemasukan</div>
                <div class="stat-value income-color"><?= rupiah((float)($record['total_pemasukan'] ?? 0)) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card expense">
                <div class="stat-label">Pengeluaran</div>
                <div class="stat-value expense-color"><?= rupiah((float)($record['total_pengeluaran'] ?? 0)) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card balance">
                <div class="stat-label">Saldo</div>
                <div class="stat-value balance-color"><?= rupiah($saldo) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="stat-label">Media Dokumentasi</div>
                <div class="stat-value"><?= count($dokumentasiFiles ?? []) ?></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h6><i class="bi bi-megaphone me-2"></i>Informasi Publik Program</h6>
                </div>
                <div class="card-body-custom">
                    <div class="donasi-info-grid">
                        <div class="donasi-info-item">
                            <span class="donasi-info-label">Slug URL</span>
                            <strong><?= e($record['slug'] ?? '-') ?></strong>
                        </div>
                        <div class="donasi-info-item">
                            <span class="donasi-info-label">Deadline</span>
                            <strong><?= e($deadlineLabel) ?></strong>
                        </div>
                        <div class="donasi-info-item">
                            <span class="donasi-info-label">Lokasi Kegiatan</span>
                            <strong><?= e($record['lokasi_kegiatan'] ?? '-') ?></strong>
                        </div>
                        <div class="donasi-info-item">
                            <span class="donasi-info-label">Nomor Kontak</span>
                            <strong><?= e($record['nomor_kontak'] ?? '-') ?></strong>
                        </div>
                    </div>

                    <div class="share-link-panel compact mt-4">
                        <div class="small text-muted mb-1">URL publik aktif</div>
                        <div class="share-link-inline"><?= !empty($shareUrl) ? e($shareUrl) : '-' ?></div>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Deskripsi Singkat</h6>
                        <p class="text-muted mb-0"><?= $plainDescription !== '' ? e(truncate($plainDescription, 240)) : 'Belum ada deskripsi.' ?></p>
                    </div>

                    <?php if (!empty($record['deskripsi_lengkap'])): ?>
                        <div class="donasi-richtext mt-4">
                            <?= $record['deskripsi_lengkap'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h6><i class="bi bi-bank me-2"></i>Informasi Rekening</h6>
                </div>
                <div class="card-body-custom">
                    <div class="donasi-bank-card">
                        <div class="donasi-bank-row"><span>Bank</span><strong><?= e($record['bank_nama'] ?? '-') ?></strong></div>
                        <div class="donasi-bank-row"><span>No. Rekening</span><strong><?= e($record['no_rekening'] ?? '-') ?></strong></div>
                        <div class="donasi-bank-row"><span>Atas Nama</span><strong><?= e($record['atas_nama_rekening'] ?? '-') ?></strong></div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <?php if (!empty($record['qris_file'])): ?>
                            <a href="<?= BASE_URL ?>/uploads/qris/<?= e($record['qris_file']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-qr-code"></i> Lihat QRIS
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($record['flyer_file'])): ?>
                            <a href="<?= BASE_URL ?>/uploads/donasi/<?= e($record['flyer_file']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-file-earmark-image"></i> Lihat Flyer
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($dokumentasiFiles)): ?>
                <div class="card-custom">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-images me-2"></i>Dokumentasi Program</h6>
                    </div>
                    <div class="card-body-custom">
                        <div class="donasi-mini-gallery">
                            <?php foreach ($dokumentasiFiles as $file): ?>
                                <a href="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>" target="_blank" class="donasi-mini-gallery-item">
                                    <?php if (isImageFile($file)): ?>
                                        <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>" alt="<?= e($file) ?>">
                                    <?php elseif (isVideoFile($file)): ?>
                                        <span><i class="bi bi-film"></i></span>
                                    <?php else: ?>
                                        <span><i class="bi bi-file-earmark"></i></span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-custom detail-table-card">
                <div class="card-header-custom detail-table-header">
                    <div>
                        <h6><i class="bi bi-arrow-down-circle text-success"></i> Pemasukan</h6>
                        <small>Riwayat dana masuk pada program donasi ini.</small>
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
                                                <form method="POST" action="<?= BASE_URL ?>/donasi/delete-pemasukan/<?= $p['id'] ?>" id="del-pem-<?= $p['id'] ?>" class="detail-action-form">
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
                        <small>Riwayat penggunaan dana dari program donasi ini.</small>
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
                                    <th>Uraian</th>
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
                                        <td><?= e($p['uraian'] ?? '-') ?></td>
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
                                                <form method="POST" action="<?= BASE_URL ?>/donasi/delete-pengeluaran/<?= $p['id'] ?>" id="del-peng-<?= $p['id'] ?>" class="detail-action-form">
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
                <h6 class="modal-title fw-bold">Tambah Pemasukan Donasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/donasi/tambah-pemasukan/<?= $record['id'] ?>" enctype="multipart/form-data">
                <?= CSRF::tokenField() ?>
                <div class="modal-body">
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
                        <label class="form-label-custom">Bukti</label>
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
                <h6 class="modal-title fw-bold">Tambah Pengeluaran Donasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/donasi/tambah-pengeluaran/<?= $record['id'] ?>" enctype="multipart/form-data">
                <?= CSRF::tokenField() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Jumlah (Rp)</label>
                        <input type="text" name="jumlah" class="form-control input-rupiah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Uraian</label>
                        <input type="text" name="uraian" class="form-control">
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
                        <label class="form-label-custom">Bukti</label>
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
                    <h6 class="modal-title fw-bold">Detail Pemasukan Donasi</h6>
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
                    <h6 class="modal-title fw-bold">Edit Pemasukan Donasi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/donasi/update-pemasukan/<?= $p['id'] ?>" enctype="multipart/form-data">
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
                    <h6 class="modal-title fw-bold">Detail Pengeluaran Donasi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-sm detail-modal-table mb-0">
                        <tr><td>Tanggal</td><td>: <?= formatDate($p['tanggal']) ?></td></tr>
                        <tr><td>Uraian</td><td>: <?= e($p['uraian'] ?? '-') ?></td></tr>
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
                    <h6 class="modal-title fw-bold">Edit Pengeluaran Donasi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= BASE_URL ?>/donasi/update-pengeluaran/<?= $p['id'] ?>" enctype="multipart/form-data">
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
                            <label class="form-label-custom">Uraian</label>
                            <input type="text" name="uraian" class="form-control" value="<?= e($p['uraian'] ?? '') ?>">
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
