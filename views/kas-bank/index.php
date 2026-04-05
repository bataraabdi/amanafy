<?php
/**
 * Kas & Bank Management View
 * Responsive, Modern, and User-Friendly
 */
$cashList = $cashList ?? [];
$bankList = $bankList ?? [];
$transferList = $transferList ?? [];
$postingList = $postingList ?? [];
$accountOptions = $accountOptions ?? [];
?>

<div class="kas-bank-page">
    <!-- Header Page -->
    <div class="page-header-new mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-bold text-gray-800">Manajemen Kas & Bank</h4>
            <p class="text-muted small mb-0">Kelola aset keuangan Masjid (Tunai & Perbankan) sesuai standar pelaporan ISAK 35.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTransfer">
                <i class="bi bi-arrow-left-right me-1"></i> Transfer Internal
            </button>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                    <div class="text-uppercase small fw-bold text-muted mb-2 tracking-wider">Total Kas Tunai</div>
                    <h3 class="fw-bold mb-1 text-primary-dark"><?= rupiah((float)$totalCash) ?></h3>
                    <div class="small text-success">
                        <i class="bi bi-check-circle-fill me-1"></i> <?= count($cashList) ?> Akun Aktif
                    </div>
                    <div class="mt-3 progress" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-bank fs-1"></i>
                    </div>
                    <div class="text-uppercase small fw-bold text-muted mb-2 tracking-wider">Total Saldo Bank</div>
                    <h3 class="fw-bold mb-1 text-info-dark"><?= rupiah((float)$totalBank) ?></h3>
                    <div class="small text-success">
                        <i class="bi bi-check-circle-fill me-1"></i> <?= count($bankList) ?> Rekening Aktif
                    </div>
                    <div class="mt-3 progress" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm overflow-hidden text-white bg-gradient-primary" style="border-radius: 16px; background: linear-gradient(135deg, #2388cf 0%, #0d4f7a 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-20">
                        <i class="bi bi-safe fs-1"></i>
                    </div>
                    <div class="text-uppercase small fw-bold mb-2 tracking-wider opacity-75">Total Kekayaan (Kas & Bank)</div>
                    <h3 class="fw-bold mb-1"><?= rupiah((float)$grandTotal) ?></h3>
                    <div class="small opacity-75">
                        <i class="bi bi-graph-up-arrow me-1"></i> Konsolidasi aset masjid
                    </div>
                    <div class="mt-3 progress bg-white-opacity-20" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar bg-white" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="card-custom border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white p-0 border-bottom">
            <ul class="nav nav-tabs nav-fill flex-nowrap overflow-auto scrollbar-hide border-0" id="kasBankTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 active fw-semibold border-0" id="cash-tab" data-bs-toggle="tab" data-bs-target="#cash-panel" type="button" role="tab">
                        <i class="bi bi-wallet2 me-2"></i> Kas Tunai
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 fw-semibold border-0" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank-panel" type="button" role="tab">
                        <i class="bi bi-bank me-2"></i> Rekening Bank
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 fw-semibold border-0" id="transfer-tab" data-bs-toggle="tab" data-bs-target="#transfer-panel" type="button" role="tab">
                        <i class="bi bi-arrow-left-right me-2"></i> Transfer Internal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 fw-semibold border-0" id="automation-tab" data-bs-toggle="tab" data-bs-target="#automation-panel" type="button" role="tab">
                        <i class="bi bi-gear-wide-connected me-2"></i> Otomatisasi
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body-custom p-4">
            <div class="tab-content" id="kasBankTabContent">
                
                <!-- Tab Panel: Kas Tunai -->
                <div class="tab-pane fade show active" id="cash-panel" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold m-0 text-gray-700">Daftar Akun Kas Tunai</h6>
                        <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAddCash">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Akun Kas
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Nama Kas</th>
                                    <th class="border-0 text-end">Saldo Awal</th>
                                    <th class="border-0 text-end">Saldo Saat Ini</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-center rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($cashList)): ?>
                                    <tr><td colspan="5" class="text-center py-5 text-muted italic">Belum ada akun kas yang terdaftar.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($cashList as $cash): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-soft text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-wallet2"></i>
                                                </div>
                                                <span class="fw-semibold text-gray-800"><?= e($cash['nama_kas']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-end text-muted"><?= rupiah((float)$cash['saldo_awal']) ?></td>
                                        <td class="text-end">
                                            <span class="fw-bold text-gray-900"><?= rupiah((float)$cash['saldo_saat_ini']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ((int)$cash['is_active'] === 1): ?>
                                                <span class="badge rounded-pill bg-success-soft text-success px-3">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary-soft text-secondary px-3">Non-aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalEditCash<?= $cash['id'] ?>"><i class="bi bi-pencil me-2"></i> Edit Akun</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="<?= BASE_URL ?>/kas-bank" id="del-cash-<?= $cash['id'] ?>">
                                                            <?= CSRF::tokenField() ?>
                                                            <input type="hidden" name="action" value="delete_cash">
                                                            <input type="hidden" name="id" value="<?= $cash['id'] ?>">
                                                            <a class="dropdown-item text-danger py-2" href="#" onclick="confirmDelete('del-cash-<?= $cash['id'] ?>', 'Hapus akun kas ini?')"><i class="bi bi-trash me-2"></i> Hapus</a>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Panel: Rekening Bank -->
                <div class="tab-pane fade" id="bank-panel" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold m-0 text-gray-700">Daftar Rekening Bank</h6>
                        <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalAddBank">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Bank
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">Informasi Bank</th>
                                    <th class="border-0">Nama Pemilik</th>
                                    <th class="border-0 text-end">Saldo Saat Ini</th>
                                    <th class="border-0 text-end">Fee & Jasa</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-center rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($bankList)): ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted italic">Belum ada akun bank yang terdaftar.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($bankList as $bank): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info-soft text-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-bank"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-gray-800"><?= e($bank['nama_bank']) ?></div>
                                                    <div class="text-muted small"><?= e($bank['nomor_rekening']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-gray-600 small"><?= e($bank['nama_pemilik']) ?></td>
                                        <td class="text-end">
                                            <span class="fw-bold text-gray-900"><?= rupiah((float)$bank['saldo_saat_ini']) ?></span>
                                        </td>
                                        <td class="text-end">
                                            <div class="small">
                                                <span class="text-danger">-<?= formatNumber((float)$bank['bank_admin_fee']) ?></span><br>
                                                <span class="text-success">+<?= formatNumber((float)$bank['bank_interest']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ((int)$bank['is_active'] === 1): ?>
                                                <span class="badge rounded-pill bg-success-soft text-success px-3">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary-soft text-secondary px-3">Non-aktif</span>
                                            <?php endif; ?>
                                            <div class="mt-1">
                                                <?php if ((int)$bank['is_public'] === 1): ?>
                                                    <span class="badge rounded-pill bg-info-soft text-info px-2" style="font-size: 0.65rem;"><i class="bi bi-eye me-1"></i>Publik</span>
                                                <?php else: ?>
                                                    <span class="badge rounded-pill bg-light text-muted px-2" style="font-size: 0.65rem;"><i class="bi bi-eye-slash me-1"></i>Privat</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalEditBank<?= $bank['id'] ?>"><i class="bi bi-pencil me-2"></i> Edit Akun</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="<?= BASE_URL ?>/kas-bank" id="del-bank-<?= $bank['id'] ?>">
                                                            <?= CSRF::tokenField() ?>
                                                            <input type="hidden" name="action" value="delete_bank">
                                                            <input type="hidden" name="id" value="<?= $bank['id'] ?>">
                                                            <a class="dropdown-item text-danger py-2" href="#" onclick="confirmDelete('del-bank-<?= $bank['id'] ?>', 'Hapus akun bank ini?')"><i class="bi bi-trash me-2"></i> Hapus</a>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Panel: Transfer Internal -->
                <div class="tab-pane fade" id="transfer-panel" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold m-0 text-gray-700">Riwayat Mutasi Antar Akun</h6>
                            <p class="text-muted small mb-0">Pemindahan dana internal untuk kebutuhan operasional atau tabungan.</p>
                        </div>
                        <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTransfer">
                            <i class="bi bi-plus-lg me-1"></i> Buat Transfer
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0">Alur Dana</th>
                                    <th class="border-0 text-center">Fund Category</th>
                                    <th class="border-0 text-end">Jumlah</th>
                                    <th class="border-0 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transferList)): ?>
                                    <tr><td colspan="5" class="text-center py-5 text-muted italic">Tidak ada riwayat transfer internal baru-baru ini.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($transferList as $transfer): ?>
                                    <tr>
                                        <td class="small text-gray-600"><?= formatDate($transfer['tanggal']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="text-danger small fw-semibold me-2"><?= e($transfer['akun_asal_nama'] ?? '-') ?></span>
                                                <i class="bi bi-chevron-right text-muted small mx-1"></i>
                                                <span class="text-success small fw-semibold ms-2"><?= e($transfer['akun_tujuan_nama'] ?? '-') ?></span>
                                            </div>
                                            <div class="text-muted small italic mt-1" style="font-size: 0.75rem;"><?= e($transfer['keterangan'] ?? 'Tanpa keterangan') ?></div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-light text-dark border p-1 px-3 small"><?= e($transfer['fund_category']) ?></span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-primary"><?= rupiah((float)$transfer['jumlah']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button class="btn btn-link btn-sm text-primary p-0" data-bs-toggle="modal" data-bs-target="#modalEditTransfer<?= $transfer['id'] ?>">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <form method="POST" action="<?= BASE_URL ?>/kas-bank" id="del-trans-<?= $transfer['id'] ?>">
                                                    <?= CSRF::tokenField() ?>
                                                    <input type="hidden" name="action" value="delete_transfer">
                                                    <input type="hidden" name="id" value="<?= $transfer['id'] ?>">
                                                    <button type="button" class="btn btn-link btn-sm text-danger p-0" onclick="confirmDelete('del-trans-<?= $transfer['id'] ?>', 'Hapus catatan transfer ini?')">
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

                <!-- Tab Panel: Otomatisasi -->
                <div class="tab-pane fade" id="automation-panel" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="p-4 bg-light rounded-4 h-100">
                                <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Jalankan Posting Bulanan</h6>
                                <p class="text-muted small mb-4">Fitur ini akan secara otomatis mencatat beban biaya admin (keluar) dan pendapatan jasa giro (masuk) untuk setiap rekening bank yang aktif pada periode yang dipilih.</p>
                                
                                <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                                    <?= CSRF::tokenField() ?>
                                    <input type="hidden" name="action" value="run_monthly_automation">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-gray-600">Pilih Periode Bulan</label>
                                        <input type="month" name="periode_bulan" class="form-control form-control-lg border-2" value="<?= e($periodeBulan) ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom w-100 py-3 shadow-sm">
                                        <i class="bi bi-play-fill me-1"></i> Mulai Proses Sekarang
                                    </button>
                                </form>
                                
                                <div class="mt-4 p-3 bg-white rounded-3 border-start border-4 border-info">
                                    <div class="small fw-semibold text-info mb-1"><i class="bi bi-info-circle me-1"></i> Informasi Standar</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">Sesuai standar pelaporan keuangan, pendapatan jasa giro bank seringkali dikategorikan sebagai Dana Tidak Terikat (Umum) kecuali ditentukan lain.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Log Posting Terbaru</h6>
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm table-hover align-middle">
                                    <thead class="bg-white sticky-top">
                                        <tr class="text-muted small text-uppercase">
                                            <th class="border-0">Periode</th>
                                            <th class="border-0">Bank</th>
                                            <th class="border-0">Jenis</th>
                                            <th class="border-0 text-end">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($postingList)): ?>
                                            <tr><td colspan="4" class="text-center py-5 text-muted italic">Belum ada riwayat posting otomatis.</td></tr>
                                        <?php endif; ?>
                                        <?php foreach ($postingList as $posting): ?>
                                            <tr>
                                                <td class="fw-bold text-gray-700"><?= e($posting['periode_bulan']) ?></td>
                                                <td class="small">
                                                    <span class="text-gray-800 d-block"><?= e($posting['nama_bank']) ?></span>
                                                    <span class="text-muted opacity-75"><?= e($posting['nomor_rekening']) ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($posting['posting_type'] === 'bank_admin_fee'): ?>
                                                        <span class="badge bg-danger-soft text-danger p-1 px-2 border-0">Biaya Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success-soft text-success p-1 px-2 border-0">Jasa Giro</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end fw-bold <?= $posting['posting_type'] === 'bank_admin_fee' ? 'text-danger' : 'text-success' ?>">
                                                    <?= ($posting['posting_type'] === 'bank_admin_fee' ? '-' : '+') . formatNumber((float)$posting['amount']) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ===================================================================
     MODALS FOR ADD / EDIT
==================================================================== -->

<!-- Modal Add Cash -->
<div class="modal fade" id="modalAddCash" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.2rem;">
            <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                <?= CSRF::tokenField() ?>
                <input type="hidden" name="action" value="add_cash">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <h5 class="modal-title fw-bold text-gray-800"><i class="bi bi-wallet2 me-2 text-primary"></i>Tambah Akun Kas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label-custom small fw-bold">Nama Kas Tunai</label>
                        <input type="text" name="nama_kas" class="form-control form-control-lg border-2" placeholder="Contoh: Kas Utama, Kas Operasional" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label-custom small fw-bold">Saldo Awal (Saat Inisialisasi)</label>
                        <input type="text" name="saldo_awal" class="form-control form-control-lg border-2 input-rupiah" value="0" required>
                        <small class="text-muted">Masukkan saldo fisik yang ada saat ini jika baru menggunakan sistem.</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label-custom small fw-bold">Status Keaktifan</label>
                        <select name="is_active" class="form-select form-select-lg border-2">
                            <option value="1">Aktif (Dapat digunakan transaksi)</option>
                            <option value="0">Tutup / Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Bank -->
<div class="modal fade" id="modalAddBank" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered px-md-5">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.2rem;">
            <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                <?= CSRF::tokenField() ?>
                <input type="hidden" name="action" value="add_bank">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <h5 class="modal-title fw-bold text-gray-800"><i class="bi bi-bank me-2 text-info"></i>Tambah Rekening Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label-custom small fw-bold">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control border-2" placeholder="Contoh: Bank Syariah Indonesia" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label-custom small fw-bold">Status</label>
                            <select name="is_active" class="form-select border-2 text-success fw-semibold">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch bg-light p-3 rounded-3 border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_public" id="is_public_add" value="1" checked>
                                <label class="form-check-label fw-bold text-gray-700" for="is_public_add">Tampilkan di Halaman Publik</label>
                                <small class="d-block text-muted mt-1">Jika diaktifkan, nomor rekening ini akan terlihat di dashboard publik untuk memfasilitasi donasi jamaah.</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" class="form-control border-2" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Nama Pemilik (Sesuai Buku Tabungan)</label>
                            <input type="text" name="nama_pemilik" class="form-control border-2" required>
                        </div>
                        <div class="col-md-12 py-2">
                            <hr class="m-0">
                            <div class="small fw-bold text-muted mt-2">PENGATURAN SALDO & AUTOMATION</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Saldo Awal</label>
                            <input type="text" name="saldo_awal" class="form-control border-2 input-rupiah" value="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom small fw-bold text-danger">Biaya Admin / Bln</label>
                            <input type="text" name="bank_admin_fee" class="form-control border-2 input-rupiah" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom small fw-bold text-success">Jasa Giro / Bln</label>
                            <input type="text" name="bank_interest" class="form-control border-2 input-rupiah" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Transfer Internal -->
<div class="modal fade" id="modalTransfer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.2rem;">
            <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                <?= CSRF::tokenField() ?>
                <input type="hidden" name="action" value="add_transfer">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <h5 class="modal-title fw-bold text-gray-800"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Transfer Dana Internal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Tanggal Pemindahan</label>
                            <input type="date" name="tanggal" class="form-control form-control-lg border-2" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold text-danger">Dari Akun Asal</label>
                            <select name="akun_asal" class="form-select form-select-lg border-2" required>
                                <option value="">-- Pilih Akun Asal --</option>
                                <?php foreach ($accountOptions as $option): ?>
                                    <option value="<?= e($option['value']) ?>"><?= e($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12 text-center py-0 my-0">
                            <i class="bi bi-arrow-down fs-4 text-muted"></i>
                        </div>
                        <div class="col-md-12 mt-0">
                            <label class="form-label-custom small fw-bold text-success">Ke Akun Tujuan</label>
                            <select name="akun_tujuan" class="form-select form-select-lg border-2" required>
                                <option value="">-- Pilih Akun Tujuan --</option>
                                <?php foreach ($accountOptions as $option): ?>
                                    <option value="<?= e($option['value']) ?>"><?= e($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Jumlah Dana (Rp)</label>
                            <input type="text" name="jumlah" class="form-control form-control-lg border-2 input-rupiah text-primary fw-bold" placeholder="0" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Fund Category</label>
                            <select name="fund_category" class="form-select border-2 shadow-none" required>
                                <option value="">-- Pilih Kategori Dana --</option>
                                <?php foreach (fundCategoryOptions() as $fundOption): ?>
                                    <option value="<?= e($fundOption) ?>" <?= $fundOption === 'Tidak Terikat' ? 'selected' : '' ?>><?= e($fundOption) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Biasanya dana operasional adalah 'Tidak Terikat'.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Keterangan Opsional</label>
                            <textarea name="keterangan" class="form-control border-2" rows="2" placeholder="Catatan mutasi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm">Proses Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT MODALS (CASH) ================= -->
<?php foreach ($cashList as $cash): ?>
<div class="modal fade" id="modalEditCash<?= $cash['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.2rem;">
            <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                <?= CSRF::tokenField() ?>
                <input type="hidden" name="action" value="edit_cash">
                <input type="hidden" name="id" value="<?= $cash['id'] ?>">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Akun Kas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label-custom small fw-bold">Nama Kas</label>
                        <input type="text" name="nama_kas" class="form-control form-control-lg border-2" value="<?= e($cash['nama_kas']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label-custom small fw-bold">Saldo Awal</label>
                        <input type="text" name="saldo_awal" class="form-control form-control-lg border-2 input-rupiah" value="<?= formatNumber((float)$cash['saldo_awal']) ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label-custom small fw-bold">Status</label>
                        <select name="is_active" class="form-select form-select-lg border-2">
                            <option value="1" <?= (int)$cash['is_active'] === 1 ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= (int)$cash['is_active'] === 0 ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- ================= EDIT MODALS (BANK) ================= -->
<?php foreach ($bankList as $bank): ?>
<div class="modal fade" id="modalEditBank<?= $bank['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered px-md-5">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.2rem;">
            <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                <?= CSRF::tokenField() ?>
                <input type="hidden" name="action" value="edit_bank">
                <input type="hidden" name="id" value="<?= $bank['id'] ?>">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Akun Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label-custom small fw-bold">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control border-2" value="<?= e($bank['nama_bank']) ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label-custom small fw-bold">Status</label>
                            <select name="is_active" class="form-select border-2">
                                <option value="1" <?= (int)$bank['is_active'] === 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= (int)$bank['is_active'] === 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch bg-light p-3 rounded-3 border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_public" id="is_public_edit_<?= $bank['id'] ?>" value="1" <?= (int)$bank['is_public'] === 1 ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold text-gray-700" for="is_public_edit_<?= $bank['id'] ?>">Tampilkan di Halaman Publik</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" class="form-control border-2" value="<?= e($bank['nomor_rekening']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Nama Pemilik</label>
                            <input type="text" name="nama_pemilik" class="form-control border-2" value="<?= e($bank['nama_pemilik']) ?>" required>
                        </div>
                        <div class="col-md-12 py-2">
                            <hr class="m-0">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Saldo Awal</label>
                            <input type="text" name="saldo_awal" class="form-control border-2 input-rupiah" value="<?= formatNumber((float)$bank['saldo_awal']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom small fw-bold text-danger">Biaya Admin / Bln</label>
                            <input type="text" name="bank_admin_fee" class="form-control border-2 input-rupiah" value="<?= formatNumber((float)$bank['bank_admin_fee']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom small fw-bold text-success">Jasa Giro / Bln</label>
                            <input type="text" name="bank_interest" class="form-control border-2 input-rupiah" value="<?= formatNumber((float)$bank['bank_interest']) ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- ================= EDIT MODALS (TRANSFER) ================= -->
<?php foreach ($transferList as $transfer): ?>
<div class="modal fade" id="modalEditTransfer<?= $transfer['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.2rem;">
            <form method="POST" action="<?= BASE_URL ?>/kas-bank">
                <?= CSRF::tokenField() ?>
                <input type="hidden" name="action" value="edit_transfer">
                <input type="hidden" name="id" value="<?= $transfer['id'] ?>">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <h5 class="modal-title fw-bold text-gray-800"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Transfer Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control border-2" value="<?= e($transfer['tanggal']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold text-danger">Dari Akun Asal</label>
                            <select name="akun_asal" class="form-select border-2" required>
                                <?php foreach ($accountOptions as $option): ?>
                                    <option value="<?= e($option['value']) ?>" <?= accountReferenceValue($transfer['akun_asal_type'], $transfer['akun_asal_id']) === $option['value'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold text-success">Ke Akun Tujuan</label>
                            <select name="akun_tujuan" class="form-select border-2" required>
                                <?php foreach ($accountOptions as $option): ?>
                                    <option value="<?= e($option['value']) ?>" <?= accountReferenceValue($transfer['akun_tujuan_type'], $transfer['akun_tujuan_id']) === $option['value'] ? 'selected' : '' ?>><?= e($option['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold text-primary">Saldo (Rp)</label>
                            <input type="text" name="jumlah" class="form-control border-2 input-rupiah fw-bold" value="<?= formatNumber((float)$transfer['jumlah']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold text-muted">Fund Category</label>
                            <select name="fund_category" class="form-select border-2" required>
                                <?php foreach (fundCategoryOptions() as $fundOption): ?>
                                    <option value="<?= e($fundOption) ?>" <?= $transfer['fund_category'] === $fundOption ? 'selected' : '' ?>><?= e($fundOption) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom small fw-bold">Keterangan</label>
                            <textarea name="keterangan" class="form-control border-2" rows="2"><?= e($transfer['keterangan'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary-custom px-4 py-2 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<style>
    /* Gradient Backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    }

    /* Soft Colors Badges & Avatars */
    .bg-primary-soft { background-color: rgba(var(--primary-rgb, 68, 172, 255), 0.1); }
    .bg-info-soft { background-color: rgba(0, 188, 212, 0.1); }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
    
    .text-primary-dark { color: #4e342e; } /* Placeholder for a darker primary */
    .text-info-dark { color: #00838f; }

    .bg-white-opacity-20 { background-color: rgba(255, 255, 255, 0.2); }
    
    .tracking-wider { letter-spacing: 0.05em; }
    
    /* Custom Tabs Styling */
    #kasBankTabs .nav-link {
        color: var(--gray-600);
        position: relative;
        transition: all 0.3s ease;
    }
    #kasBankTabs .nav-link.active {
        color: var(--primary);
        font-weight: 700;
        background: none;
    }
    #kasBankTabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 60%;
        height: 4px;
        background-color: var(--primary);
        border-radius: 4px 4px 0 0;
    }

    /* Scrollbar hide for tabs on mobile */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Table Adjustments */
    .table-hover tbody tr:hover {
        background-color: rgba(var(--primary-rgb), 0.02);
    }
    
    .avatar-sm {
        flex-shrink: 0;
    }
    
    .italic { font-style: italic; }
</style>
