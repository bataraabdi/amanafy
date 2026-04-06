<?php
$available_reports = $available_reports ?? [];
$settings = $settings ?? [];
$jenis     = $jenis     ?? ($periode ?? 'bulanan');
$bulan     = $bulan     ?? date('Y-m');
$tahun     = $tahun     ?? date('Y');
$tanggal   = $tanggal   ?? date('Y-m-d');
$report    = $_GET['report'] ?? 'dashboard';

// Human-readable period label
$periodeLabel = '';
if ($jenis === 'harian') {
    $periodeLabel = 'Tanggal ' . formatDate($tanggal);
} elseif ($jenis === 'bulanan') {
    $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $parts = explode('-', $bulan);
    $periodeLabel = ($months[(int)($parts[1] ?? 1)] ?? '') . ' ' . ($parts[0] ?? $tahun);
} elseif ($jenis === 'tahunan') {
    $periodeLabel = 'Tahun ' . $tahun;
} elseif ($jenis === 'kustom') {
    $periodeLabel = formatDate($_GET['tanggal_dari'] ?? date('Y-m-01')) . ' s.d ' . formatDate($_GET['tanggal_sampai'] ?? date('Y-m-t'));
}
?>

<style>
/* ===== LAPORAN DASHBOARD STYLES ===== */
.laporan-dashboard .stat-card-lg {
    border-radius: 16px;
    padding: 1.5rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.12);
    transition: transform .2s, box-shadow .2s;
}
.laporan-dashboard .stat-card-lg:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,.18);
}
.laporan-dashboard .stat-card-lg.green  { background: linear-gradient(135deg,#10b981 0%,#059669 100%); }
.laporan-dashboard .stat-card-lg.red    { background: linear-gradient(135deg,#ef4444 0%,#dc2626 100%); }
.laporan-dashboard .stat-card-lg.blue   { background: linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%); }
.laporan-dashboard .stat-card-lg.purple { background: linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%); }

.laporan-dashboard .stat-icon-bg {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(255,255,255,.22);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; flex-shrink: 0;
}
.laporan-dashboard .stat-card-lg .stat-bg-icon {
    position: absolute;
    font-size: 7rem;
    right: -1rem; bottom: -1.5rem;
    opacity: .1;
    line-height: 1;
}
.laporan-dashboard .stat-card-lg h2 {
    font-size: 1.6rem;
    font-weight: 700;
    margin: .5rem 0 .2rem;
    color: #fff;
}
.laporan-dashboard .stat-card-lg small,
.laporan-dashboard .stat-card-lg p  { color: rgba(255,255,255,.85); }
.laporan-dashboard .stat-card-lg h6 { color: #fff; font-weight: 600; }

/* Period badge on card */
.laporan-dashboard .period-badge {
    display: inline-block;
    background: rgba(255,255,255,.25);
    color: #fff;
    border-radius: 20px;
    padding: 2px 12px;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .5px;
    margin-bottom: .5rem;
}

/* Filter card */
.laporan-dashboard .filter-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    padding: 1.25rem 1.5rem;
    border: 1px solid #e9ecef;
}

/* Export buttons */
.laporan-dashboard .btn-export {
    border-radius: 10px;
    padding: .5rem 1.1rem;
    font-size: .85rem;
    font-weight: 600;
    display: inline-flex; align-items: center; gap: .4rem;
}

/* Account position table */
.laporan-dashboard .acct-header {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: 1px;
    padding: .55rem 1rem;
    text-align: center;
}
.laporan-dashboard .acct-header.kas  { background: #10b981; color:#fff; border-radius: 8px 8px 0 0; }
.laporan-dashboard .acct-header.bank { background: #3b82f6; color:#fff; border-radius: 8px 8px 0 0; }

.laporan-dashboard .total-aset-bar {
    background: linear-gradient(135deg,#1e293b 0%,#334155 100%);
    color: #fff;
    border-radius: 0 0 16px 16px;
    padding: 1rem;
    text-align: center;
}
.laporan-dashboard .total-aset-bar small {
    font-size: .7rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    opacity: .75;
    display: block;
    margin-bottom: .25rem;
}
.laporan-dashboard .total-aset-bar h3 { font-weight: 800; font-size: 1.7rem; color:#fff; margin:0; }

/* Fund category badges */
.laporan-dashboard .fund-badge {
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 8px;
    padding: .35rem .9rem;
    font-weight: 600;
    font-size: .9rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.laporan-dashboard .fund-badge.terikat { background: #fef3c7; color: #92400e; }

/* Responsive chart canvas */
.laporan-dashboard .chart-wrap {
    position: relative;
    min-height: 220px;
}

/* Mobile adjustments */
@media (max-width: 767.98px) {
    .laporan-dashboard .stat-card-lg { padding: 1.1rem 1.2rem; }
    .laporan-dashboard .stat-card-lg h2 { font-size: 1.25rem; }
    .laporan-dashboard .filter-card { padding: 1rem; }
    .laporan-dashboard .btn-export { font-size: .78rem; padding: .4rem .85rem; }
    .laporan-dashboard .total-aset-bar h3 { font-size: 1.3rem; }
}

@media print {
    .no-print { display: none !important; }
    .printable-area { page-break-inside: avoid; }
    body { font-size: 11px !important; }
    #report-header, #report-footer { display: block !important; }
}
</style>

<div class="laporan-dashboard">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4 no-print">
        <div>
            <h4 class="fw-bold mb-1">Laporan Keuangan</h4>
            <p class="text-muted mb-0 small">Pilih laporan & periode, kemudian export PDF / Excel / Print</p>
        </div>
        <?php if ($periodeLabel): ?>
        <span class="badge text-bg-secondary rounded-pill px-3 py-2 fw-normal">
            <i class="bi bi-calendar3 me-1"></i> <?= e($periodeLabel) ?>
        </span>
        <?php endif; ?>
    </div>

    <!-- Filter & Export Row -->
    <div class="filter-card mb-4 no-print">
        <form method="GET" class="row g-2 align-items-end" id="laporanForm">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-semibold small mb-1">Pilih Laporan</label>
                <select name="report" class="form-select" onchange="this.form.submit()">
                    <option value="dashboard" <?= $report === 'dashboard' ? 'selected' : '' ?>>Dashboard Ringkasan</option>
                    <?php foreach ($available_reports as $key => $label): ?>
                        <option value="<?= e($key) ?>" <?= $report === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php if ($report === 'arus-kas'): ?>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-semibold small mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_dari" class="form-control" value="<?= e($_GET['tanggal_dari'] ?? date('Y-m-01')) ?>" required>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label fw-semibold small mb-1">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="<?= e($_GET['tanggal_sampai'] ?? date('Y-m-t')) ?>" required>
            </div>
            <input type="hidden" name="periode" value="kustom">
            <?php else: ?>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label fw-semibold small mb-1">Periode</label>
                <select name="periode" id="periodeSelect" class="form-select">
                    <option value="harian"  <?= $jenis === 'harian'  ? 'selected' : '' ?>>Harian</option>
                    <option value="bulanan" <?= $jenis === 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                    <option value="tahunan" <?= $jenis === 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
                    <option value="kustom" <?= $jenis === 'kustom' ? 'selected' : '' ?>>Kustom Kapanpun</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2 periode-field harian" style="<?= $jenis !== 'harian'  ? 'display:none' : '' ?>">
                <label class="form-label fw-semibold small mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= e($tanggal) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-2 periode-field bulanan" style="<?= $jenis !== 'bulanan' ? 'display:none' : '' ?>">
                <label class="form-label fw-semibold small mb-1">Bulan</label>
                <input type="month" name="bulan" class="form-control" value="<?= e($bulan) ?>">
            </div>
            <div class="col-12 col-sm-6 col-md-2 periode-field tahunan" style="<?= $jenis !== 'tahunan' ? 'display:none' : '' ?>">
                <label class="form-label fw-semibold small mb-1">Tahun</label>
                <select name="tahun" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 10; $y--): ?>
                        <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4 periode-field kustom" style="<?= $jenis !== 'kustom' ? 'display:none' : '' ?>">
                <div class="d-flex gap-2">
                    <div>
                        <label class="form-label fw-semibold small mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="<?= e($_GET['tanggal_dari'] ?? date('Y-m-01')) ?>">
                    </div>
                    <div>
                        <label class="form-label fw-semibold small mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="<?= e($_GET['tanggal_sampai'] ?? date('Y-m-t')) ?>">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-export">
                    <i class="bi bi-search"></i> <span class="d-none d-sm-inline">Tampilkan</span>
                </button>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
            <button onclick="exportReport('excel')" class="btn btn-success btn-export">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </button>
            <button onclick="exportReport('pdf')" class="btn btn-danger btn-export">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </button>
            <button onclick="window.print()" class="btn btn-secondary btn-export">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <!-- Report Content Area -->
    <div id="report-content" class="printable-area">
        <?php if ($report === 'dashboard'): ?>

            <!-- ===== SUMMARY CARDS ===== -->
            <div class="row g-3 mb-4">
                <!-- Pemasukan -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card-lg green">
                        <span class="period-badge"><?= e($periodeLabel ?: 'Semua Periode') ?></span>
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon-bg"><i class="bi bi-wallet2"></i></div>
                            <div>
                                <h6 class="mb-0 text-uppercase" style="font-size:.75rem;letter-spacing:1px;">Total Pemasukan</h6>
                                <h2 class="mb-0"><?= rupiah($totalMasuk ?? 0) ?></h2>
                            </div>
                        </div>
                        <p class="mb-0 mt-2 small"><?= count($dataPemasukan ?? []) ?> transaksi pada periode ini</p>
                        <i class="bi bi-graph-up-arrow stat-bg-icon"></i>
                    </div>
                </div>
                <!-- Pengeluaran -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card-lg red">
                        <span class="period-badge"><?= e($periodeLabel ?: 'Semua Periode') ?></span>
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon-bg"><i class="bi bi-cart-x"></i></div>
                            <div>
                                <h6 class="mb-0 text-uppercase" style="font-size:.75rem;letter-spacing:1px;">Total Pengeluaran</h6>
                                <h2 class="mb-0"><?= rupiah($totalKeluar ?? 0) ?></h2>
                            </div>
                        </div>
                        <p class="mb-0 mt-2 small"><?= count($dataPengeluaran ?? []) ?> transaksi pada periode ini</p>
                        <i class="bi bi-graph-down-arrow stat-bg-icon"></i>
                    </div>
                </div>
                <!-- Net Saldo -->
                <?php $saldoVal = ($totalMasuk ?? 0) - ($totalKeluar ?? 0); ?>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card-lg <?= $saldoVal >= 0 ? 'blue' : 'red' ?>">
                        <span class="period-badge"><?= e($periodeLabel ?: 'Semua Periode') ?></span>
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon-bg"><i class="bi bi-currency-exchange"></i></div>
                            <div>
                                <h6 class="mb-0 text-uppercase" style="font-size:.75rem;letter-spacing:1px;"><?= $saldoVal >= 0 ? 'Surplus' : 'Defisit' ?> Periode</h6>
                                <h2 class="mb-0"><?= ($saldoVal < 0 ? '-' : '') . rupiah(abs($saldoVal)) ?></h2>
                            </div>
                        </div>
                        <p class="mb-0 mt-2 small">Sisa dana periode berjalan</p>
                        <i class="bi bi-safe stat-bg-icon"></i>
                    </div>
                </div>
                <!-- Total Aset -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card-lg purple">
                        <span class="period-badge">Saldo Aktual</span>
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon-bg"><i class="bi bi-bank"></i></div>
                            <div>
                                <h6 class="mb-0 text-uppercase" style="font-size:.75rem;letter-spacing:1px;">Total Aset Tersedia</h6>
                                <h2 class="mb-0"><?= rupiah(($totalCashPosition ?? 0) + ($totalBankPosition ?? 0)) ?></h2>
                            </div>
                        </div>
                        <p class="mb-0 mt-2 small">Kas tunai + rekening bank</p>
                        <i class="bi bi-pie-chart-fill stat-bg-icon"></i>
                    </div>
                </div>
            </div>

            <!-- ===== CHARTS ===== -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 h-100" style="border-radius:16px;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-pie-chart-fill text-success me-2"></i>Komposisi Pemasukan
                            </h6>
                            <small class="text-muted">Berdasarkan kategori (semua waktu)</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart-wrap d-flex justify-content-center align-items-center">
                                <?php if (empty($pemasukanByKat) || array_sum(array_column($pemasukanByKat, 'total')) == 0): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                        <small>Belum ada data pemasukan</small>
                                    </div>
                                <?php else: ?>
                                    <canvas id="chartPemasukan" style="max-height:250px;"></canvas>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 h-100" style="border-radius:16px;">
                        <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                            <h6 class="fw-bold text-dark mb-0">
                                <i class="bi bi-pie-chart-fill text-danger me-2"></i>Komposisi Pengeluaran
                            </h6>
                            <small class="text-muted">Berdasarkan kategori (semua waktu)</small>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart-wrap d-flex justify-content-center align-items-center">
                                <?php if (empty($pengeluaranByKat) || array_sum(array_column($pengeluaranByKat, 'total')) == 0): ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                        <small>Belum ada data pengeluaran</small>
                                    </div>
                                <?php else: ?>
                                    <canvas id="chartPengeluaran" style="max-height:250px;"></canvas>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== POSISI SALDO AKUN ===== -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius:16px; overflow:hidden;">
                <div class="card-header bg-white border-bottom px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-layers-fill text-primary me-2"></i>Posisi Saldo Akun Terkini
                    </h6>
                    <span class="badge text-bg-primary rounded-pill fw-normal px-3 py-2" style="font-size:.78rem;">
                        s.d. <?= e($reportEndDate ?? date('Y-m-d')) ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Kas Tunai -->
                        <div class="col-12 col-md-6" style="border-right: 1px solid #e9ecef;">
                            <div class="acct-header kas">KAS TUNAI</div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <?php foreach ($cashPosition ?? [] as $cash): ?>
                                        <tr>
                                            <td class="ps-4 fw-semibold text-dark"><?= e($cash['nama_kas']) ?></td>
                                            <td class="text-end pe-4 fw-bold text-success"><?= rupiah((float)($cash['saldo_posisi'] ?? 0)) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($cashPosition)): ?>
                                        <tr><td colspan="2" class="text-center text-muted py-4"><i class="bi bi-inbox me-1"></i> Kosong</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td class="ps-4 fw-bold text-dark py-3">TOTAL KAS TUNAI</td>
                                            <td class="text-end pe-4 fw-bold text-success fs-6 py-3"><?= rupiah($totalCashPosition ?? 0) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- Rekening Bank -->
                        <div class="col-12 col-md-6">
                            <div class="acct-header bank">REKENING BANK</div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <tbody>
                                        <?php foreach ($bankPosition ?? [] as $bank): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold text-dark"><?= e($bank['nama_bank']) ?></div>
                                                <div class="small text-muted"><?= e($bank['nomor_rekening']) ?></div>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-primary"><?= rupiah((float)($bank['saldo_posisi'] ?? 0)) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($bankPosition)): ?>
                                        <tr><td colspan="2" class="text-center text-muted py-4"><i class="bi bi-inbox me-1"></i> Kosong</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td class="ps-4 fw-bold text-dark py-3">TOTAL REKENING BANK</td>
                                            <td class="text-end pe-4 fw-bold text-primary fs-6 py-3"><?= rupiah($totalBankPosition ?? 0) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="total-aset-bar">
                    <small>Total Aset Tersedia (Kas + Bank)</small>
                    <h3><?= rupiah(($totalCashPosition ?? 0) + ($totalBankPosition ?? 0)) ?></h3>
                </div>
            </div>

            <!-- ===== POSISI DANA PER KATEGORI FUND ===== -->
            <?php if (!empty($positionByFund)): ?>
            <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
                <div class="card-header bg-white border-0 px-4 pt-3 pb-0">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-diagram-3-fill text-info me-2"></i>Posisi Dana per Kategori (ISAK 35)
                    </h6>
                    <small class="text-muted">Saldo bersih berdasarkan klasifikasi dana</small>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <?php foreach ($positionByFund as $fund): ?>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="fund-badge <?= strtolower($fund['fund_category']) === 'terikat' ? 'terikat' : '' ?>">
                                <span><?= e($fund['fund_category']) ?></span>
                                <span><?= rupiah((float)$fund['total']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ===== TRANSAKSI PERIODE INI ===== -->
            <?php if (!empty($dataPemasukan) || !empty($dataPengeluaran)): ?>
            <div class="row g-3 mb-4">
                <!-- Pemasukan periode -->
                <?php if (!empty($dataPemasukan)): ?>
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm border-0 h-100" style="border-radius:16px;">
                        <div class="card-header bg-white border-0 px-4 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-success mb-0"><i class="bi bi-arrow-down-circle-fill me-2"></i>Pemasukan Periode Ini</h6>
                            <span class="badge text-bg-success rounded-pill"><?= count($dataPemasukan) ?></span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height:300px; overflow-y:auto;">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="ps-3" style="font-size:.78rem;">Tanggal</th>
                                            <th style="font-size:.78rem;">Keterangan</th>
                                            <th class="text-end pe-3" style="font-size:.78rem;">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dataPemasukan as $row): ?>
                                        <tr>
                                            <td class="ps-3 small text-muted"><?= formatDate($row['tanggal']) ?></td>
                                            <td class="small"><?= e($row['keterangan'] ?: ($row['nama_kategori'] ?? '-')) ?></td>
                                            <td class="text-end pe-3 fw-semibold text-success small"><?= rupiah((float)$row['jumlah']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Pengeluaran periode -->
                <?php if (!empty($dataPengeluaran)): ?>
                <div class="col-12 col-lg-6">
                    <div class="card shadow-sm border-0 h-100" style="border-radius:16px;">
                        <div class="card-header bg-white border-0 px-4 pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-danger mb-0"><i class="bi bi-arrow-up-circle-fill me-2"></i>Pengeluaran Periode Ini</h6>
                            <span class="badge text-bg-danger rounded-pill"><?= count($dataPengeluaran) ?></span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height:300px; overflow-y:auto;">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="ps-3" style="font-size:.78rem;">Tanggal</th>
                                            <th style="font-size:.78rem;">Keterangan</th>
                                            <th class="text-end pe-3" style="font-size:.78rem;">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dataPengeluaran as $row): ?>
                                        <tr>
                                            <td class="ps-3 small text-muted"><?= formatDate($row['tanggal']) ?></td>
                                            <td class="small"><?= e($row['keterangan'] ?: ($row['nama_kategori'] ?? '-')) ?></td>
                                            <td class="text-end pe-3 fw-semibold text-danger small"><?= rupiah((float)$row['jumlah']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Charts Script -->
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Chart === 'undefined') return;
                Chart.defaults.font.family = "'Inter','Poppins',sans-serif";
                Chart.defaults.color = '#64748b';

                const isMobile = window.innerWidth < 640;
                const legendPos = isMobile ? 'bottom' : 'right';

                const chartOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: legendPos,
                            labels: { usePointStyle: true, padding: 14, boxWidth: 9, font: { size: 11 } }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15,23,42,.9)',
                            titleFont: { size: 12 }, bodyFont: { size: 13, weight: 'bold' },
                            padding: 10, cornerRadius: 8,
                            callbacks: {
                                label: function (ctx) {
                                    let lbl = ctx.label ? ctx.label + ': ' : '';
                                    lbl += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(ctx.raw);
                                    return lbl;
                                }
                            }
                        }
                    },
                    cutout: '62%'
                };

                <?php if (!empty($pemasukanByKat) && array_sum(array_column($pemasukanByKat, 'total')) > 0): ?>
                new Chart(document.getElementById('chartPemasukan'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= json_encode(array_column($pemasukanByKat, 'nama_kategori')) ?>,
                        datasets: [{
                            data: <?= json_encode(array_map('floatval', array_column($pemasukanByKat, 'total'))) ?>,
                            backgroundColor: ['#10b981','#34d399','#059669','#6ee7b7','#047857','#a7f3d0','#065f46','#d1fae5'],
                            borderWidth: 2, borderColor: '#fff', hoverOffset: 5
                        }]
                    },
                    options: chartOptions
                });
                <?php endif; ?>

                <?php if (!empty($pengeluaranByKat) && array_sum(array_column($pengeluaranByKat, 'total')) > 0): ?>
                new Chart(document.getElementById('chartPengeluaran'), {
                    type: 'doughnut',
                    data: {
                        labels: <?= json_encode(array_column($pengeluaranByKat, 'nama_kategori')) ?>,
                        datasets: [{
                            data: <?= json_encode(array_map('floatval', array_column($pengeluaranByKat, 'total'))) ?>,
                            backgroundColor: ['#ef4444','#f87171','#dc2626','#fca5a5','#b91c1c','#fecaca','#7f1d1d','#fee2e2'],
                            borderWidth: 2, borderColor: '#fff', hoverOffset: 5
                        }]
                    },
                    options: chartOptions
                });
                <?php endif; ?>
            });
            </script>

        <?php else: ?>
            <?= $report_content ?? '<div class="alert alert-info">Memuat laporan...</div>' ?>
        <?php endif; ?>
    </div><!-- /report-content -->

