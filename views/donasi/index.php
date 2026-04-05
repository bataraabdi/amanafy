<?php /** Donasi List View */ ?>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card info"><div class="stat-icon info mb-2"><i class="bi bi-heart"></i></div><div class="stat-label">Total Program</div><div class="stat-value"><?= (int)($stats['total_program'] ?? 0) ?></div></div></div>
    <div class="col-md-3"><div class="stat-card income"><div class="stat-icon income mb-2"><i class="bi bi-arrow-down-circle"></i></div><div class="stat-label">Total Pemasukan</div><div class="stat-value income-color"><?= rupiah((float)($stats['total_pemasukan'] ?? 0)) ?></div></div></div>
    <div class="col-md-3"><div class="stat-card expense"><div class="stat-icon expense mb-2"><i class="bi bi-arrow-up-circle"></i></div><div class="stat-label">Total Pengeluaran</div><div class="stat-value expense-color"><?= rupiah((float)($stats['total_pengeluaran'] ?? 0)) ?></div></div></div>
    <div class="col-md-3"><div class="stat-card balance"><div class="stat-icon balance mb-2"><i class="bi bi-wallet2"></i></div><div class="stat-label">Saldo</div><div class="stat-value balance-color"><?= rupiah(((float)($stats['total_pemasukan'] ?? 0)) - ((float)($stats['total_pengeluaran'] ?? 0))) ?></div></div></div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h5 class="mb-1 fw-bold">Program Donasi</h5>
        <small class="text-muted">Kelola kampanye donasi, kanal transfer, dan link share publik di satu tempat.</small>
    </div>
    <a href="<?= BASE_URL ?>/donasi/create" class="btn btn-primary-custom"><i class="bi bi-plus-lg"></i> Tambah Program</a>
</div>

<div class="row g-4">
    <?php if (empty($list)): ?>
        <div class="col-12">
            <div class="empty-state card-custom p-5">
                <i class="bi bi-heart d-block"></i>
                <h5>Belum ada program donasi</h5>
                <p class="mb-0">Tambahkan program baru untuk mulai mengelola target, rekening, dan link publikasi.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($list as $d): ?>
        <?php
        $progress = $d['target_nominal'] > 0 ? min(100, round(($d['total_pemasukan'] / $d['target_nominal']) * 100)) : 0;
        $shareUrl = !empty($d['slug']) ? donasiPublicUrl($d) : '';
        $docsCount = count(decodeJsonArray($d['dokumentasi_files'] ?? null));
        $excerpt = htmlToPlainText($d['deskripsi_lengkap'] ?? ($d['uraian'] ?? ''));
        ?>
        <div class="col-lg-6 col-xxl-4">
            <div class="card-custom donasi-program-card h-100">
                <div class="donasi-card-media">
                    <?php
                        $displayImage = $d['gambar'] ?? '';
                        if (empty($displayImage) && !empty($d['flyer_file']) && isImageFile($d['flyer_file'])) {
                            $displayImage = $d['flyer_file'];
                        }
                    ?>
                    <?php if (!empty($displayImage)): ?>
                        <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($displayImage) ?>" alt="<?= e($d['nama_donasi']) ?>">
                    <?php else: ?>
                        <div class="donasi-card-placeholder">
                            <i class="bi bi-heart"></i>
                        </div>
                    <?php endif; ?>
                    <span class="badge-status <?= ($d['status'] ?? 'aktif') === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>">
                        <?= ($d['status'] ?? 'aktif') === 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </div>

                <div class="card-body-custom d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                        <h6 class="fw-bold mb-0"><?= e($d['nama_donasi']) ?></h6>
                        <span class="donasi-target-pill"><?= rupiah((float)$d['target_nominal']) ?></span>
                    </div>

                    <?php if ($excerpt !== ''): ?>
                        <p class="text-muted small mb-3"><?= e(truncate($excerpt, 140)) ?></p>
                    <?php endif; ?>

                    <div class="donasi-card-meta">
                        <div><i class="bi bi-geo-alt"></i> <?= e($d['lokasi_kegiatan'] ?? '-') ?></div>
                        <div><i class="bi bi-telephone"></i> <?= e($d['nomor_kontak'] ?? '-') ?></div>
                        <div><i class="bi bi-calendar-event"></i> <?= !empty($d['deadline']) ? formatDate($d['deadline']) : 'Tanpa deadline' ?></div>
                        <div><i class="bi bi-images"></i> <?= $docsCount ?> media dokumentasi</div>
                    </div>

                    <div class="donasi-progress-panel mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Terkumpul</span>
                            <span class="fw-bold text-success"><?= rupiah((float)$d['total_pemasukan']) ?></span>
                        </div>
                        <div class="progress-custom mb-2"><div class="progress-bar-custom" style="width:<?= $progress ?>%"></div></div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Progress <?= $progress ?>%</span>
                            <span>Sisa <?= rupiah(max(0, ((float)$d['target_nominal']) - ((float)$d['total_pemasukan']))) ?></span>
                        </div>
                    </div>

                    <div class="share-link-panel compact mt-3">
                        <div class="small text-muted mb-1">Link publik</div>
                        <div class="share-link-inline"><?= $shareUrl !== '' ? e($shareUrl) : 'Slug publik belum tersedia' ?></div>
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <?php if ($shareUrl !== ''): ?>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick='copyTextValue(<?= json_encode($shareUrl) ?>)'>
                                    <i class="bi bi-copy"></i> Copy
                                </button>
                                <a href="<?= e($shareUrl) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-box-arrow-up-right"></i> Lihat Publik
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="<?= BASE_URL ?>/donasi/detail/<?= $d['id'] ?>" class="btn btn-primary-custom flex-fill justify-content-center"><i class="bi bi-eye"></i> Detail</a>
                        <a href="<?= BASE_URL ?>/donasi/edit/<?= $d['id'] ?>" class="btn-action edit" title="Edit program"><i class="bi bi-pencil"></i></a>
                        <form id="del-d-<?= $d['id'] ?>" method="POST" action="<?= BASE_URL ?>/donasi/delete/<?= $d['id'] ?>" class="d-inline">
                            <?= CSRF::tokenField() ?>
                            <button type="button" class="btn-action delete" onclick="confirmDelete('del-d-<?= $d['id'] ?>')" title="Hapus program"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
