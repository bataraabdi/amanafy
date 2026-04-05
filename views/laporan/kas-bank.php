<?php $settings = $settings ?? []; ?>
<?php include BASE_PATH . '/views/laporan/_report_styles.php'; ?>

<div class="report-page" id="report-content">
    <?php include BASE_PATH . '/views/laporan/_kop.php'; ?>

    <div class="report-header-bar">
        <h5><i class="bi bi-bank text-primary me-2"></i>Laporan Kas Bank</h5>
        <span class="period-chip"><i class="bi bi-calendar3"></i> s.d. <?= e($endDate ?? date('Y-m-d')) ?></span>
    </div>

    <div class="rpt-actions no-print">
        <button onclick="exportRpt('excel')" class="rpt-btn excel"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        <button onclick="exportRpt('pdf')"   class="rpt-btn pdf"  ><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        <button onclick="window.print()"     class="rpt-btn print"><i class="bi bi-printer"></i> Print</button>
    </div>

    <?php if (empty($accounts) && empty($postings)): ?>
        <div class="rpt-empty"><i class="bi bi-inbox"></i><p>Belum ada akun bank atau posting bulanan.</p></div>
    <?php else: ?>
        <?php $grand_total = 0; ?>
        <div class="row g-3 mb-4">
            <?php foreach ($accounts as $id => $acc):
                $total = (float)$acc['account']['saldo_saat_ini'];
                $grand_total += $total;
            ?>
            <div class="col-12 col-md-6">
                <div class="rpt-card h-100" style="overflow:hidden;">
                    <!-- Bank Card Header (gradient) -->
                    <div style="background: linear-gradient(135deg,#1e3c72,#2a5298); padding: 1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;">
                        <div>
                            <div class="text-white fw-bold"><?= e($acc['account']['nama_bank']) ?></div>
                            <div style="color:rgba(255,255,255,.7);font-size:.8rem;"><i class="bi bi-credit-card-2-front me-1"></i><?= e($acc['account']['nomor_rekening']) ?></div>
                        </div>
                        <span class="badge <?= $acc['account']['is_active'] ? 'bg-success' : 'bg-secondary' ?>"><?= $acc['account']['is_active'] ? 'Aktif' : 'Nonaktif' ?></span>
                    </div>
                    <div class="rpt-card-body">
                        <!-- Saldo + Pemilik -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="rpt-stat blue" style="padding:.75rem 1rem;">
                                    <label>Saldo Saat Ini</label>
                                    <h3><?= rupiah($total) ?></h3>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rpt-stat slate" style="padding:.75rem 1rem;">
                                    <label>Atas Nama</label>
                                    <h3 style="font-size:.95rem; margin-bottom:0;"><?= e($acc['account']['nama_pemilik']) ?></h3>
                                </div>
                            </div>
                        </div>

                        <!-- Mutasi terbaru -->
                        <?php if (!empty($acc['mutations'])): ?>
                        <p class="text-muted fw-bold mb-2" style="font-size:.75rem;letter-spacing:.5px;text-transform:uppercase;">Mutasi Terbaru</p>
                        <div class="table-responsive">
                            <table class="rpt-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Referensi</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($acc['mutations'], 0, 5) as $mut): ?>
                                    <tr>
                                        <td><?= formatDate($mut['tanggal']) ?></td>
                                        <td>
                                            <span class="badge <?= $mut['entry_type'] == 'debet' ? 'text-bg-success' : 'text-bg-danger' ?>" style="font-size:.68rem;"><?= $mut['entry_type'] == 'debet' ? 'D' : 'K' ?></span>
                                            <?= e(str_replace('_',' ',$mut['reference_table'])) ?>
                                        </td>
                                        <td class="text-end fw-semibold <?= $mut['entry_type'] == 'debet' ? 'text-success' : 'text-danger' ?>">
                                            <?= $mut['entry_type'] == 'debet' ? '+' : '-' ?><?= rupiah((float)$mut['amount']) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <!-- Posting bulanan -->
                        <?php if (!empty($acc['monthly_postings'])): ?>
                        <p class="text-muted fw-bold mb-2 mt-3" style="font-size:.75rem;letter-spacing:.5px;text-transform:uppercase;">Posting Bulanan</p>
                        <div class="table-responsive">
                            <table class="rpt-table">
                                <thead><tr><th>Periode</th><th>Tipe</th><th class="text-end">Jumlah</th></tr></thead>
                                <tbody>
                                    <?php foreach (array_slice($acc['monthly_postings'], 0, 4) as $post): ?>
                                    <tr>
                                        <td><?= e($post['periode_bulan']) ?></td>
                                        <td><span class="badge <?= $post['posting_type'] == 'bank_admin_fee' ? 'text-bg-danger' : 'text-bg-success' ?>"><?= $post['posting_type'] == 'bank_admin_fee' ? 'Biaya Admin' : 'Jasa Giro' ?></span></td>
                                        <td class="text-end fw-semibold <?= $post['posting_type'] == 'bank_admin_fee' ? 'text-danger' : 'text-success' ?>">
                                            <?= $post['posting_type'] == 'bank_admin_fee' ? '-' : '+' ?><?= rupiah((float)$post['amount']) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>

                        <?php if (empty($acc['mutations']) && empty($acc['monthly_postings'])): ?>
                            <p class="text-center text-muted small">Tidak ada riwayat mutasi / posting.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Grand Total Bar with teal gradient -->
        <div style="background: linear-gradient(135deg, #0d9488, #0f766e); color:#fff; border-radius:14px; padding:1.2rem 1.5rem; text-align:center; margin-bottom:1.5rem;">
            <div style="font-size:.7rem;letter-spacing:1px;text-transform:uppercase;opacity:.75;margin-bottom:.25rem;">Total Saldo Rekening Bank (<?= count($accounts) ?> Rekening)</div>
            <div style="font-size:1.8rem;font-weight:800;"><?= rupiah($grand_total) ?></div>
        </div>

        <?php include BASE_PATH . '/views/laporan/_tandatangan.php'; ?>
    <?php endif; ?>
</div>
