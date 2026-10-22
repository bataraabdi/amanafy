/**
 * App.js - Main Application JavaScript
 */

// ============================================
// SIDEBAR TOGGLE
// ============================================
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.remove('show');
            this.classList.remove('show');
        });
    }
});

// ============================================
// SWEETALERT HELPERS
// ============================================

// Helper function for confirm delete modal
function confirmDelete(url, title, text) {
    Swal.fire({


        title: title || 'Apakah Anda yakin?',
        text: text || "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,



        confirmColor: '#dc3545',
        cancelColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',








        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            window.location.href = url;
        }
    });
}

function showAlert(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    Toast.fire({ icon: type, title: message });
}

// Helper function for AJAX success notification
function showSuccessToast(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Helper function for AJAX error notification
function showErrorToast(message) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true
    });
}

// ============================================
// FORMAT RUPIAH
// ============================================
function formatRupiah(angka, prefix = 'Rp ') {
    let number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
}

function bindRupiahInput(selector) {
    document.querySelectorAll(selector).forEach(function(el) {
        el.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value, '');
        });
    });
}

function parseRupiah(value) {
    return parseInt(value.toString().replace(/\./g, '').replace(/,/g, '')) || 0;
}

// ============================================
// DATATABLES INIT
// ============================================
function initDataTable(tableId, options = {}) {
    const defaults = {
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(filter dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            paginate: {




                first:      '<i class="fas fa-angle-double-left"></i>',
                last:       '<i class="fas fa-angle-double-right"></i>',
                next:       '<i class="fas fa-angle-right"></i>',
                previous:   '<i class="fas fa-angle-left"></i>'
            }
        },
        responsive: true,
        pageLength: 15,
        order: [[0, 'desc']],
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
    };

    return new DataTable('#' + tableId, { ...defaults, ...options });
}

// Auto initialize tooltips & popovers
$(document).ready(function() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);

    // Global double-click protection and loading feedback on form submit
    $('form').on('submit', function() {
        var $form = $(this);
        // Skip if form validation fails
        if (this.checkValidity && !this.checkValidity()) {
            return true;
        }
        if ($form.data('submitting') === true) {
            return false;
        }

        var $submitBtn = $form.find('button[type="submit"], input[type="submit"]');
        if ($submitBtn.length && !$submitBtn.hasClass('no-spinner')) {
            $form.data('submitting', true);
            $submitBtn.prop('disabled', true);
            var originalText = $submitBtn.html();
            $submitBtn.data('original-html', originalText);
            $submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Memproses...');

            // Safety fallback timeout to re-enable button if page does not redirect/reload
            setTimeout(function() {
                if ($form.data('submitting')) {
                    $form.data('submitting', false);
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            }, 10000);
        }
    });
});

// ============================================
// SHOW FLASH MESSAGES
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const flashSuccess = document.getElementById('flash-success');
    const flashError = document.getElementById('flash-error');

    if (flashSuccess && flashSuccess.value) {
        showAlert('success', flashSuccess.value);
    }
    if (flashError && flashError.value) {
        showAlert('error', flashError.value);
    }
});

// ============================================
// FILE PREVIEW
// ============================================
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function slugifyText(text) {
    return text
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function copyTextValue(value) {
    if (!value) {
        return;
    }

    const onSuccess = () => showAlert('success', 'Link berhasil disalin.');
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(value).then(onSuccess).catch(() => fallbackCopyText(value, onSuccess));
        return;
    }

    fallbackCopyText(value, onSuccess);
}

function fallbackCopyText(value, onSuccess) {
    const input = document.createElement('input');
    input.value = value;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
    onSuccess();
}

function copyFieldValue(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) {
        return;
    }

    copyTextValue(field.value || field.textContent || '');
}

function showSelectedFiles(input, containerId) {
    const container = document.getElementById(containerId);
    if (!container) {
        return;
    }

    const files = Array.from(input.files || []);
    if (files.length === 0) {
        container.innerHTML = '';
        return;
    }

    container.innerHTML = files.map(file => `
        <div class="selected-file-chip">
            <i class="bi bi-paperclip"></i>
            <span>${file.name}</span>
        </div>
    `).join('');
}

function initSlugInputs() {
    document.querySelectorAll('[data-slug-target]').forEach(function(slugInput) {
        const form = slugInput.closest('form');
        const titleInput = form ? form.querySelector('[data-slug-source]') : null;
        const previewInput = form ? form.querySelector('[data-slug-preview]') : null;
        const publicBase = previewInput ? previewInput.dataset.publicBase || '' : '';
        let slugEditedManually = slugInput.value.trim() !== '';

        const updatePreview = function() {
            if (!previewInput) {
                return;
            }

            const slug = slugInput.value.trim();
            previewInput.value = slug ? publicBase + slug : '';
        };

        if (titleInput) {
            titleInput.addEventListener('input', function() {
                if (!slugEditedManually) {
                    slugInput.value = slugifyText(this.value);
                    updatePreview();
                }
            });
        }

        slugInput.addEventListener('input', function() {
            slugEditedManually = this.value.trim() !== '';
            this.value = slugifyText(this.value);
            updatePreview();
        });

        updatePreview();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initSlugInputs();
});
