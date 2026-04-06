<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h5 class="mb-1 text-primary fw-bold text-uppercase" style="letter-spacing: 1px;">Laporan Arus Kas</h5>
            <small class="text-muted fw-semibold"><?= formatDate($tanggal_dari) ?> - <?= formatDate($tanggal_sampai) ?></small>
        </div>
        <div class="rpt-actions no-print m-0">
            <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
            <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
            <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-sm mb-0 p-3" style="font-family: Arial, sans-serif; font-size: 0.95rem;">
            <tbody>
                <!-- Pemasukan -->
                <tr>
                    <td colspan="2" class="fw-bold text-primary pt-3" style="font-size: 1.05rem;">Aktivitas Pemasukan</td>
                </tr>
                <?php if(empty($kategoriPemasukan)): ?>
                    <tr>
                        <td class="ps-4 text-muted">Belum ada data pemasukan pada periode ini.</td>
                        <td></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kategoriPemasukan as $kat): ?>
                        <tr>
                            <td class="ps-4" style="width: 70%;"><?= e($kat['nama_kategori']) ?></td>
                            <td class="text-end fw-semibold text-success"><?= rupiah((float)$kat['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td class="ps-4 text-end text-muted small" style="border-bottom:1px solid #dee2e6;">Total Pemasukan :</td>
                    <td class="text-end fw-bold text-success" style="border-bottom:1px solid #dee2e6;"><?= rupiah($total_inflow ?? 0) ?></td>
                </tr>

                <!-- Pengeluaran -->
                <tr>
                    <td colspan="2" class="fw-bold text-danger pt-4" style="font-size: 1.05rem;">Aktivitas Pengeluaran</td>
                </tr>
                <?php if(empty($kategoriPengeluaran)): ?>
                    <tr>
                        <td class="ps-4 text-muted">Belum ada data pengeluaran pada periode ini.</td>
                        <td></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kategoriPengeluaran as $kat): ?>
                        <tr>
                            <td class="ps-4" style="width: 70%;"><?= e($kat['nama_kategori']) ?></td>
                            <td class="text-end fw-semibold text-danger">-<?= rupiah((float)$kat['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr>
                    <td class="ps-4 text-end text-muted small" style="border-bottom:1px solid #dee2e6;">Total Pengeluaran :</td>
                    <td class="text-end fw-bold text-danger" style="border-bottom:1px solid #dee2e6;">-<?= rupiah($total_outflow ?? 0) ?></td>
                </tr>

                <!-- Net Flow & Balances -->
                <tr>
                    <td colspan="2" class="pt-4"></td>
                </tr>
                
                <tr>
                    <td class="fw-bold fs-6">Kenaikan/Penurunan Kas:</td>
                    <td class="text-end fw-bold fs-6 <?= $net_cash >= 0 ? 'text-success' : 'text-danger' ?> border-bottom">
                        <?= rupiah($net_cash) ?>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold text-muted mt-2">Saldo Awal (Per <?= formatDate(date('Y-m-d', strtotime($tanggal_dari . ' -1 day'))) ?>):</td>
                    <td class="text-end fw-bold text-muted border-bottom pt-2">
                        <?= rupiah($saldoAwal) ?>
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold fs-5 text-dark pt-3 pb-4">Saldo Akhir:</td>
                    <td class="text-end fw-bold fs-5 text-primary border-bottom pt-3 border-2 border-dark pb-4" style="border-bottom-style: double !important;">
                        <?= rupiah($saldoAkhir) ?>
                    </td>
                </tr>

                <!-- Kas & Bank Breakdown -->
                <tr>
                    <td colspan="2" class="fw-bold text-dark pt-4" style="font-size: 1.05rem;">Komposisi Kas & Bank (Berdasarkan Mutasi Total)</td>
                </tr>
                <tr>
                    <td class="ps-4 text-muted pt-2 pb-1" style="font-weight: 500;">Kas Tunai</td>
                    <td class="text-end fw-semibold text-dark pt-2 pb-1"><?= rupiah($totalCashPosition ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="ps-4 text-muted py-1" style="font-weight: 500;">Kas Bank</td>
                    <td class="text-end fw-semibold text-dark py-1 border-bottom"><?= rupiah($totalBankPosition ?? 0) ?></td>
                </tr>
                <tr>
                    <td class="ps-4 text-end text-muted small pt-2">Total Kas Aktual Terpisah :</td>
                    <td class="text-end fw-bold text-dark pt-2"><?= rupiah(($totalCashPosition ?? 0) + ($totalBankPosition ?? 0)) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
</div>
