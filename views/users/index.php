<?php /** Users List */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1 fw-bold">Kelola User</h5><small class="text-muted">Manajemen pengguna sistem</small></div>
    <a href="<?= BASE_URL ?>/users/create" class="btn btn-primary-custom"><i class="bi bi-plus-lg"></i> Tambah User</a>
</div>
<div class="card-custom"><div class="card-body-custom">
    <div class="table-responsive"><table class="table-custom" id="tableUsers" style="width:100%"><thead><tr><th>No</th><th>Nama</th><th>Username</th><th>Email</th><th>No HP</th><th>Role</th><th>Status</th><th>Last Login</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($users as $i => $u): ?>
    <tr>
        <td><?= $i+1 ?></td><td class="fw-semibold"><?= e($u['nama_lengkap']) ?></td><td><?= e($u['username']) ?></td>
        <td><?= e($u['email']) ?></td><td><?= e($u['no_hp'] ?? '-') ?></td>
        <td><span class="badge-status badge-aktif"><?= e($u['nama_role']) ?></span></td>
        <td><span class="badge-status <?= $u['status'] === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>"><?= ucfirst($u['status']) ?></span></td>
        <td><?= $u['last_login'] ? formatDateTime($u['last_login']) : '-' ?></td>
        <td><div class="d-flex gap-1">
            <a href="<?= BASE_URL ?>/users/edit/<?= $u['id'] ?>" class="btn-action edit"><i class="bi bi-pencil"></i></a>
            <?php if ($u['id'] != Auth::id()): ?>
            <form id="del-u-<?= $u['id'] ?>" method="POST" action="<?= BASE_URL ?>/users/delete/<?= $u['id'] ?>" class="d-inline"><?= CSRF::tokenField() ?>
                <button type="button" class="btn-action delete" onclick="confirmDelete('del-u-<?= $u['id'] ?>')"><i class="bi bi-trash"></i></button>
            </form>
            <?php endif; ?>
        </div></td>
    </tr>
    <?php endforeach; ?>
    </tbody></table></div>
</div></div>
<?php $extraJs = "<script>initDataTable('tableUsers', {order:[[0,'asc']]});</script>"; ?>
