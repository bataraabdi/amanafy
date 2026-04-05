<?php
/**
 * Top Header
 */
$currentUser = Auth::user();
$initials = '';
if ($currentUser) {
    $names = explode(' ', $currentUser['nama_lengkap']);
    $initials = strtoupper(substr($names[0], 0, 1));
    if (isset($names[1])) $initials .= strtoupper(substr($names[1], 0, 1));
}
?>
<header class="top-header sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-gray-100 shadow-sm flex items-center justify-between px-6 h-16 transition-all duration-300">
    <div class="header-left flex items-center gap-4">
        <button class="hamburger-btn hover:bg-gray-100 p-2 rounded-md transition-colors" onclick="toggleSidebar()">
            <i class="bi bi-list text-xl text-gray-700"></i>
        </button>
        <div class="page-title-header">
            <h5 class="text-base font-bold text-gray-900 m-0"><?= e($pageTitle ?? 'Dashboard') ?></h5>
            <small class="text-xs text-gray-500"><?= date('l, d F Y') ?></small>
        </div>
    </div>
    <div class="header-right flex items-center gap-4">
        <div class="dropdown">
            <div class="user-menu flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 hover:bg-gray-200 cursor-pointer transition-colors shadow-sm border border-gray-100" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar"><?= $initials ?></div>
                <div class="user-info d-none d-md-block">
                    <div class="user-name"><?= e($currentUser['nama_lengkap'] ?? '') ?></div>
                    <div class="user-role"><?= e($currentUser['role_name'] ?? '') ?></div>
                </div>
                <i class="bi bi-chevron-down" style="font-size: 0.7rem; color: var(--gray-500);"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px; min-width: 200px;">
                <li class="px-3 py-2 border-bottom">
                    <small class="text-muted">Login sebagai</small>
                    <div class="fw-bold"><?= e($currentUser['username'] ?? '') ?></div>
                </li>
                <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>/profil"><i class="bi bi-person me-2"></i>Ubah Profil & Password</a></li>
                <?php if(Auth::hasRole(['Super Admin', 'Bendahara'])): ?>
                <li><a class="dropdown-item py-2" href="<?= BASE_URL ?>/settings"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger" href="<?= BASE_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>
