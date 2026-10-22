<?php
/**
 * Buku Kas Umum (BKU) View
 * Template & tema disesuaikan 100% konsisten dengan halaman sistem lainnya
 */
$settings = $settings ?? [];
$periode = $periode ?? 'bulanan';
$periodeLabel = $periodeLabel ?? '-';
$startDate = $startDate ?? date('Y-m-01');
$endDate = $endDate ?? date('Y-m-t');
$prevDate = $prevDate ?? date('Y-m-d', strtotime($startDate . ' -1 day'));
$saldoAwal = $saldoAwal ?? 0.0;
$totalMasuk = $totalMasuk ?? 0.0;
$totalKeluar = $totalKeluar ?? 0.0;
$surplusDefisit = $surplusDefisit ?? 0.0;
$saldoAkhir = $saldoAkhir ?? 0.0;
$transactions = $transactions ?? [];
$accountRef = $accountRef ?? '';
$selectedAccountLabel = $selectedAccountLabel ?? 'Semua Akun (Kas & Bank)';
$cashAccountsList = $cashAccountsList ?? [];
$bankAccountsList = $bankAccountsList ?? [];
?>

<style>
/* Styling konsisten dengan modul Laporan lainnya */
.stat-card-lg {
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.10);
    transition: transform .2s, box-shadow .2s;
}
.stat-card-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(0,0,0,.15);
}
.stat-card-lg.green  { background: linear-gradient(135deg,#10b981 0%,#059669 100%); }
.stat-card-lg.red    { background: linear-gradient(135deg,#ef4444 0%,#dc2626 100%); }
.stat-card-lg.blue   { background: linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%); }
.stat-card-lg.purple { background: linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%); }

.stat-icon-bg {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: rgba(255,255,255,.22);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.stat-card-lg h2 {
    font-size: 1.45rem;
    font-weight: 700;
    margin: .35rem 0 .15rem;
    color: #fff;
}
.stat-card-lg small {
    color: rgba(255,255,255,.85);
    font-size: 0.8rem;
    font-weight: 500;
}
.period-badge {
    display: inline-block;
    background: rgba(255,255,255,.25);
    color: #fff;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .4px;
    margin-bottom: .4rem;
}
.btn-export {
    border-radius: 8px;
    padding: .45rem 1rem;
    font-size: .85rem;
    font-weight: 600;
    display: inline-flex; align-items: center; gap: .4rem;
}
.preset-link {
    font-size: 0.78rem;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
}
.preset-link:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.bku-opening-row td {
    background-color: #f8fafc !important;
    font-weight: 600;
}
</style>

<!-- Page Header (Tema Konsisten Halaman Kas Masjid) -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 no-print">
    <div>
        <h5 class="mb-1 fw-bold">Buku Kas Umum</h5>
        <small class="text-muted">Laporan arus transaksi penerimaan, pengeluaran, dan saldo kas/bank secara kronologis</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge text-bg-secondary rounded-pill px-3 py-2 fw-normal">
            <i class="bi bi-calendar3 me-1"></i> <?= e($periodeLabel) ?>
        </span>
    </div>
</div>

<!-- Card Filter & Tombol Export (Sama Persis Format Laporan Lain) -->
<div class="card-custom mb-4 no-print">
    <div class="card-body-custom py-3">
        <form method="GET" action="<?= BASE_URL ?>/laporan" id="formFilterBku" class="row g-2 align-items-end">
            <input type="hidden" name="report" value="buku-kas-umum">

            <!-- Pilihan Periode -->
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label-custom">Periode</label>
                <select name="periode" id="periodeSelectBku" class="form-select" onchange="togglePeriodFields(this.value)">
                    <option value="harian"  <?= $periode === 'harian'  ? 'selected' : '' ?>>Harian</option>
                    <option value="bulanan" <?= $periode === 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                    <option value="tahunan" <?= $periode === 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
                    <option value="kustom"  <?= $periode === 'kustom'  ? 'selected' : '' ?>>Kustom Kapanpun</option>
                </select>
            </div>

            <!-- Field Harian -->
            <div class="col-12 col-sm-6 col-md-2 periode-field-bku field-harian" style="<?= $periode !== 'harian' ? 'display:none;' : '' ?>">
                <label class="form-label-custom">Tanggal</label>
                <input type="date" name="tanggal" id="fieldTanggal" class="form-control" value="<?= e($tanggal ?? date('Y-m-d')) ?>">
            </div>

            <!-- Field Bulanan -->
            <div class="col-12 col-sm-6 col-md-2 periode-field-bku field-bulanan" style="<?= $periode !== 'bulanan' ? 'display:none;' : '' ?>">
                <label class="form-label-custom">Bulan</label>
                <input type="month" name="bulan" id="fieldBulan" class="form-control" value="<?= e($bulan ?? date('Y-m')) ?>">
            </div>

            <!-- Field Tahunan -->
            <div class="col-12 col-sm-6 col-md-2 periode-field-bku field-tahunan" style="<?= $periode !== 'tahunan' ? 'display:none;' : '' ?>">
                <label class="form-label-custom">Tahun</label>
                <select name="tahun" id="fieldTahun" class="form-select">
                    <?php for ($y = (int)date('Y') + 1; $y >= (int)date('Y') - 8; $y--): ?>
                        <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Field Kustom (Rentang Tanggal) -->
            <div class="col-12 col-sm-6 col-md-4 periode-field-bku field-kustom" style="<?= $periode !== 'kustom' ? 'display:none;' : '' ?>">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label-custom">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" id="fieldTanggalDari" class="form-control" value="<?= e($tanggal_dari ?? date('Y-m-01')) ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label-custom">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" id="fieldTanggalSampai" class="form-control" value="<?= e($tanggal_sampai ?? date('Y-m-t')) ?>">
                    </div>
                </div>
            </div>

            <!-- Filter Akun Kas / Bank -->
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label-custom">Akun Kas / Bank</label>
                <select name="account_ref" class="form-select">
                    <option value="all" <?= ($accountRef === '' || $accountRef === 'all') ? 'selected' : '' ?>>Semua Akun (Kas &amp; Bank)</option>
                    <optgroup label="Kas Tunai">
                        <?php foreach ($cashAccountsList as $c): ?>
                            <option value="cash:<?= $c['id'] ?>" <?= $accountRef === ('cash:' . $c['id']) ? 'selected' : '' ?>>
                                <?= e($c['nama_kas']) ?> (<?= rupiah((float)$c['saldo_saat_ini']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Rekening Bank">
                        <?php foreach ($bankAccountsList as $b): ?>
                            <option value="bank:<?= $b['id'] ?>" <?= $accountRef === ('bank:' . $b['id']) ? 'selected' : '' ?>>
                                <?= e($b['nama_bank']) ?> - <?= e($b['nomor_rekening']) ?> (<?= rupiah((float)$b['saldo_saat_ini']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="col-auto">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>
        </form>

        <!-- Preset Cepat & Tombol Export (Sama Persis Style & Tombol Halaman Lain) -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-3 border-top">
            <div class="d-flex align-items-center gap-1 flex-wrap">
                <span class="text-muted small me-1">Pintasan:</span>
                <button type="button" class="preset-link" onclick="setPreset('hari_ini')">Hari Ini</button>
                <button type="button" class="preset-link" onclick="setPreset('bulan_ini')">Bulan Ini</button>
                <button type="button" class="preset-link" onclick="setPreset('bulan_lalu')">Bulan Lalu</button>
                <button type="button" class="preset-link" onclick="setPreset('tahun_ini')">Tahun Ini</button>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="button" onclick="exportBku('excel')" class="btn btn-success btn-export" id="btnExportExcel">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </button>
                <button type="button" onclick="exportBku('pdf')" class="btn btn-danger btn-export" id="btnExportPdf">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </button>
                <button type="button" onclick="printBku()" class="btn btn-secondary btn-export" id="btnExportPrint">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 4 Key Stat Cards (Konsisten Tema Dashboard & Laporan) -->
<div class="row g-3 mb-4 no-print">
    <!-- Saldo Awal -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-lg blue">
            <span class="period-badge">Per <?= formatDate($prevDate) ?></span>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-bg"><i class="bi bi-wallet2"></i></div>
                <div>
                    <small>Saldo Awal</small>
                    <h2><?= rupiah($saldoAwal) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Penerimaan -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-lg green">
            <span class="period-badge">Penerimaan (Debet)</span>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-bg"><i class="bi bi-arrow-down-circle-fill"></i></div>
                <div>
                    <small>Total Penerimaan</small>
                    <h2><?= rupiah($totalMasuk) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pengeluaran -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-lg red">
            <span class="period-badge">Pengeluaran (Kredit)</span>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-bg"><i class="bi bi-arrow-up-circle-fill"></i></div>
                <div>
                    <small>Total Pengeluaran</small>
                    <h2><?= rupiah($totalKeluar) ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Saldo Akhir -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-lg purple">
            <span class="period-badge">Saldo Berjalan</span>
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-bg"><i class="bi bi-safe-fill"></i></div>
                <div>
                    <small>Saldo Akhir</small>
                    <h2><?= rupiah($saldoAkhir) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Area Cetak & Tabel Utama Buku Kas Umum -->
<div id="report-content" class="report-page printable-area" data-filename="buku-kas-umum-<?= e($periode) ?>-<?= date('Ymd') ?>">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <!-- Override dan Spesifikasi Style Cetak (Otomatis Margin & Pas Kertas) -->
    <style>
    @media print {
        @page {
            size: landscape;
            margin: 8mm 6mm 8mm 6mm !important;
        }

        /* Reset kontainer halaman cetak */
        html, body {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            color: #000 !important;
            font-size: 8.5pt !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Sembunyikan elemen navigasi, filter, dan tombol */
        .sidebar, 
        .top-header, 
        .navbar, 
        .no-print, 
        .d-print-none, 
        .btn, 
        .card-custom.no-print, 
        .card-header-custom,
        .preset-link,
        .sidebar-overlay,
        nav, 
        footer,
        .alert {
            display: none !important;
        }

        .main-content, 
        .content-wrapper, 
        .page-wrapper, 
        .container, 
        .container-fluid,
        #report-content, 
        .report-page, 
        .printable-area {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
            left: 0 !important;
            position: static !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }

        .card-custom, .card-body-custom {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .table-responsive {
            overflow: visible !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Tabel Otomatis Menyesuaikan Kertas Tanpa Terpotong */
        #tableBku {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
            margin: 0 0 10px 0 !important;
        }

        #tableBku thead {
            display: table-header-group;
        }

        #tableBku tfoot {
            display: table-footer-group;
        }

        #tableBku tr {
            page-break-inside: avoid;
        }

        #tableBku th, 
        #tableBku td {
            border: 1px solid #333 !important;
            padding: 4px 5px !important;
            font-size: 8pt !important;
            line-height: 1.25 !important;
            color: #000 !important;
            background: transparent !important;
            vertical-align: middle;
        }

        #tableBku th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            text-align: center;
        }

        /* Penataan Proporsional Kolom BKU (Total 100%) */
        #tableBku th:nth-child(1), #tableBku td:nth-child(1) { width: 4% !important; text-align: center; }  /* No */
        #tableBku th:nth-child(2), #tableBku td:nth-child(2) { width: 9% !important; white-space: nowrap; } /* Tanggal */
        #tableBku th:nth-child(3), #tableBku td:nth-child(3) { width: 11% !important; word-break: break-all; } /* No. Bukti */
        #tableBku th:nth-child(4), #tableBku td:nth-child(4) { width: 28% !important; word-break: break-word; } /* Uraian */
        #tableBku th:nth-child(5), #tableBku td:nth-child(5) { width: 12% !important; word-break: break-word; } /* Kas / Bank */
        #tableBku th:nth-child(6), #tableBku td:nth-child(6) { width: 12% !important; text-align: right; white-space: nowrap; } /* Penerimaan */
        #tableBku th:nth-child(7), #tableBku td:nth-child(7) { width: 12% !important; text-align: right; white-space: nowrap; } /* Pengeluaran */
        #tableBku th:nth-child(8), #tableBku td:nth-child(8) { width: 12% !important; text-align: right; white-space: nowrap; } /* Saldo */

        .d-none.d-print-block {
            display: block !important;
        }

        .report-kop {
            margin-bottom: 12px !important;
            padding-bottom: 8px !important;
        }

        .report-kop h2 {
            font-size: 15pt !important;
            margin-bottom: 2px !important;
        }

        .report-kop p {
            font-size: 8.5pt !important;
            margin-bottom: 0 !important;
        }

        .report-ttd {
            page-break-inside: avoid;
            margin-top: 15px !important;
        }
    }
    </style>

    <div class="d-none d-print-block text-center mb-3">
        <h4 class="fw-bold text-uppercase mb-1" style="color: #000;">BUKU KAS UMUM (BKU)</h4>
        <div style="font-size: 13px; color: #333;">Periode: <?= e($periodeLabel) ?> | Akun: <?= e($selectedAccountLabel) ?></div>
    </div>

    <div class="card-custom">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="bi bi-journal-text me-1 text-primary"></i> Rincian Buku Kas Umum</h6>
            <small class="text-muted"><?= count($transactions) ?> baris transaksi</small>
        </div>
        <div class="card-body-custom p-0">
            <div class="table-responsive">
                <table class="table-custom mb-0" id="tableBku" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 45px;" class="text-center">No</th>
                            <th style="width: 105px;">Tanggal</th>
                            <th style="width: 110px;">No. Bukti</th>
                            <th>Uraian</th>
                            <th style="width: 150px;">Kas / Bank</th>
                            <th style="width: 135px;" class="text-end">Penerimaan (Rp)</th>
                            <th style="width: 135px;" class="text-end">Pengeluaran (Rp)</th>
                            <th style="width: 145px;" class="text-end">Saldo (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris Saldo Awal -->
                        <tr class="bku-opening-row">
                            <td class="text-center text-muted">-</td>
                            <td class="text-muted"><?= formatDate($prevDate) ?></td>
                            <td class="text-muted">-</td>
                            <td>Saldo Awal</td>
                            <td><?= e($selectedAccountLabel) ?></td>
                            <td class="text-end text-muted">-</td>
                            <td class="text-end text-muted">-</td>
                            <td class="text-end fw-bold text-primary"><?= formatNumber($saldoAwal) ?></td>
                        </tr>

                        <!-- Baris Transaksi -->
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Tidak ada data transaksi pada periode ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($transactions as $tx): ?>
                                <?php 
                                    $masukVal = (float)$tx['masuk'];
                                    $keluarVal = (float)$tx['keluar'];
                                    // Uraian dibuat simpel & sederhana sesuai permintaan
                                    $uraianText = !empty($tx['keterangan']) ? $tx['keterangan'] : $tx['nama_kategori'];
                                ?>
                                <tr>
                                    <td class="text-center text-muted"><?= $no++ ?></td>
                                    <td><?= formatDate($tx['tanggal']) ?></td>
                                    <td><?= e($tx['no_bukti']) ?></td>
                                    <td><?= e($uraianText) ?></td>
                                    <td><?= e($tx['nama_akun']) ?></td>
                                    <td class="text-end fw-semibold text-success">
                                        <?= $masukVal > 0 ? formatNumber($masukVal) : '-' ?>
                                    </td>
                                    <td class="text-end fw-semibold text-danger">
                                        <?= $keluarVal > 0 ? formatNumber($keluarVal) : '-' ?>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        <?= formatNumber((float)$tx['saldo_berjalan']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold" style="background: #f8f9fa;">
                            <td colspan="5" class="text-end text-uppercase">Total Periode :</td>
                            <td class="text-end text-success"><?= formatNumber($totalMasuk) ?></td>
                            <td class="text-end text-danger"><?= formatNumber($totalKeluar) ?></td>
                            <td class="text-end text-muted small fw-normal">Net: <?= ($surplusDefisit >= 0 ? '+' : '-') . formatNumber(abs($surplusDefisit)) ?></td>
                        </tr>
                        <tr class="fw-bold" style="background: #e9ecef;">
                            <td colspan="5" class="text-end text-uppercase">SALDO AKHIR BUKU KAS UMUM :</td>
                            <td colspan="2" class="text-center text-muted small font-normal">(Saldo Awal + Masuk - Keluar)</td>
                            <td class="text-end text-primary fs-6"><?= formatNumber($saldoAkhir) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Tanda Tangan Block (Print & PDF) -->
    <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
</div>

<script>
// Toggle filter inputs
function togglePeriodFields(val) {
    document.querySelectorAll('.periode-field-bku').forEach(el => el.style.display = 'none');
    const target = document.querySelector('.field-' + val);
    if (target) {
        target.style.display = 'block';
    }
}

// Pintasan preset filter
function setPreset(preset) {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const sel = document.getElementById('periodeSelectBku');

    if (preset === 'hari_ini') {
        sel.value = 'harian';
        togglePeriodFields('harian');
        document.getElementById('fieldTanggal').value = `${yyyy}-${mm}-${dd}`;
    } else if (preset === 'bulan_ini') {
        sel.value = 'bulanan';
        togglePeriodFields('bulanan');
        document.getElementById('fieldBulan').value = `${yyyy}-${mm}`;
    } else if (preset === 'bulan_lalu') {
        let prevM = today.getMonth();
        let prevY = yyyy;
        if (prevM === 0) {
            prevM = 12;
            prevY = yyyy - 1;
        }
        sel.value = 'bulanan';
        togglePeriodFields('bulanan');
        document.getElementById('fieldBulan').value = `${prevY}-${String(prevM).padStart(2, '0')}`;
    } else if (preset === 'tahun_ini') {
        sel.value = 'tahunan';
        togglePeriodFields('tahunan');
        document.getElementById('fieldTahun').value = yyyy;
    }

    document.getElementById('formFilterBku').submit();
}

// Fungsi Export yang teruji dan handal
function exportBku(type) {
    const reportElem = document.getElementById('report-content') || document.getElementById('report-printable');
    const filename = (reportElem && reportElem.dataset && reportElem.dataset.filename) 
        ? reportElem.dataset.filename 
        : ('buku-kas-umum-' + new Date().toISOString().slice(0, 10));

    if (type === 'excel') {
        if (typeof XLSX === 'undefined') {
            alert('Library Excel belum siap. Silakan muat ulang halaman.');
            return;
        }

        const table = document.getElementById('tableBku');
        if (!table) {
            alert('Tabel Buku Kas Umum tidak ditemukan.');
            return;
        }

        // Ekspor langsung dari tabel DOM
        const wb = XLSX.utils.table_to_book(table, { sheet: "Buku Kas Umum" });
        XLSX.writeFile(wb, filename + '.xlsx');

    } else if (type === 'pdf') {
        const printArea = reportElem;
        if (!printArea) {
            window.print();
            return;
        }

        if (typeof html2pdf === 'undefined') {
            window.print();
            return;
        }

        const btn = document.getElementById('btnExportPdf');
        if (btn) btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengunduh...';

        // Tampilkan sementara blok tanda tangan & kop untuk render PDF
        const ttd = printArea.querySelector('.report-ttd');
        if (ttd) ttd.classList.remove('d-none', 'd-print-block');

        const kop = printArea.querySelector('.report-kop');
        if (kop) kop.classList.remove('d-none', 'd-print-block');

        const printTitle = printArea.querySelector('.d-none.d-print-block');
        if (printTitle) printTitle.classList.remove('d-none');

        const opt = {
            margin:       [8, 8, 8, 8],
            filename:     filename + '.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(printArea).save().then(function() {
            if (btn) btn.innerHTML = '<i class="bi bi-file-earmark-pdf"></i> PDF';
            if (ttd) ttd.classList.add('d-none', 'd-print-block');
            if (kop) kop.classList.add('d-none', 'd-print-block');
            if (printTitle) printTitle.classList.add('d-none');
        }).catch(function(err) {
            console.error('PDF error:', err);
            if (btn) btn.innerHTML = '<i class="bi bi-file-earmark-pdf"></i> PDF';
            if (ttd) ttd.classList.add('d-none', 'd-print-block');
            if (kop) kop.classList.add('d-none', 'd-print-block');
            if (printTitle) printTitle.classList.add('d-none');
            // Fallback cetak sistem
            window.print();
        });
    }
}

// Aliases untuk kompatibilitas tombol
function printBku() { window.print(); }
function exportReport(type) { exportBku(type); }
function exportRpt(type) { exportBku(type); }
function exportBkuExcel() { exportBku('excel'); }
</script>
