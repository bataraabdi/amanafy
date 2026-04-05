<?php /** Audit Log View */ ?>
<h5 class="fw-bold mb-4">Audit Log</h5>

<div class="card-custom mb-4"><div class="card-body-custom py-3">
    <form method="GET" action="<?= BASE_URL ?>/audit" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label-custom">Aksi</label>
            <select name="action" class="form-select"><option value="">Semua</option>
                <option value="CREATE" <?= ($filters['action'] ?? '') === 'CREATE' ? 'selected' : '' ?>>CREATE</option>
                <option value="UPDATE" <?= ($filters['action'] ?? '') === 'UPDATE' ? 'selected' : '' ?>>UPDATE</option>
                <option value="DELETE" <?= ($filters['action'] ?? '') === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                <option value="LOGIN" <?= ($filters['action'] ?? '') === 'LOGIN' ? 'selected' : '' ?>>LOGIN</option>
                <option value="LOGOUT" <?= ($filters['action'] ?? '') === 'LOGOUT' ? 'selected' : '' ?>>LOGOUT</option>
            </select>
        </div>
        <div class="col-md-3"><label class="form-label-custom">Dari</label><input type="date" name="tanggal_dari" class="form-control" value="<?= e($filters['tanggal_dari'] ?? '') ?>"></div>
        <div class="col-md-3"><label class="form-label-custom">Sampai</label><input type="date" name="tanggal_sampai" class="form-control" value="<?= e($filters['tanggal_sampai'] ?? '') ?>"></div>
        <div class="col-md-3"><button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-search"></i> Filter</button></div>
    </form>
</div></div>

<div class="card-custom"><div class="card-body-custom p-0">
    <div class="table-responsive"><table class="table-custom"><thead><tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Tabel</th><th>Record ID</th><th>IP Address</th></tr></thead><tbody>
    <?php if (empty($logs)): ?><tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada log</td></tr><?php endif; ?>
    <?php foreach ($logs as $l): ?>
    <tr>
        <td><?= formatDateTime($l['created_at']) ?></td>
        <td class="fw-semibold"><?= e($l['username'] ?? '-') ?></td>
        <td><span class="badge-status <?php
            echo match($l['action']) {
                'CREATE' => 'badge-aktif', 'UPDATE' => 'badge-transfer', 'DELETE' => 'badge-nonaktif',
                'LOGIN' => 'badge-tetap', 'LOGOUT' => 'badge-tidak-tetap', default => 'badge-tunai'
            };
        ?>"><?= e($l['action']) ?></span></td>
        <td><?= e($l['table_name'] ?? '-') ?></td>
        <td><?= $l['record_id'] ?? '-' ?></td>
        <td><?= e($l['ip_address'] ?? '-') ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</div></div>

<?= pagination($total, $perPage, $page, BASE_URL . '/audit') ?>
