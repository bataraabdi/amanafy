<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>
<style>
@media print {
    .nav-tabs { display: none !important; }
    .tab-pane { display: block !important; opacity: 1 !important; visibility: visible !important; }
    .tab-content > .tab-pane { display: block !important; }
}
</style>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-pie-chart-fill text-info me-2"></i>Posisi Dana per Kategori Fund</h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> s.d. <?= formatDate($endDate ?? date('Y-m-d')) ?></span>
    </div>

    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <?php if (empty($positions) && empty($kategoriPemasukan) && empty($kategoriPengeluaran)): ?>
        <div class="rpt-empty"><i class="bi bi-inbox"></i><p>Belum ada data posisi dana.</p></div>
    <?php else: ?>
        <?php
        $grand_total = $grand_total ?? array_sum(array_column($positions, 'total'));
        $colors = ['#3b82f6','#10b981','#8b5cf6','#ef4444','#f59e0b','#0d9488','#ec4899','#6366f1'];
        ?>

        <!-- Tabs container -->
        <ul class="nav nav-tabs no-print mb-4" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="posisi-tab" data-bs-toggle="tab" data-bs-target="#posisi-content" type="button" role="tab" aria-controls="posisi-content" aria-selected="true">Posisi Dana per Kategori</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="kategori-tab" data-bs-toggle="tab" data-bs-target="#kategori-content" type="button" role="tab" aria-controls="kategori-content" aria-selected="false">Rincian Kategori Pemasukan & Pengeluaran</button>
            </li>
        </ul>

        <div class="tab-content" id="reportTabsContent">
            <!-- Posisi Dana Tab -->
            <div class="tab-pane fade show active" id="posisi-content" role="tabpanel" aria-labelledby="posisi-tab">
                <!-- Fund Category Cards -->
        <div class="row g-3 mb-4">
            <?php foreach ($positions as $i => $pos):
                $pct = $grand_total > 0 ? round((float)$pos['total'] / $grand_total * 100, 1) : 0;
                $color = $colors[$i % count($colors)];
                $isNeg = (float)$pos['total'] < 0;
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="rpt-card text-center" style="border-top: 4px solid <?= $color ?>;">
                    <div class="rpt-card-body">
                        <!-- Donut mini chart -->
                        <div style="width:80px;height:80px;border-radius:50%;background:conic-gradient(<?= $color ?> 0deg <?= round($pct * 3.6) ?>deg, #e2e8f0 0deg);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                            <div style="width:52px;height:52px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:<?= $isNeg ? '#ef4444' : $color ?>;">
                                <?= $pct ?>%
                            </div>
                        </div>
                        <div class="fw-bold text-dark mb-1" style="font-size:.85rem;"><?= e($pos['fund_category']) ?></div>
                        <div class="fw-bold <?= $isNeg ? 'text-danger' : 'text-dark' ?>" style="font-size:.9rem;"><?= rupiah((float)$pos['total']) ?></div>
                        <!-- Progress bar -->
                        <div class="rpt-progress mt-2">
                            <div class="rpt-progress-bar" style="width:<?= $pct ?>%;background:<?= $isNeg ? '#ef4444' : $color ?>;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Detail Table -->
        <div class="rpt-card mb-4">
            <div class="rpt-card-header">
                <h6><i class="bi bi-table text-info"></i>Tabel Rincian Posisi Dana</h6>
            </div>
            <div class="table-responsive">
                <table class="rpt-table" id="posisi-table">
                    <thead>
                        <tr>
                            <th>Kategori Fund</th>
                            <th class="text-end">Saldo</th>
                            <th class="text-center" style="min-width:140px;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($positions as $i => $pos):
                            $pct = $grand_total > 0 ? round((float)$pos['total'] / $grand_total * 100, 1) : 0;
                            $color = $colors[$i % count($colors)];
                            $isNeg = (float)$pos['total'] < 0;
                        ?>
                        <tr>
                            <td>
                                <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:<?= $color ?>;margin-right:.5rem;"></span>
                                <strong><?= e($pos['fund_category']) ?></strong>
                            </td>
                            <td class="text-end fw-bold <?= $isNeg ? 'text-danger' : 'text-success' ?>"><?= rupiah((float)$pos['total']) ?></td>
                            <td class="text-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rpt-progress flex-grow-1">
                                        <div class="rpt-progress-bar" style="width:<?= $pct ?>%;background:<?= $isNeg ? '#ef4444' : $color ?>;"></div>
                                    </div>
                                    <span class="fw-bold" style="min-width:38px;font-size:.82rem;"><?= $pct ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-end">TOTAL KESELURUHAN</td>
                            <td class="text-end text-success"><?= rupiah($grand_total) ?></td>
                            <td class="text-center">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        </div> <!-- end Posisi Dana Tab -->

        <!-- Kategori Pemasukan & Pengeluaran Tab -->
        <div class="tab-pane fade" id="kategori-content" role="tabpanel" aria-labelledby="kategori-tab">
            <div class="row">
                <div class="col-md-6">
                    <div class="rpt-card mb-4" style="border-top: 4px solid #10b981;">
                        <div class="rpt-card-header">
                            <h6 class="mb-0"><i class="bi bi-box-arrow-in-down text-success me-2"></i>Kategori Pemasukan</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="rpt-table">
                                <thead>
                                    <tr>
                                        <th>Nama Kategori</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($kategoriPemasukan)): ?>
                                        <tr><td colspan="2" class="text-center text-muted">Belum ada data pemasukan.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($kategoriPemasukan as $kat): ?>
                                            <tr>
                                                <td><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#10b981;margin-right:.5rem;"></span><strong><?= e($kat['nama_kategori']) ?></strong></td>
                                                <td class="text-end fw-bold text-success"><?= rupiah((float)$kat['total']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-end fw-bold">TOTAL PEMASUKAN</td>
                                        <td class="text-end fw-bold text-success"><?= rupiah($totalMasukKat ?? 0) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="rpt-card mb-4" style="border-top: 4px solid #ef4444;">
                        <div class="rpt-card-header">
                            <h6 class="mb-0"><i class="bi bi-box-arrow-up text-danger me-2"></i>Kategori Pengeluaran</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="rpt-table">
                                <thead>
                                    <tr>
                                        <th>Nama Kategori</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($kategoriPengeluaran)): ?>
                                        <tr><td colspan="2" class="text-center text-muted">Belum ada data pengeluaran.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($kategoriPengeluaran as $kat): ?>
                                            <tr>
                                                <td><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#ef4444;margin-right:.5rem;"></span><strong><?= e($kat['nama_kategori']) ?></strong></td>
                                                <td class="text-end fw-bold text-danger"><?= rupiah((float)$kat['total']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-end fw-bold">TOTAL PENGELUARAN</td>
                                        <td class="text-end fw-bold text-danger"><?= rupiah($totalKeluarKat ?? 0) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end Kategori Tab -->
        </div> <!-- end tab content -->

        <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
    <?php endif; ?>
</div>
