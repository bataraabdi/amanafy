<?php /** Kegiatan List View */ ?>
<!-- Stats cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-icon info mb-2"><i class="bi bi-calendar-event"></i></div>
            <div class="stat-label">Total Kegiatan</div>
            <div class="stat-value"><?= (int)($stats['total_kegiatan'] ?? 0) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card income">
            <div class="stat-icon income mb-2"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-label">Total Anggaran</div>
            <div class="stat-value income-color"><?= rupiah((float)($stats['total_anggaran'] ?? 0)) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card expense">
            <div class="stat-icon expense mb-2"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stat-label">Total Pengeluaran</div>
            <div class="stat-value expense-color"><?= rupiah((float)($stats['total_pengeluaran'] ?? 0)) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card balance">
            <div class="stat-icon balance mb-2"><i class="bi bi-wallet2"></i></div>
            <div class="stat-label">Sisa Anggaran</div>
            <div class="stat-value balance-color"><?= rupiah((float)($stats['sisa_anggaran'] ?? 0)) ?></div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Daftar Kegiatan</h5>
    <a href="<?= BASE_URL ?>/kegiatan/create" class="btn btn-primary-custom"><i class="bi bi-plus-lg"></i> Tambah Kegiatan</a>
</div>

<div class="row g-3">
    <?php if (empty($list)): ?>
        <div class="col-12"><div class="empty-state card-custom p-5"><i class="bi bi-calendar-x d-block"></i><h5>Belum ada kegiatan</h5><p>Klik tombol Tambah Kegiatan untuk memulai.</p></div></div>
    <?php endif; ?>
    <?php foreach ($list as $k): ?>
    <div class="col-md-6 col-xl-4">
        <div class="card-custom h-100">
            <?php if (!empty($k['gambar'])): ?>
                <img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($k['gambar']) ?>" class="w-100" style="height:160px; object-fit:cover;">
            <?php else: ?>
                <div class="w-100 d-flex align-items-center justify-content-center" style="height:120px; background: var(--primary-light);"><i class="bi bi-calendar-event" style="font-size:2.5rem; color: var(--primary);"></i></div>
            <?php endif; ?>
            <div class="card-body-custom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0"><?= e($k['nama_kegiatan']) ?></h6>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="badge-status <?= ($k['status'] ?? 'aktif') === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>"><?= ucfirst($k['status'] ?? 'aktif') ?></span>
                        <span class="badge <?= (int)($k['tampil_publik'] ?? 1) === 1 ? 'bg-info' : 'bg-secondary' ?>" style="font-size:0.7rem;"><i class="bi <?= (int)($k['tampil_publik'] ?? 1) === 1 ? 'bi-globe' : 'bi-eye-slash' ?>"></i> <?= (int)($k['tampil_publik'] ?? 1) === 1 ? 'Publik' : 'Hidden' ?></span>
                    </div>
                </div>
                <p class="text-muted mb-3" style="font-size:0.82rem;"><?= e(truncate($k['keterangan'] ?? '-', 80)) ?></p>
                <div class="text-muted mb-1" style="font-size:0.8rem;"><i class="bi bi-geo-alt me-1"></i><?= e($k['waktu_tempat'] ?? '-') ?></div>
                <div class="text-muted mb-1" style="font-size:0.8rem;"><i class="bi bi-person-badge me-1"></i>PJ: <?= e($k['penanggung_jawab'] ?? '-') ?></div>
                <div class="text-muted mb-3" style="font-size:0.8rem;"><i class="bi bi-wallet me-1"></i>Sumber Dana: <?= e($k['sumber_dana'] ?? '-') ?></div>
                <div class="d-flex justify-content-between mb-1" style="font-size:0.8rem;">
                    <span class="text-primary"><i class="bi bi-cash-stack me-1"></i>Anggaran Awal: <?= rupiah((float)($k['jumlah_anggaran'] ?? 0)) ?></span>
                    <span class="text-success"><i class="bi bi-arrow-down-circle me-1"></i>Tambahan: <?= rupiah((float)$k['total_pemasukan']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1" style="font-size:0.8rem;">
                    <span class="text-info"><i class="bi bi-calculator me-1"></i>Total: <?= rupiah((float)($k['total_anggaran'] ?? 0)) ?></span>
                    <span class="text-danger"><i class="bi bi-arrow-up-circle me-1"></i>Keluar: <?= rupiah((float)$k['total_pengeluaran']) ?></span>
                </div>
                <div class="fw-bold mb-3" style="font-size:0.82rem;"><i class="bi bi-wallet2 me-1"></i>Sisa Anggaran: <?= rupiah((float)($k['sisa_anggaran'] ?? 0)) ?></div>
                <div class="d-flex gap-1">
                    <a href="<?= BASE_URL ?>/kegiatan/detail/<?= $k['id'] ?>" class="btn btn-sm btn-primary-custom flex-fill"><i class="bi bi-eye"></i> Detail</a>
                    <a href="<?= BASE_URL ?>/kegiatan/edit/<?= $k['id'] ?>" class="btn-action edit"><i class="bi bi-pencil"></i></a>
                    <form id="del-k-<?= $k['id'] ?>" method="POST" action="<?= BASE_URL ?>/kegiatan/delete/<?= $k['id'] ?>" class="d-inline">
                        <?= CSRF::tokenField() ?>
                        <button type="button" class="btn-action delete" onclick="confirmDelete('del-k-<?= $k['id'] ?>')"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
