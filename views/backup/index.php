<?php /** Backup View */ ?>
<h5 class="fw-bold mb-4">Backup Database</h5>

<div class="card-custom mb-4"><div class="card-body-custom">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <p class="mb-1 fw-semibold">Buat backup database</p>
            <small class="text-muted">Backup akan disimpan dalam format SQL yang bisa di-restore kapan saja.</small>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/backup/create">
            <?= CSRF::tokenField() ?>
            <button type="submit" class="btn btn-primary-custom"><i class="bi bi-database-fill-down"></i> Buat Backup Sekarang</button>
        </form>
    </div>
</div></div>

<div class="card-custom">
    <div class="card-header-custom"><h6><i class="bi bi-archive me-2"></i>Daftar Backup</h6></div>
    <div class="card-body-custom p-0">
        <div class="table-responsive"><table class="table-custom"><thead><tr><th>Filename</th><th>Ukuran</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
        <?php if (empty($backups)): ?><tr><td colspan="4" class="text-center py-4 text-muted">Belum ada backup</td></tr><?php endif; ?>
        <?php foreach ($backups as $b): ?>
        <tr>
            <td><i class="bi bi-file-earmark-code me-2 text-primary"></i><?= e($b['filename']) ?></td>
            <td><?= number_format($b['size'] / 1024, 1) ?> KB</td>
            <td><?= formatDateTime($b['date']) ?></td>
            <td>
                <div class="d-flex gap-1">
                    <a href="<?= BASE_URL ?>/backup/download/<?= e($b['filename']) ?>" class="btn-action view" title="Download"><i class="bi bi-download"></i></a>
                    <form id="del-b-<?= md5($b['filename']) ?>" method="POST" action="<?= BASE_URL ?>/backup/delete/<?= e($b['filename']) ?>" class="d-inline"><?= CSRF::tokenField() ?>
                        <button type="button" class="btn-action delete" onclick="confirmDelete('del-b-<?= md5($b['filename']) ?>')"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody></table></div>
    </div>
</div>
