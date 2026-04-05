<?php
/**
 * Sidebar Navigation
 */
$isSuperAdmin = Auth::isSuperAdmin();
$isBendahara = Auth::isBendahara();
$namaApp = $appSettings['nama_masjid'] ?? APP_NAME;
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <?php if (!empty($appSettings['logo'])): ?>
            <img src="<?= BASE_URL ?>/uploads/logo/<?= e($appSettings['logo']) ?>" alt="Logo" class="mosque-icon" style="object-fit: contain; background: #fff; padding: 2px;">
        <?php else: ?>
            <div class="mosque-icon">🕌</div>
        <?php endif; ?>
        <h4><?= e($namaApp) ?></h4>
        <small>Manajemen Keuangan</small>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-label">Menu Utama</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/dashboard" class="nav-link <?= isActiveMenu('dashboard') ?: isActiveMenu('') ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </div>

        <?php if ($isSuperAdmin || $isBendahara): ?>
        <!-- Keuangan -->
        <div class="nav-label">Keuangan</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/pemasukan" class="nav-link <?= isActiveMenu('pemasukan') ?>">
                <i class="bi bi-arrow-down-circle-fill"></i> Pemasukan Kas
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/pengeluaran" class="nav-link <?= isActiveMenu('pengeluaran') ?>">
                <i class="bi bi-arrow-up-circle-fill"></i> Pengeluaran Kas
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/kas-bank" class="nav-link <?= isActiveMenu('kas-bank') ?>">
                <i class="bi bi-bank2"></i> Kas &amp; Bank
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/donatur" class="nav-link <?= isActiveMenu('donatur') ?>">
                <i class="bi bi-people-fill"></i> Donatur
            </a>
        </div>

        <!-- Program -->
        <div class="nav-label">Program</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/kegiatan" class="nav-link <?= isActiveMenu('kegiatan') ?>">
                <i class="bi bi-calendar-event-fill"></i> Program Kegiatan
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/donasi" class="nav-link <?= isActiveMenu('donasi') ?>">
                <i class="bi bi-heart-fill"></i> Program Donasi
            </a>
        </div>

        <!-- Laporan -->
        <div class="nav-label">Laporan</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan" class="nav-link <?= (isActiveMenu('laporan') && !isset($_GET['report'])) ? 'active' : '' ?>">
                <i class="bi bi-pie-chart-fill"></i> Ikhtisar Laporan
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=arus-kas" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'arus-kas') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph"></i> Arus Kas
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=kas-tunai" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'kas-tunai') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-text"></i> Kas Tunai
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=kas-bank" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'kas-bank') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-spreadsheet"></i> Kas Bank
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=posisi-dana" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'posisi-dana') ? 'active' : '' ?>">
                <i class="bi bi-card-checklist"></i> Posisi Dana Kategori
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=realisasi-kegiatan" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'realisasi-kegiatan') ? 'active' : '' ?>">
                <i class="bi bi-calendar2-check"></i> Realisasi Kegiatan
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=program-donasi" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'program-donasi') ? 'active' : '' ?>">
                <i class="bi bi-heart"></i> Program Donasi
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/laporan?report=periodik" class="nav-link <?= (isset($_GET['report']) && $_GET['report'] == 'periodik') ? 'active' : '' ?>">
                <i class="bi bi-calendar3"></i> Laporan Periodik
            </a>
        </div>
        <?php endif; ?>

        <?php if ($isSuperAdmin): ?>
        <!-- Administrasi -->
        <div class="nav-label">Administrasi</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/users" class="nav-link <?= isActiveMenu('users') ?>">
                <i class="bi bi-person-gear"></i> Kelola User
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/settings" class="nav-link <?= isActiveMenu('settings') ?>">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/audit" class="nav-link <?= isActiveMenu('audit') ?>">
                <i class="bi bi-shield-check"></i> Audit Log
            </a>
        </div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/backup" class="nav-link <?= isActiveMenu('backup') ?>">
                <i class="bi bi-database-fill-down"></i> Backup Database
            </a>
        </div>
        <?php endif; ?>

        <!-- Public -->
        <div class="nav-label">Lainnya</div>
        <div class="nav-item">
            <a href="<?= BASE_URL ?>/publik" class="nav-link <?= isActiveMenu('publik') ?>" target="_blank">
                <i class="bi bi-globe"></i> Dashboard Publik
            </a>
        </div>
    </nav>
</aside>
