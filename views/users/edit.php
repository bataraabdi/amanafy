<?php /** Edit User */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h5 class="mb-1 fw-bold">Edit User</h5></div>
    <a href="<?= BASE_URL ?>/users" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
<div class="card-custom"><div class="card-body-custom">
    <form method="POST" action="<?= BASE_URL ?>/users/edit/<?= $editUser['id'] ?>">
        <?= CSRF::tokenField() ?>
        <div class="row g-3">
            <div class="col-12">
                <div class="share-link-panel compact d-flex flex-column gap-1">
                    <strong class="text-dark">Sedang mengedit: <?= e($editUser['nama_lengkap']) ?></strong>
                    <small class="text-muted">Username saat ini: <?= e($editUser['username']) ?> | Role: <?= e($editUser['nama_role'] ?? '-') ?></small>
                </div>
            </div>
            <div class="col-md-6"><label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="nama_lengkap" class="form-control" value="<?= e($editUser['nama_lengkap']) ?>" required></div>
            <div class="col-md-6"><label class="form-label-custom">Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" value="<?= e($editUser['email']) ?>" required></div>
            <div class="col-md-6"><label class="form-label-custom">No HP</label><input type="text" name="no_hp" class="form-control" value="<?= e($editUser['no_hp'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label-custom">Username <span class="text-danger">*</span></label><input type="text" name="username" class="form-control" value="<?= e($editUser['username']) ?>" required></div>
            <div class="col-md-6"><label class="form-label-custom">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label><input type="password" name="password" class="form-control" minlength="6"></div>
            <div class="col-md-3"><label class="form-label-custom">Role</label><select name="role_id" class="form-select"><?php foreach ($roles as $r): ?><option value="<?= $r['id'] ?>" <?= $editUser['role_id'] == $r['id'] ? 'selected' : '' ?>><?= e($r['nama_role']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-3"><label class="form-label-custom">Status</label><select name="status" class="form-select"><option value="aktif" <?= $editUser['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option><option value="nonaktif" <?= $editUser['status'] === 'nonaktif' ? 'selected' : '' ?>>Tidak Aktif</option></select></div>
            <div class="col-12"><hr><button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg"></i> Perbarui</button></div>
        </div>
    </form>
</div></div>
