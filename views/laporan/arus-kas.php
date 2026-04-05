<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-arrow-left-right text-primary me-2"></i>Laporan Arus Kas (Cash Flow)</h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> <?= e($periode ?? '-') ?></span>
    </div>

    <!-- Action Buttons -->
    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="rpt-stat green">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <label>Total Masuk</label>
                        <h3><?= rupiah($total_inflow ?? 0) ?></h3>
                    </div>
                </div>
                <i class="bi bi-wallet2 rpt-bg-icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="rpt-stat red">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-graph-down-arrow"></i></div>
                    <div>
                        <label>Total Keluar</label>
                        <h3><?= rupiah($total_outflow ?? 0) ?></h3>
                    </div>
                </div>
                <i class="bi bi-cart-x rpt-bg-icon"></i>
            </div>
        </div>
        <?php $netCashVal = $net_cash ?? 0; ?>
        <div class="col-12 col-md-4">
            <div class="rpt-stat <?= $netCashVal >= 0 ? 'blue' : 'red' ?>">
                <div class="d-flex align-items-center gap-2">
                    <div class="rpt-icon"><i class="bi bi-currency-exchange"></i></div>
                    <div>
                        <label>Net Cash Flow</label>
                        <h3><?= ($netCashVal < 0 ? '-' : '+') . rupiah(abs($netCashVal)) ?></h3>
                    </div>
                </div>
                <i class="bi bi-safe rpt-bg-icon"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="rpt-card">
        <div class="rpt-card-header">
            <h6><i class="bi bi-table text-primary"></i>Rincian Mutasi Arus Kas</h6>
        </div>
        <?php if (empty($cashflow)): ?>
            <div class="rpt-empty"><i class="bi bi-inbox"></i><p>Belum ada mutasi keuangan pada periode ini.</p></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Fund Category</th>
                            <th class="text-end">Pemasukan</th>
                            <th class="text-end">Pengeluaran</th>
                            <th class="text-end">Net Flow</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cashflow as $row): ?>
                        <tr>
                            <td><?= formatDate($row['date']) ?></td>
                            <td><span class="badge text-bg-light border"><?= e($row['fund_category']) ?></span></td>
                            <td class="text-end fw-semibold text-success"><?= rupiah((float)$row['inflow']) ?></td>
                            <td class="text-end fw-semibold text-danger"><?= rupiah((float)$row['outflow']) ?></td>
                            <td class="text-end fw-bold <?= (float)$row['net'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= (float)$row['net'] >= 0 ? '+' : '' ?><?= rupiah((float)$row['net']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end">GRAND TOTAL</td>
                            <td class="text-end text-success"><?= rupiah($total_inflow ?? 0) ?></td>
                            <td class="text-end text-danger"><?= rupiah($total_outflow ?? 0) ?></td>
                            <td class="text-end <?= $netCashVal >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= $netCashVal >= 0 ? '+' : '' ?><?= rupiah(abs($netCashVal)) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
</div>
