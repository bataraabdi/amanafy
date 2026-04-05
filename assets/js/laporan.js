// Laporan Export Utilities (Client-side)
// Dependencies loaded via CDN in views

console.log('Laporan JS loaded');

function exportReport(type) {
    const header = document.getElementById('report-header');
    const content = document.getElementById('report-content');
    const footer = document.getElementById('report-footer');
    
    const fullContent = document.createElement('div');
    fullContent.innerHTML = header.outerHTML + content.outerHTML + footer.outerHTML;
    fullContent.className = 'print-full';
    fullContent.style.position = 'absolute';
    fullContent.style.left = '-9999px';
    document.body.appendChild(fullContent);

    switch(type) {
        case 'excel':
            exportExcel(fullContent);
            break;
        case 'pdf':
            exportPDF(fullContent);
            break;
    }
    
    document.body.removeChild(fullContent);
}

function exportExcel(element) {
    const tables = element.querySelectorAll('table');
    const wb = XLSX.utils.book_new();
    
    tables.forEach((table, index) => {
        const ws = XLSX.utils.table_to_sheet(table);
        XLSX.utils.book_append_sheet(wb, ws, table.closest('.card')?.querySelector('h4,h5,h6')?.textContent.trim().slice(0,31) || `Sheet${index+1}`);
    });
    
    XLSX.writeFile(wb, `Laporan-Keuangan-${new Date().toISOString().slice(0,10)}.xlsx`);
}

async function exportPDF(element) {
    try {
        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            width: window.innerWidth,
            logging: false
        });
        
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        const imgWidth = pdfWidth - 20;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        
        let heightLeft = imgHeight;
        let position = 10;
        
        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pdfHeight;
        
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight + 10;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pdfHeight;
        }
        
        pdf.save(`Laporan-Keuangan-${new Date().toISOString().slice(0,10)}.pdf`);
    } catch (error) {
        console.error('PDF Export Error:', error);
        alert('Export PDF gagal. Coba Print ke PDF.');
    }
}

// Auto-trigger on page load if ?print=1
if (new URLSearchParams(window.location.search).get('print')) {
    setTimeout(() => window.print(), 500);
}

// Enhance tables for mobile
document.querySelectorAll('.table-responsive table').forEach(table => {
    table.querySelectorAll('th, td').forEach((cell, index) => {
        if (window.innerWidth < 768 && index > 0) {
            cell.classList.add('text-nowrap');
        }
    });
});

// Period toggle fallback
document.addEventListener('change', function(e) {
    if (e.target.name === 'periode') {
        document.querySelectorAll('.periode-field').forEach(f => f.style.display = 'none');
        document.querySelector(`.periode-field.${e.target.value}`)?.style.display = 'block';
    }
});
```

