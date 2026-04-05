<?php /** Donatur List View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Data Donatur</h5>
        <small class="text-muted">Kelola data donatur masjid</small>
    </div>
    <a href="<?= BASE_URL ?>/donatur/create" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Tambah Donatur
    </a>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-custom" id="tableDonatur" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Donatur</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Jenis</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donaturList as $i => $d): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="fw-semibold"><?= e($d['nama_donatur']) ?></td>
                        <td><?= e($d['no_hp'] ?: '-') ?></td>
                        <td><?= e(truncate($d['alamat'] ?: '-', 30)) ?></td>
                        <td>
                            <span class="badge-status <?= $d['jenis_donatur'] === 'tetap' ? 'badge-tetap' : 'badge-tidak-tetap' ?>">
                                <?= $d['jenis_donatur'] === 'tetap' ? 'Tetap' : 'Tidak Tetap' ?>
                            </span>
                        </td>
                        <td><?= e(truncate($d['catatan'] ?: '-', 30)) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= BASE_URL ?>/donatur/edit/<?= $d['id'] ?>" class="btn-action edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form id="delete-<?= $d['id'] ?>" method="POST" action="<?= BASE_URL ?>/donatur/delete/<?= $d['id'] ?>" class="d-inline">
                                    <?= CSRF::tokenField() ?>
                                    <button type="button" class="btn-action delete" onclick="confirmDelete('delete-<?= $d['id'] ?>')" title="Hapus">
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

<?php $extraJs = "<script>initDataTable('tableDonatur', {order:[[1,'asc']]});</script>"; ?>
