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
function confirmDelete(formId, message = 'Data yang dihapus tidak dapat dikembalikan!') {
    Swal.fire({
        title: 'Yakin hapus?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c62828',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
    return false;
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
                first: "Pertama",
                last: "Terakhir",
                next: "&raquo;",
                previous: "&laquo;"
            }
        },
        responsive: true,
        pageLength: 15,
        order: [[0, 'desc']],
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip'
    };

    return new DataTable('#' + tableId, { ...defaults, ...options });
}

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
                }
                updatePreview();
            });
        }

        slugInput.addEventListener('input', function() {
            const sanitized = slugifyText(this.value);
            slugEditedManually = sanitized !== '';
            this.value = sanitized;
            updatePreview();
        });

        updatePreview();
    });
}

function initNoteEditors() {
    document.querySelectorAll('[data-note-editor]').forEach(function(editor) {
        const surface = editor.querySelector('[data-note-surface]');
        const input = editor.querySelector('[data-note-input]');
        if (!surface || !input) {
            return;
        }

        const sync = function() {
            input.value = surface.innerHTML.trim();
        };

        editor.querySelectorAll('.note-editor-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const command = this.dataset.command;
                const value = this.dataset.value || null;

                surface.focus();

                if (command === 'createLink') {
                    const url = window.prompt('Masukkan URL:', 'https://');
                    if (url) {
                        document.execCommand('createLink', false, url);
                    }
                } else if (command === 'blockquote') {
                    document.execCommand('formatBlock', false, 'blockquote');
                } else if (command === 'formatBlock') {
                    document.execCommand('formatBlock', false, value || 'p');
                } else {
                    document.execCommand(command, false, value);
                }

                sync();
            });
        });

        surface.addEventListener('input', sync);
        const form = editor.closest('form');
        if (form) {
            form.addEventListener('submit', sync);
        }
    });
}

// ============================================
// NUMBER INPUT HELPER
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.input-rupiah').forEach(function(el) {
        el.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value) {
                this.value = parseInt(value).toLocaleString('id-ID');
            }
        });
    });

    initSlugInputs();
    initNoteEditors();
});

// ============================================
// IDLE SESSION TRACKER
// ============================================
let idleTime = 0;
let lastPingTime = 0;
// 15 minutes (900 seconds) timeout
const maxIdleTime = 15 * 60; 

document.addEventListener('DOMContentLoaded', function() {
    // Only initialized if not on login page 
    if (document.querySelector('.top-header')) {
        setInterval(function() {
            idleTime++;
            if (idleTime >= maxIdleTime) {
                const baseUrl = window.APP_BASE_URL || '';
                window.location.href = baseUrl + '/logout?idle=1';
            }
        }, 1000);

        // Reset idle timer on any user activity and ping server to keep session alive
        const resetTimer = () => { 
            idleTime = 0; 
            const now = Date.now();
            // Ping server at most once per minute (60000 ms) when active
            if (now - lastPingTime > 60000) {
                lastPingTime = now;
                const baseUrl = window.APP_BASE_URL || '';
                fetch(baseUrl + '/auth/ping').catch(()=>false);
            }
        };
        
        // Initialize once to set baseline
        resetTimer();
        
        document.addEventListener('mousemove', resetTimer);
        document.addEventListener('keypress', resetTimer);
        document.addEventListener('keyup', resetTimer);
        document.addEventListener('click', resetTimer);
        document.addEventListener('scroll', resetTimer);
        document.addEventListener('touchstart', resetTimer);
    }
});
