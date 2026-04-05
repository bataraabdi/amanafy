<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-calendar-check text-primary me-2"></i>Laporan Periodik — <?= ucfirst($periode ?? '') ?></h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> <?= e($periodeLabel ?? '-') ?></span>
    </div>

    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="rpt-stat green">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <label>Total Pemasukan</label>
                        <h3><?= rupiah($totalMasuk ?? 0) ?></h3>
                    </div>
                </div>
                <p class="mt-1"><?= count($dataPemasukan ?? []) ?> transaksi</p>
                <i class="bi bi-wallet2 rpt-bg-icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rpt-stat red">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-graph-down-arrow"></i></div>
                    <div>
                        <label>Total Pengeluaran</label>
                        <h3><?= rupiah($totalKeluar ?? 0) ?></h3>
                    </div>
                </div>
                <p class="mt-1"><?= count($dataPengeluaran ?? []) ?> transaksi</p>
                <i class="bi bi-cart-x rpt-bg-icon"></i>
            </div>
        </div>
        <?php $saldoVal = $saldo ?? 0; ?>
        <div class="col-6 col-md-3">
            <div class="rpt-stat <?= $saldoVal >= 0 ? 'blue' : 'red' ?>">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-currency-exchange"></i></div>
                    <div>
                        <label><?= $saldoVal >= 0 ? 'Surplus' : 'Defisit' ?> Periode</label>
                        <h3><?= ($saldoVal < 0 ? '-' : '+') . rupiah(abs($saldoVal)) ?></h3>
                    </div>
                </div>
                <p class="mt-1">Sisa dana periode</p>
                <i class="bi bi-safe rpt-bg-icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="rpt-stat purple">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-bank"></i></div>
                    <div>
                        <label>Total Aset</label>
                        <h3><?= rupiah(($totalCashPosition ?? 0) + ($totalBankPosition ?? 0)) ?></h3>
                    </div>
                </div>
                <p class="mt-1">Kas + Bank</p>
                <i class="bi bi-pie-chart-fill rpt-bg-icon"></i>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="rpt-pills mb-3 no-print">
        <button class="rpt-pill active" onclick="switchTab(this,'tab-pemasukan')">
            <i class="bi bi-arrow-down-circle me-1"></i>Pemasukan (<?= count($dataPemasukan ?? []) ?>)
        </button>
        <button class="rpt-pill" onclick="switchTab(this,'tab-pengeluaran')">
            <i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran (<?= count($dataPengeluaran ?? []) ?>)
        </button>
        <button class="rpt-pill" onclick="switchTab(this,'tab-saldo')">
            <i class="bi bi-layers me-1"></i>Posisi Saldo
        </button>
    </div>

    <!-- Tab: Pemasukan -->
    <div class="tab-content-rpt" id="tab-pemasukan">
        <div class="rpt-card">
            <div class="rpt-card-header">
                <h6><i class="bi bi-arrow-down-circle-fill text-success"></i>Rincian Pemasukan — <?= e($periodeLabel ?? '') ?></h6>
            </div>
            <?php if (empty($dataPemasukan)): ?>
                <div class="rpt-empty"><i class="bi bi-inbox"></i><p>Tidak ada pemasukan pada periode ini.</p></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="rpt-table" id="tbl-pemasukan">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th class="d-none d-md-table-cell">Donatur</th>
                            <th class="d-none d-sm-table-cell">Akun</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataPemasukan as $item): ?>
                        <tr>
                            <td><?= formatDate($item['tanggal']) ?></td>
                            <td><span class="badge text-bg-success" style="font-size:.7rem;"><?= e($item['nama_kategori'] ?? '-') ?></span></td>
                            <td class="d-none d-md-table-cell text-muted small"><?= e($item['donatur_nama'] ?? '-') ?></td>
                            <td class="d-none d-sm-table-cell text-muted small"><?= e($item['nama_akun'] ?? '-') ?></td>
                            <td class="text-end fw-bold text-success">+ <?= rupiah((float)$item['jumlah']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end">TOTAL PEMASUKAN</td>
                            <td class="d-none d-sm-table-cell"></td>
                            <td class="text-end text-success"><?= rupiah($totalMasuk ?? 0) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tab: Pengeluaran -->
    <div class="tab-content-rpt" id="tab-pengeluaran" style="display:none;">
        <div class="rpt-card">
            <div class="rpt-card-header">
                <h6><i class="bi bi-arrow-up-circle-fill text-danger"></i>Rincian Pengeluaran — <?= e($periodeLabel ?? '') ?></h6>
            </div>
            <?php if (empty($dataPengeluaran)): ?>
                <div class="rpt-empty"><i class="bi bi-inbox"></i><p>Tidak ada pengeluaran pada periode ini.</p></div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="rpt-table" id="tbl-pengeluaran">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th class="d-none d-md-table-cell">Keterangan</th>
                            <th class="d-none d-sm-table-cell">Akun</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataPengeluaran as $item): ?>
                        <tr>
                            <td><?= formatDate($item['tanggal']) ?></td>
                            <td><span class="badge text-bg-danger" style="font-size:.7rem;"><?= e($item['nama_kategori'] ?? '-') ?></span></td>
                            <td class="d-none d-md-table-cell text-muted small"><?= e(mb_substr($item['keterangan'] ?? '-', 0, 50)) ?></td>
                            <td class="d-none d-sm-table-cell text-muted small"><?= e($item['nama_akun'] ?? '-') ?></td>
                            <td class="text-end fw-bold text-danger">- <?= rupiah((float)$item['jumlah']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end">TOTAL PENGELUARAN</td>
                            <td class="d-none d-sm-table-cell"></td>
                            <td class="text-end text-danger"><?= rupiah($totalKeluar ?? 0) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tab: Posisi Saldo -->
    <div class="tab-content-rpt" id="tab-saldo" style="display:none;">
        <div class="rpt-card">
            <div class="rpt-card-header">
                <h6><i class="bi bi-layers-fill text-primary"></i>Posisi Saldo Akun s.d. <?= e($endDate ?? '') ?></h6>
            </div>
            <div class="p-0">
                <!-- KAS TUNAI -->
                <span class="rpt-strip green">KAS TUNAI</span>
                <div class="table-responsive">
                    <table class="rpt-table">
                        <tbody>
                            <?php foreach ($cashPosition ?? [] as $cash): ?>
                            <tr>
                                <td class="ps-3 fw-semibold"><?= e($cash['nama_kas']) ?></td>
                                <td class="text-end pe-3 fw-bold text-success"><?= rupiah((float)($cash['saldo_posisi'] ?? 0)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($cashPosition)): ?>
                            <tr><td colspan="2" class="rpt-empty" style="padding:1rem;"><p>Tidak ada akun kas.</p></td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="ps-3">TOTAL KAS TUNAI</td>
                                <td class="text-end pe-3 text-success"><?= rupiah($totalCashPosition ?? 0) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- REKENING BANK -->
                <span class="rpt-strip blue">REKENING BANK</span>
                <div class="table-responsive">
                    <table class="rpt-table">
                        <tbody>
                            <?php foreach ($bankPosition ?? [] as $bank): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold"><?= e($bank['nama_bank']) ?></div>
                                    <div class="text-muted small"><?= e($bank['nomor_rekening']) ?></div>
                                </td>
                                <td class="text-end pe-3 fw-bold text-primary"><?= rupiah((float)($bank['saldo_posisi'] ?? 0)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($bankPosition)): ?>
                            <tr><td colspan="2" class="rpt-empty" style="padding:1rem;"><p>Tidak ada rekening bank.</p></td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="ps-3">TOTAL REKENING BANK</td>
                                <td class="text-end pe-3 text-primary"><?= rupiah($totalBankPosition ?? 0) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Total Bar -->
                <div class="rpt-total-bar">
                    <small>Total Aset Tersedia (Kas + Bank)</small>
                    <h4><?= rupiah(($totalCashPosition ?? 0) + ($totalBankPosition ?? 0)) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
</div>

<script>
function switchTab(btn, id) {
    document.querySelectorAll('.rpt-pill').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content-rpt').forEach(t => t.style.display = 'none');
    btn.classList.add('active');
    document.getElementById(id).style.display = 'block';
}

function exportRpt(type) {
    // For periodik: show all hidden tab sections before PDF render
    if (type === 'pdf') {
        document.querySelectorAll('.tab-content-rpt').forEach(t => t.style.display = 'block');
    }

    exportReport(type);

    // Restore tab visibility after a short delay (for PDF async)
    if (type === 'pdf') {
        setTimeout(() => {
            document.querySelectorAll('.tab-content-rpt').forEach((t, i) => {
                t.style.display = i === 0 ? 'block' : 'none';
            });
        }, 3000);
    }
}
</script>