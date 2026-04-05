<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-wallet2 text-success me-2"></i>Laporan Kas Tunai</h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> s.d. <?= e($endDate ?? date('Y-m-d')) ?></span>
    </div>

    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <?php if (empty($accounts)): ?>
        <div class="rpt-empty"><i class="bi bi-inbox"></i><p>Belum ada akun kas tunai.</p></div>
    <?php else: ?>
        <?php $grand_total = 0; ?>
        <div class="row g-3 mb-4">
            <?php foreach ($accounts as $id => $acc):
                $total = (float)$acc['balance'];
                $grand_total += $total;
                $mutCount = count($acc['mutations'] ?? []);
            ?>
            <div class="col-12 col-md-6">
                <div class="rpt-card h-100">
                    <div class="rpt-card-header">
                        <h6><i class="bi bi-cash-stack text-success"></i><?= e($acc['account']['nama_kas']) ?></h6>
                        <span class="badge <?= $acc['account']['is_active'] ? 'text-bg-success' : 'text-bg-secondary' ?> rounded-pill">
                            <?= $acc['account']['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                    <div class="rpt-card-body">
                        <!-- Balance Summary -->
                        <div class="row text-center g-2 mb-3">
                            <div class="col-6">
                                <div class="rpt-stat green" style="padding:.75rem 1rem;">
                                    <label>Saldo Akhir Periode</label>
                                    <h3><?= rupiah($total) ?></h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rpt-stat teal" style="padding:.75rem 1rem;">
                                    <label>Jumlah Mutasi</label>
                                    <h3><?= $mutCount ?></h3>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($acc['mutations'])): ?>
                        <p class="text-muted fw-bold mb-2" style="font-size:.75rem;letter-spacing:.5px;text-transform:uppercase;">Mutasi Terbaru</p>
                        <div class="table-responsive">
                            <table class="rpt-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($acc['mutations'], 0, 6) as $mut): ?>
                                    <tr>
                                        <td><?= formatDate($mut['tanggal']) ?></td>
                                        <td>
                                            <span class="badge <?= $mut['entry_type'] == 'debet' ? 'text-bg-success' : 'text-bg-danger' ?>" style="font-size:.7rem;">
                                                <?= $mut['entry_type'] == 'debet' ? '+' : '-' ?>
                                            </span>
                                            <?= e($mut['fund_category']) ?>
                                        </td>
                                        <td class="text-end fw-semibold <?= $mut['entry_type'] == 'debet' ? 'text-success' : 'text-danger' ?>">
                                            <?= $mut['entry_type'] == 'debet' ? '+' : '-' ?><?= rupiah((float)$mut['amount']) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <p class="text-center text-muted small">Tidak ada riwayat mutasi pada periode ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Grand Total Bar -->
        <div class="rpt-total-bar" style="border-radius:14px;">
            <small>Total Saldo Kas Tunai Keseluruhan (<?= count($accounts) ?> Akun)</small>
            <h4><?= rupiah($grand_total) ?></h4>
        </div>

        <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
    <?php endif; ?>
</div>
