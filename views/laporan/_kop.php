<div class="report-kop d-none d-print-block mb-4 border-bottom border-dark pb-3 text-center">
    <?php $namaMasjid = $settings['nama_masjid'] ?? APP_NAME; ?>
    <?php $alamat = $settings['alamat'] ?? ''; ?>
    <?php $telp = $settings['no_telepon'] ?? ''; ?>
    
    <h2 class="fw-bold mb-1" style="font-family: 'Times New Roman', Times, serif; color: #000;"><?= e(strtoupper($namaMasjid)) ?></h2>
    <p class="mb-0 text-dark" style="font-size: 14px;"><?= e($alamat) ?></p>
    <?php if(!empty($telp)): ?>
    <p class="mb-0 text-dark" style="font-size: 14px;">Telp/WA: <?= e($telp) ?></p>
    <?php endif; ?>
</div>

<style>
@media print {
    @page { size: A4 portrait; margin: 15mm; }
    body { background: #fff !important; margin: 0; padding: 0; font-family: Arial, sans-serif; color: #000 !important; }
    .main-content, .content-wrapper { margin: 0 !important; padding: 0 !important; width: 100% !important; background: transparent !important; }
    .sidebar, .top-header, .btn, .d-print-none, .report-actions, form.filter-form { display: none !important; }
    .card, .card-custom, .card-header, .card-body { border: none !important; box-shadow: none !important; background: transparent !important; padding: 0 !important; margin-bottom: 20px;}
    h4, h5, h6 { color: #000 !important; margin-bottom: 20px !important; text-align: center; }
    table { width: 100% !important; border-collapse: collapse !important; margin-bottom: 20px !important; }
    table, th, td { border: 1px solid #000 !important; background: transparent !important; color: #000 !important; }
    th { padding: 8px !important; background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    td { padding: 6px !important; }
    .badge { border: 1px solid #000; background: transparent !important; color: #000 !important; }
    .text-success, .text-danger, .text-primary, .text-info { color: #000 !important; font-weight: bold; font-family: monospace; }
    .d-none.d-print-block { display: block !important; }
    .d-none.d-print-flex { display: flex !important; }
}
/* Also styling for html2canvas to look somewhat similar to print without stripping everything */
.pdf-exporting .sidebar, .pdf-exporting .top-header, .pdf-exporting .report-actions, .pdf-exporting .filter-form { display: none !important; }
.pdf-exporting .main-content { margin: 0 !important; padding: 20px !important; background: #fff !important; min-height: 100vh; }
.pdf-exporting .report-kop { display: block !important; }
</style>
