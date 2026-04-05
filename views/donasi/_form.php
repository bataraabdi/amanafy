<?php
$record = $record ?? [];
$targetValue = (string)($record['target_nominal'] ?? '');
if ($targetValue !== '' && is_numeric($targetValue)) {
    $targetValue = formatNumber((float)$targetValue);
}

$deskripsiHtml = (string)($record['deskripsi_lengkap'] ?? '');
$dokumentasiFiles = decodeJsonArray($record['dokumentasi_files'] ?? null);
$shareUrl = (string)($record['share_url'] ?? (!empty($record['slug']) ? donasiPublicUrl($record) : ''));
$instanceId = !empty($record['id']) ? (string)$record['id'] : 'new';
$shareFieldId = 'share-url-' . $instanceId;
$docsPreviewId = 'docs-preview-' . $instanceId;
$flyerPreviewId = 'flyer-preview-' . $instanceId;
$qrisPreviewId = 'qris-preview-' . $instanceId;
?>

<div class="card-custom donasi-form-card">
    <div class="card-body-custom">
        <form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data" class="donasi-form-layout">
            <?= CSRF::tokenField() ?>

            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="donasi-form-section">
                        <div class="donasi-section-heading">
                            <div>
                                <h6>Informasi Program</h6>
                                <p>Data utama yang tampil di admin dan halaman publik program.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label-custom">Judul Program <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="nama_donasi"
                                    class="form-control"
                                    value="<?= e($record['nama_donasi'] ?? '') ?>"
                                    placeholder="Contoh: Renovasi Area Wudhu Masjid"
                                    data-slug-source
                                    required
                                >
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-custom">Target Dana <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="target_nominal"
                                    class="form-control input-rupiah"
                                    value="<?= e($targetValue) ?>"
                                    placeholder="0"
                                    required
                                >
                            </div>
                            <div class="col-md-8">
                                <label class="form-label-custom">URL Link Publik</label>
                                <input
                                    type="text"
                                    name="slug"
                                    class="form-control"
                                    value="<?= e($record['slug'] ?? '') ?>"
                                    placeholder="renovasi-area-wudhu"
                                    data-slug-target
                                >
                                <small class="text-muted">Boleh diubah admin. Link publik akan mengikuti slug ini.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Status Publikasi</label>
                                <select name="status" class="form-select">
                                    <option value="aktif" <?= ($record['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif / Bisa diakses</option>
                                    <option value="nonaktif" <?= ($record['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif / Disembunyikan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Deadline (Opsional)</label>
                                <input type="date" name="deadline" class="form-control" value="<?= e($record['deadline'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Nomor Kontak <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="nomor_kontak"
                                    class="form-control"
                                    value="<?= e($record['nomor_kontak'] ?? '') ?>"
                                    placeholder="0812xxxxxxx"
                                    required
                                >
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">Lokasi Kegiatan <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="lokasi_kegiatan"
                                    class="form-control"
                                    value="<?= e($record['lokasi_kegiatan'] ?? '') ?>"
                                    placeholder="Alamat lengkap atau titik kegiatan"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="donasi-form-section">
                        <div class="donasi-section-heading">
                            <div>
                                <h6>Deskripsi Lengkap</h6>
                                <p>Gunakan editor ini untuk menulis kebutuhan program, tujuan, dan ajakan donasi.</p>
                            </div>
                        </div>

                        <label class="form-label-custom">Deskripsi Lengkap (Note Editor) <span class="text-danger">*</span></label>
                        <div class="note-editor" data-note-editor>
                            <div class="note-editor-toolbar">
                                <button type="button" class="note-editor-btn" data-command="bold" title="Bold"><i class="bi bi-type-bold"></i></button>
                                <button type="button" class="note-editor-btn" data-command="italic" title="Italic"><i class="bi bi-type-italic"></i></button>
                                <button type="button" class="note-editor-btn" data-command="underline" title="Underline"><i class="bi bi-type-underline"></i></button>
                                <button type="button" class="note-editor-btn" data-command="insertUnorderedList" title="Bullet List"><i class="bi bi-list-ul"></i></button>
                                <button type="button" class="note-editor-btn" data-command="insertOrderedList" title="Numbered List"><i class="bi bi-list-ol"></i></button>
                                <button type="button" class="note-editor-btn" data-command="formatBlock" data-value="h3" title="Heading"><i class="bi bi-type-h3"></i></button>
                                <button type="button" class="note-editor-btn" data-command="blockquote" title="Quote"><i class="bi bi-blockquote-left"></i></button>
                                <button type="button" class="note-editor-btn" data-command="createLink" title="Link"><i class="bi bi-link-45deg"></i></button>
                                <button type="button" class="note-editor-btn" data-command="removeFormat" title="Clear format"><i class="bi bi-eraser"></i></button>
                            </div>
                            <div
                                class="note-editor-surface"
                                contenteditable="true"
                                data-note-surface
                                data-placeholder="Tulis deskripsi lengkap program donasi di sini..."
                            ><?= $deskripsiHtml ?></div>
                            <textarea name="deskripsi_lengkap" class="d-none" data-note-input><?= e($deskripsiHtml) ?></textarea>
                        </div>
                    </div>

                    <div class="donasi-form-section">
                        <div class="donasi-section-heading">
                            <div>
                                <h6>Dokumentasi & Flyer</h6>
                                <p>Unggah aset visual yang mendukung publikasi program.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label-custom">Upload Foto/Video Dokumentasi</label>
                                <label class="upload-zone donasi-upload-zone">
                                    <input
                                        type="file"
                                        name="dokumentasi_files[]"
                                        class="d-none"
                                        accept="image/*,video/*"
                                        multiple
                                        onchange="showSelectedFiles(this, '<?= $docsPreviewId ?>')"
                                    >
                                    <i class="bi bi-images"></i>
                                    <div class="fw-semibold">Pilih beberapa foto atau video</div>
                                    <small class="text-muted d-block mt-1">Format gambar/video umum, maksimal 10MB per file.</small>
                                </label>
                                <div id="<?= $docsPreviewId ?>" class="selected-file-list"></div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label-custom">Upload Flyer</label>
                                <label class="upload-zone donasi-upload-zone">
                                    <input
                                        type="file"
                                        name="flyer_file"
                                        class="d-none"
                                        accept="image/*,.pdf"
                                        onchange="showSelectedFiles(this, '<?= $flyerPreviewId ?>')"
                                    >
                                    <i class="bi bi-file-earmark-image"></i>
                                    <div class="fw-semibold">Pilih flyer program</div>
                                    <small class="text-muted d-block mt-1">Bisa berupa gambar atau PDF.</small>
                                </label>
                                <div id="<?= $flyerPreviewId ?>" class="selected-file-list"></div>
                                <?php if (!empty($record['flyer_file'])): ?>
                                    <a href="<?= BASE_URL ?>/uploads/donasi/<?= e($record['flyer_file']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                        <i class="bi bi-eye"></i> Lihat Flyer Saat Ini
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($dokumentasiFiles)): ?>
                            <div class="donasi-existing-media">
                                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                    <h6 class="mb-0">Dokumentasi Saat Ini</h6>
                                    <small class="text-muted">Centang media yang ingin dihapus saat menyimpan perubahan.</small>
                                </div>
                                <div class="row g-3">
                                    <?php foreach ($dokumentasiFiles as $file): ?>
                                        <div class="col-md-6">
                                            <label class="donasi-media-card">
                                                <input type="checkbox" name="hapus_dokumentasi[]" value="<?= e($file) ?>" class="form-check-input donasi-media-check">
                                                <div class="donasi-media-thumb">
                                                    <?php if (isImageFile($file)): ?>
                                                        <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>" alt="<?= e($file) ?>">
                                                    <?php elseif (isVideoFile($file)): ?>
                                                        <div class="donasi-media-icon"><i class="bi bi-film"></i></div>
                                                    <?php else: ?>
                                                        <div class="donasi-media-icon"><i class="bi bi-file-earmark"></i></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="donasi-media-body">
                                                    <div class="fw-semibold text-truncate"><?= e($file) ?></div>
                                                    <small class="text-muted"><?= isVideoFile($file) ? 'Video dokumentasi' : 'Media dokumentasi' ?></small>
                                                </div>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="donasi-form-section donasi-sidebar-card">
                        <div class="donasi-section-heading">
                            <div>
                                <h6>Link Share</h6>
                                <p>Gunakan link ini untuk publikasi program.</p>
                            </div>
                        </div>

                        <label class="form-label-custom">Preview URL Publik</label>
                        <div class="share-link-panel">
                            <input type="text" id="<?= $shareFieldId ?>" class="form-control" value="<?= e($shareUrl) ?>" readonly data-slug-preview data-public-base="<?= e(BASE_URL . '/publik/donasi/') ?>">
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyFieldValue('<?= $shareFieldId ?>')">
                                    <i class="bi bi-copy"></i> Copy Link
                                </button>
                                <?php if ($shareUrl !== ''): ?>
                                    <a href="<?= e($shareUrl) ?>" target="_blank" class="btn btn-sm btn-primary-custom">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka
                                    </a>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted d-block mt-2">Jika slug diubah, link share juga ikut berubah.</small>
                        </div>
                    </div>

                    <div class="donasi-form-section donasi-sidebar-card">
                        <div class="donasi-section-heading">
                            <div>
                                <h6>Informasi Rekening</h6>
                                <p>Data transfer utama untuk donatur.</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-custom">Bank <span class="text-danger">*</span></label>
                                <input type="text" name="bank_nama" class="form-control" value="<?= e($record['bank_nama'] ?? '') ?>" placeholder="Bank Syariah Indonesia" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">No. Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="no_rekening" class="form-control" value="<?= e($record['no_rekening'] ?? '') ?>" placeholder="1234567890" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">Atas Nama <span class="text-danger">*</span></label>
                                <input type="text" name="atas_nama_rekening" class="form-control" value="<?= e($record['atas_nama_rekening'] ?? '') ?>" placeholder="Yayasan / DKM / Masjid" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">QRIS</label>
                                <label class="upload-zone donasi-upload-zone compact">
                                    <input
                                        type="file"
                                        name="qris_file"
                                        class="d-none"
                                        accept="image/*"
                                        onchange="showSelectedFiles(this, '<?= $qrisPreviewId ?>')"
                                    >
                                    <i class="bi bi-qr-code"></i>
                                    <div class="fw-semibold">Unggah gambar QRIS</div>
                                </label>
                                <div id="<?= $qrisPreviewId ?>" class="selected-file-list"></div>
                                <?php if (!empty($record['qris_file'])): ?>
                                    <a href="<?= BASE_URL ?>/uploads/qris/<?= e($record['qris_file']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                                        <i class="bi bi-eye"></i> Lihat QRIS Saat Ini
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="donasi-form-actions">
                        <button type="submit" class="btn btn-primary-custom btn-lg w-100 justify-content-center">
                            <i class="bi <?= e($submitIcon ?? 'bi-check-lg') ?>"></i> <?= e($submitLabel ?? 'Simpan') ?>
                        </button>
                        <a href="<?= BASE_URL ?>/donasi" class="btn btn-outline-secondary w-100">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
