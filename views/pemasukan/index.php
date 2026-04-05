<?php /** Pemasukan List View */ ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Pemasukan Kas</h5>
        <small class="text-muted">Kelola pemasukan keuangan masjid</small>
    </div>
    <a href="<?= BASE_URL ?>/pemasukan/create" class="btn btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Tambah Pemasukan
    </a>
</div>

<!-- Filter -->
<div class="card-custom mb-4">
    <div class="card-body-custom py-3">
        <form method="GET" action="<?= BASE_URL ?>/pemasukan" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label-custom">Bulan</label>
                <input type="month" name="bulan" class="form-control" value="<?= e($filters['bulan'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Kategori</label>
                <select name="kategori_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoriList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($filters['kategori_id'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="<?= e($filters['tanggal_dari'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="<?= e($filters['tanggal_sampai'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Fund</label>
                <select name="fund_category" class="form-select">
                    <option value="">Semua</option>
                    <?php foreach (fundCategoryOptions() as $fundOption): ?>
                        <option value="<?= e($fundOption) ?>" <?= ($filters['fund_category'] ?? '') === $fundOption ? 'selected' : '' ?>><?= e($fundOption) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table class="table-custom" id="tablePemasukan" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Donatur</th>
                        <th>Kategori</th>
                        <th>Fund</th>
                        <th>Akun</th>
                        <th class="text-end">Jumlah</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataList as $i => $d): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= formatDate($d['tanggal']) ?></td>
                        <td><?= e($d['donatur_nama'] ?? $d['nama_donatur'] ?? '-') ?></td>
                        <td><span class="badge-status badge-aktif"><?= e($d['nama_kategori'] ?? '-') ?></span></td>
                        <td><?= e($d['fund_category'] ?? '-') ?></td>
                        <td><?= e($d['nama_akun'] ?? '-') ?></td>
                        <td class="text-end fw-bold text-success"><?= rupiah((float)$d['jumlah']) ?></td>
                        <td><?= e($d['petugas'] ?? '-') ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?= BASE_URL ?>/pemasukan/edit/<?= $d['id'] ?>" class="btn-action edit"><i class="bi bi-pencil"></i></a>
                                <form id="del-p-<?= $d['id'] ?>" method="POST" action="<?= BASE_URL ?>/pemasukan/delete/<?= $d['id'] ?>" class="d-inline">
                                    <?= CSRF::tokenField() ?>
                                    <button type="button" class="btn-action delete" onclick="confirmDelete('del-p-<?= $d['id'] ?>')"><i class="bi bi-trash"></i></button>
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

<?php $extraJs = "<script>initDataTable('tablePemasukan');</script>"; ?>
