<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-check2-square text-warning me-2"></i>Realisasi Anggaran Kegiatan</h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> <?= e($periode ?? 'Semua') ?></span>
    </div>

    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <?php if (empty($data)): ?>
        <div class="rpt-empty"><i class="bi bi-calendar-event"></i><p>Belum ada kegiatan dengan realisasi anggaran.</p></div>
    <?php else: ?>
        <!-- Summary Cards (filled by JS) -->
        <div class="row g-3 mb-4 no-print" id="summary-cards">
            <div class="col-6 col-md-3">
                <div class="rpt-stat blue" style="padding:.85rem 1rem;">
                    <label>Total Kegiatan</label>
                    <h3><?= count($data) ?></h3>
                    <i class="bi bi-calendar3 rpt-bg-icon"></i>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="rpt-stat green" style="padding:.85rem 1rem;">
                    <label>Rata-rata Progress</label>
                    <h3 id="avg-progress">-</h3>
                    <i class="bi bi-clock-history rpt-bg-icon"></i>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="rpt-stat teal" style="padding:.85rem 1rem;">
                    <label>On Track (&lt;80%)</label>
                    <h3 id="on-track">-</h3>
                    <i class="bi bi-check-circle rpt-bg-icon"></i>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="rpt-stat red" style="padding:.85rem 1rem;">
                    <label>Over Budget</label>
                    <h3 id="over-budget">-</h3>
                    <i class="bi bi-exclamation-triangle rpt-bg-icon"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="rpt-card">
            <div class="rpt-card-header">
                <h6><i class="bi bi-table text-warning"></i>Detail Realisasi per Kegiatan</h6>
            </div>
            <div class="table-responsive">
                <table class="rpt-table" id="realisasi-table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th class="text-end d-none d-md-table-cell">Anggaran</th>
                            <th class="text-end">Pengeluaran</th>
                            <th class="text-center d-none d-sm-table-cell" style="min-width:110px;">Progress</th>
                            <th class="text-end">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item):
                            $budget  = (float)$item['kegiatan']['jumlah_anggaran'];
                            $real_out = (float)$item['pengeluaran'];
                            $progress = $budget > 0 ? min(100, round($real_out / $budget * 100)) : 0;
                            $sisa     = $budget - $real_out;
                            $progColor = $progress > 80 ? 'red' : ($progress > 50 ? 'yellow' : 'green');
                        ?>
                        <tr class="<?= $sisa < 0 ? 'table-danger' : '' ?>">
                            <td>
                                <strong class="text-dark"><?= e($item['kegiatan']['nama_kegiatan']) ?></strong>
                                <?php if (!empty($item['kegiatan']['keterangan'])): ?>
                                    <div class="text-muted small"><?= e(mb_substr($item['kegiatan']['keterangan'], 0, 60)) ?>...</div>
                                <?php endif; ?>
                                <!-- Mobile-only inline data -->
                                <div class="d-sm-none mt-1">
                                    <div class="rpt-progress" style="height:6px; margin-bottom:.25rem;">
                                        <div class="rpt-progress-bar <?= $progColor ?>" style="width:<?= $progress ?>%;"></div>
                                    </div>
                                    <small class="text-muted">Anggaran: <?= rupiah($budget) ?> &bull; Progress: <?= $progress ?>%</small>
                                </div>
                            </td>
                            <td class="text-end fw-semibold d-none d-md-table-cell"><?= rupiah($budget) ?></td>
                            <td class="text-end fw-semibold text-danger"><?= rupiah($real_out) ?></td>
                            <td class="d-none d-sm-table-cell">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rpt-progress flex-grow-1">
                                        <div class="rpt-progress-bar <?= $progColor ?>" style="width:<?= $progress ?>%;"></div>
                                    </div>
                                    <span class="fw-bold <?= $progress > 80 ? 'text-danger' : 'text-success' ?>" style="min-width:36px;font-size:.8rem;"><?= $progress ?>%</span>
                                </div>
                            </td>
                            <td class="text-end fw-bold <?= $sisa >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= rupiah(abs($sisa)) ?>
                                <?php if ($sisa < 0): ?>
                                    <br><span class="badge text-bg-danger" style="font-size:.65rem;">OVER</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-end">GRAND TOTAL</td>
                            <td class="text-end d-none d-md-table-cell" id="total-budget"></td>
                            <td class="text-end text-danger" id="total-out"></td>
                            <td class="d-none d-sm-table-cell text-center">-</td>
                            <td class="text-end text-success" id="total-sisa"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const php_data = <?= json_encode(array_map(function($item) {
        return [
            'budget'   => (float)$item['kegiatan']['jumlah_anggaran'],
            'real_out' => (float)$item['pengeluaran'],
            'sisa'     => (float)$item['kegiatan']['jumlah_anggaran'] - (float)$item['pengeluaran'],
            'progress' => $item['kegiatan']['jumlah_anggaran'] > 0 ? min(100, round(($item['pengeluaran'] / $item['kegiatan']['jumlah_anggaran']) * 100)) : 0
        ];
    }, $data ?? [])) ?>;

    let totalBudget = 0, totalOut = 0, totalSisa = 0, progressSum = 0, onTrack = 0, overBudget = 0;
    php_data.forEach(d => {
        totalBudget += d.budget;
        totalOut    += d.real_out;
        totalSisa   += d.sisa;
        progressSum += d.progress;
        if (d.progress < 80) onTrack++;
        if (d.sisa < 0) overBudget++;
    });
    const count = php_data.length || 1;
    const fmt = v => 'Rp ' + Math.round(Math.abs(v)).toLocaleString('id-ID');
    const el = id => document.getElementById(id);
    if (el('total-budget')) el('total-budget').textContent = fmt(totalBudget);
    if (el('total-out'))    el('total-out').textContent    = fmt(totalOut);
    if (el('total-sisa'))   el('total-sisa').textContent   = fmt(totalSisa);
    if (el('avg-progress')) el('avg-progress').textContent = Math.round(progressSum / count) + '%';
    if (el('on-track'))     el('on-track').textContent     = onTrack;
    if (el('over-budget'))  el('over-budget').textContent  = overBudget;
});
</script>