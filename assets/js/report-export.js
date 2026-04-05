/**
 * Amanafy - Laporan Export Utilities
 * Digunakan oleh semua halaman laporan untuk export PDF dan Excel
 */

function exportReport(type) {
    const content = document.getElementById('report-content');
    if (!content) {
        alert('Konten laporan tidak ditemukan.');
        return;
    }

    const filename = 'laporan-' + new Date().toISOString().slice(0, 10);

    if (type === 'excel') {
        if (typeof XLSX === 'undefined') {
            alert('Library Excel belum dimuat. Coba refresh halaman.');
            return;
        }
        const tables = document.querySelectorAll('#report-content table, .report-page table');
        if (!tables.length) {
            alert('Tidak ada data tabel untuk diekspor!');
            return;
        }
        const wb = XLSX.utils.book_new();
        tables.forEach(function(t, i) {
            var ws = XLSX.utils.table_to_sheet(t);
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet' + (i + 1));
        });
        XLSX.writeFile(wb, filename + '.xlsx');

    } else if (type === 'pdf') {
        if (typeof html2pdf === 'undefined') {
            alert('Library PDF belum dimuat. Coba refresh halaman.');
            return;
        }

        // Hide action buttons during export
        var hiddenEls = [];
        document.querySelectorAll('.rpt-actions, .no-print').forEach(function(el) {
            if (el.style.display !== 'none') {
                hiddenEls.push(el);
                el.style.display = 'none';
            }
        });

        // Show signature block if present
        var ttd = document.querySelector('.report-ttd');
        if (ttd) ttd.classList.remove('d-none', 'd-print-block');

        var opt = {
            margin:      [10, 10, 10, 10],
            filename:    filename + '.pdf',
            image:       { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, allowTaint: true },
            jsPDF:       { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Use the full report-page if available, otherwise fall back to report-content
        var target = document.querySelector('.report-page') || content;

        html2pdf().set(opt).from(target).save().then(function() {
            // Restore hidden elements
            hiddenEls.forEach(function(el) { el.style.display = ''; });
            // Re-hide signature block from screen
            if (ttd) {
                ttd.classList.add('d-none', 'd-print-block');
            }
        });
    }
}

// Alias so per-report files that call exportRpt() also work
function exportRpt(type) {
    exportReport(type);
}