</div><!-- /laporan-dashboard -->

<!-- Print Header -->
<div id="report-header" class="d-print-block" style="display:none;">
    <div class="text-center mb-4 pb-4 border-bottom">
        <h2 class="fw-bold"><?= e($settings['nama_masjid'] ?? 'Masjid') ?></h2>
        <p class="mb-1"><?= e($settings['alamat'] ?? '') ?></p>
        <p class="mb-3"><?= e($settings['no_telepon'] ?? '') ?></p>
        <h4 class="text-primary">LAPORAN <?= strtoupper($pageTitle ?? '') ?></h4>
        <p class="fw-semibold">Periode: <?= e($periodeLabel ?: '-') ?> | Dibuat: <?= e($settings['dibuat'] ?? date('d M Y')) ?></p>
    </div>
</div>

<!-- Print Footer -->
<div id="report-footer" class="d-print-block mt-5 pt-4 border-top text-center" style="display:none; font-family: Arial, sans-serif;">
    <div class="row">
        <div class="col-4">
            <p class="mb-5 pb-4">Mengetahui,<br><b><?= e($settings['jabatan_ketua'] ?? 'Ketua') ?></b></p>
            <p class="mb-0"><u><b><?= e($settings['ketua'] ?? 'Nama Ketua') ?></b></u></p>
        </div>
        <div class="col-4">
            <p class="mb-5 pb-4"><br><b><?= e($settings['jabatan_sekretaris'] ?? 'Sekretaris') ?></b></p>
            <p class="mb-0"><u><b><?= e($settings['sekretaris'] ?? 'Nama Sekretaris') ?></b></u></p>
        </div>
        <div class="col-4">
            <p class="mb-5 pb-4"><?= e(date('d F Y', strtotime($reportEndDate ?? date('Y-m-d')))) ?>,<br><b><?= e($settings['jabatan_bendahara'] ?? 'Bendahara') ?></b></p>
            <p class="mb-0"><u><b><?= e($settings['bendahara'] ?? 'Nama Bendahara') ?></b></u></p>
        </div>
    </div>
</div>

<!-- Export & Periode Scripts -->
<script>
// Toggle periode filter fields
const periodeSelect = document.getElementById('periodeSelect');
if (periodeSelect) {
    periodeSelect.addEventListener('change', function () {
        document.querySelectorAll('.periode-field').forEach(el => el.style.display = 'none');
        const target = document.querySelector('.periode-field.' + this.value);
        if (target) target.style.display = 'block';
    });
}
</script>