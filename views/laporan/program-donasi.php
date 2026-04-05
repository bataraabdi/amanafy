<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-heart-fill text-danger me-2"></i>Laporan Program Donasi</h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> <?= e($periode ?? 'Semua') ?></span>
    </div>

    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <?php if (empty($data)): ?>
        <div class="rpt-empty"><i class="bi bi-heart"></i><p>Belum ada program donasi aktif.</p></div>
    <?php else: ?>
        <!-- Program Donasi Cards -->
        <div class="row g-3 mb-4 no-print">
            <?php
            $colors_donasi = ['#ef4444','#8b5cf6','#3b82f6','#10b981','#f59e0b','#ec4899','#0d9488','#6366f1'];
            foreach ($data as $di => $item):
                $target    = (float)$item['donasi']['target_nominal'];
                $collected = (float)$item['pemasukan'];
                $spent     = (float)$item['pengeluaran'];
                $sisa_target = max(0, $target - $collected);
                $pct = min(100, $target > 0 ? round($collected / $target * 100, 1) : 0);
                $accentColor = $colors_donasi[$di % count($colors_donasi)];
            ?>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="rpt-card h-100" style="border-top: 4px solid <?= $accentColor ?>;">
                    <div class="position-relative" style="height:150px;overflow:hidden;background:#f8fafc;">
                        <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($item['donasi']['gambar'] ?? 'default.jpg') ?>"
                             style="width:100%;height:150px;object-fit:cover;"
                             onerror="this.style.display='none';this.parentElement.style.background='<?= $accentColor ?>';this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;\'><i class=\'bi bi-heart-fill\' style=\'color:rgba(255,255,255,.5);font-size:3rem;\'></i></div>'">
                        <?php if ($pct >= 100): ?>
                            <div style="position:absolute;top:10px;right:10px;">
                                <span class="badge bg-success rounded-pill"><i class="bi bi-check2-circle me-1"></i>Tercapai</span>
                            </div>
                        <?php endif; ?>
                        <div style="position:absolute;bottom:10px;right:12px;">
                            <span class="badge rounded-pill text-white" style="background:<?= $accentColor ?>;font-size:.85rem;padding:.3rem .8rem;"><?= $pct ?>%</span>
                        </div>
                    </div>
                    <div class="rpt-card-body">
                        <h6 class="fw-bold text-dark mb-1"><?= e($item['donasi']['nama_donasi']) ?></h6>
                        <p class="text-muted small mb-3"><?= e(mb_substr($item['donasi']['uraian'], 0, 80)) ?>...</p>

                        <!-- Progress -->
                        <div class="d-flex justify-content-between small mb-1" style="font-size:.78rem;">
                            <span class="text-success fw-bold">Terkumpul: <?= rupiah($collected) ?></span>
                            <span class="text-muted">Target: <?= rupiah($target) ?></span>
                        </div>
                        <div class="rpt-progress mb-2">
                            <div class="rpt-progress-bar" style="width:<?= $pct ?>%;background:<?= $pct >= 100 ? '#10b981' : $accentColor ?>;"></div>
                        </div>
                        <?php if ($sisa_target > 0): ?>
                            <div class="text-end small text-muted mb-3" style="font-size:.75rem;">Sisa target: <?= rupiah($sisa_target) ?></div>
                        <?php endif; ?>

                        <!-- Stats row -->
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div style="background:#f0fdf4;border-radius:10px;padding:.5rem .25rem;">
                                    <div class="text-success fw-bold" style="font-size:.82rem;"><?= rupiah($collected) ?></div>
                                    <div style="font-size:.65rem;color:#16a34a;text-transform:uppercase;font-weight:600;">Terkumpul</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background:#fef2f2;border-radius:10px;padding:.5rem .25rem;">
                                    <div class="text-danger fw-bold" style="font-size:.82rem;"><?= rupiah($spent) ?></div>
                                    <div style="font-size:.65rem;color:#dc2626;text-transform:uppercase;font-weight:600;">Disalurkan</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background:#eff6ff;border-radius:10px;padding:.5rem .25rem;">
                                    <div class="text-primary fw-bold" style="font-size:.82rem;"><?= rupiah($collected - $spent) ?></div>
                                    <div style="font-size:.65rem;color:#1d4ed8;text-transform:uppercase;font-weight:600;">Saldo</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Summary Table -->
        <div class="rpt-card">
            <div class="rpt-card-header">
                <h6><i class="bi bi-table text-danger"></i>Ringkasan Semua Program Donasi</h6>
            </div>
            <div class="table-responsive">
                <?php $grand_collected = 0; $grand_target = 0; $grand_spent = 0; ?>
                <table class="rpt-table" id="donasi-table">
                    <thead>
                        <tr>
                            <th>Program Donasi</th>
                            <th class="text-end d-none d-md-table-cell">Target</th>
                            <th class="text-end">Terkumpul</th>
                            <th class="text-center d-none d-sm-table-cell">Progress</th>
                            <th class="text-end">Saldo Dana</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $di => $item):
                            $target    = (float)$item['donasi']['target_nominal'];
                            $collected = (float)$item['pemasukan'];
                            $spent     = (float)$item['pengeluaran'];
                            $pct       = $target > 0 ? min(100, round($collected / $target * 100, 1)) : 0;
                            $grand_collected += $collected;
                            $grand_target    += $target;
                            $grand_spent     += $spent;
                            $accentColor = $colors_donasi[$di % count($colors_donasi)];
                        ?>
                        <tr>
                            <td><strong><?= e(mb_substr($item['donasi']['nama_donasi'], 0, 40)) ?></strong></td>
                            <td class="text-end text-muted d-none d-md-table-cell"><?= rupiah($target) ?></td>
                            <td class="text-end fw-bold text-success"><?= rupiah($collected) ?></td>
                            <td class="d-none d-sm-table-cell">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rpt-progress flex-grow-1">
                                        <div class="rpt-progress-bar <?= $pct >= 100 ? 'green' : 'blue' ?>" style="width:<?= $pct ?>%;"></div>
                                    </div>
                                    <span style="min-width:38px;font-size:.8rem;font-weight:700;"><?= $pct ?>%</span>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-primary"><?= rupiah($collected - $spent) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-end">GRAND TOTAL</td>
                            <td class="text-end text-muted d-none d-md-table-cell"><?= rupiah($grand_target) ?></td>
                            <td class="text-end text-success"><?= rupiah($grand_collected) ?></td>
                            <td class="text-center d-none d-sm-table-cell">
                                <?= number_format($grand_target > 0 ? ($grand_collected / $grand_target * 100) : 0, 1) ?>%
                            </td>
                            <td class="text-end text-primary"><?= rupiah($grand_collected - $grand_spent) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
    <?php endif; ?>
</div>
