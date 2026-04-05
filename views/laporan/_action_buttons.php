<?php
$title = $title ?? 'laporan_' . date('Ymd');
?>
<div class="report-actions mb-4 d-flex justify-content-end gap-2 d-print-none">
    <button onclick="window.print()" class="btn border border-secondary text-secondary bg-white hover-bg-light px-3 shadow-sm" style="border-radius: 12px; transition: 0.2s;">
        <i class="bi bi-printer me-1"></i> Print / Save PDF
    </button>
    <button onclick="exportToExcel('report-table', '<?= e($title) ?>')" class="btn border border-success text-success bg-white px-3 shadow-sm" style="border-radius: 12px; transition: 0.2s;" onmouseover="this.style.background='#198754'; this.style.color='#fff';" onmouseout="this.style.background='#fff'; this.style.color='#198754';">
        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
    </button>
</div>

<!-- Simple Exporter Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportToExcel(elementId, filename) {
    let container = document.getElementById(elementId);
    if (!container) {
        alert('Tidak ada data yang bisa diexport.');
        return;
    }
    
    // Create a temporary clone to remove print-none or hidden elements if necessary
    let clone = container.cloneNode(true);
    // Remove elements that shouldn't be in excel
    let noPrintElements = clone.querySelectorAll('.d-print-none, .no-print');
    noPrintElements.forEach(el => el.remove());

    // Generate book with all tables found in the clone block
    let wb = XLSX.utils.table_to_book(clone, { sheet: "Laporan" });
    XLSX.writeFile(wb, filename + '.xlsx');
}
</script>
