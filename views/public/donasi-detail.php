<?php
/**
 * Public Donation Detail
 */
$namaApp = $settings['nama_masjid'] ?? 'Kas Masjid';
$alamat = $settings['alamat'] ?? '';
$progress = $record['target_nominal'] > 0
    ? min(100, round(($record['total_pemasukan'] / $record['target_nominal']) * 100))
    : 0;
$deadlineLabel = !empty($record['deadline']) ? formatDate($record['deadline']) : 'Tanpa deadline';
$plainDescription = htmlToPlainText($record['deskripsi_lengkap'] ?? ($record['uraian'] ?? ''));
$contactDigits = preg_replace('/\D+/', '', (string)($record['nomor_kontak'] ?? ''));
$waNumber = $contactDigits;
if ($waNumber !== '' && strpos($waNumber, '0') === 0) {
    $waNumber = '62' . substr($waNumber, 1);
}
$waUrl = $waNumber !== '' ? 'https://wa.me/' . $waNumber : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(truncate($plainDescription !== '' ? $plainDescription : $record['nama_donasi'], 150)) ?>">
    <title><?= e($record['nama_donasi']) ?> - <?= e($namaApp) ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background:
                radial-gradient(circle at top right, rgba(197,160,40,0.16), transparent 32%),
                linear-gradient(180deg, #f5f9ff 0%, #eef4ff 100%);
        }
        .public-donasi-shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 32px 18px 48px;
        }
        .public-donasi-hero {
            background: linear-gradient(135deg, rgba(13,79,122,0.96), rgba(68,172,255,0.92));
            color: #fff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(32, 112, 182, 0.2);
        }
        .public-donasi-cover {
            min-height: 100%;
            background: rgba(255,255,255,0.08);
        }
        .public-donasi-cover img {
            width: 100%;
            height: 100%;
            min-height: 320px;
            object-fit: cover;
        }
        .public-donasi-placeholder {
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            color: rgba(255,255,255,0.45);
        }
        .public-donasi-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 24px;
        }
        .public-donasi-meta-item {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 18px;
            padding: 14px 16px;
        }
        .public-donasi-meta-item span {
            display: block;
            font-size: 0.78rem;
            opacity: 0.72;
            margin-bottom: 4px;
        }
        .public-donasi-meta-item strong {
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .public-donasi-body {
            margin-top: 28px;
        }
        .public-donasi-richtext p:last-child {
            margin-bottom: 0;
        }
        .public-donasi-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }
        .public-donasi-gallery a,
        .public-donasi-gallery .media-card {
            display: block;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: var(--shadow-sm);
        }
        .public-donasi-gallery img,
        .public-donasi-gallery video {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }
        .public-donasi-gallery .media-card span {
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--gray-500);
        }
        .public-donasi-side-card {
            position: sticky;
            top: 24px;
        }
        @media (max-width: 991px) {
            .public-donasi-side-card {
                position: static;
            }
            .public-donasi-cover img,
            .public-donasi-placeholder {
                min-height: 240px;
            }
        }
    </style>
</head>
<body>
    <div class="public-donasi-shell">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <a href="<?= BASE_URL ?>/publik" class="small text-muted text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard Publik</a>
                <div class="mt-1 text-muted small"><?= e($namaApp) ?><?php if ($alamat !== ''): ?> • <?= e($alamat) ?><?php endif; ?></div>
            </div>
            <button type="button" class="btn btn-outline-secondary" onclick="copyShareLink()">
                <i class="bi bi-share"></i> Bagikan Link
            </button>
        </div>

        <section class="public-donasi-hero">
            <div class="row g-0">
                <div class="col-lg-5">
                    <div class="public-donasi-cover">
                        <?php
                            $displayImage = $record['gambar'] ?? '';
                            if (empty($displayImage) && !empty($record['flyer_file']) && isImageFile($record['flyer_file'])) {
                                $displayImage = $record['flyer_file'];
                            }
                        ?>
                        <?php if (!empty($displayImage)): ?>
                            <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($displayImage) ?>" alt="<?= e($record['nama_donasi']) ?>">
                        <?php else: ?>
                            <div class="public-donasi-placeholder">
                                <i class="bi bi-heart"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="p-4 p-lg-5">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <span class="badge-status badge-aktif">Program Donasi Aktif</span>
                            <span class="badge-status" style="background: rgba(255,255,255,0.12); color: #fff;">Target <?= rupiah((float)$record['target_nominal']) ?></span>
                        </div>
                        <h1 class="mb-3" style="font-size: clamp(1.9rem, 4vw, 3rem); font-weight: 800; line-height: 1.15;"><?= e($record['nama_donasi']) ?></h1>
                        <p class="mb-0" style="opacity: .85; font-size: 1rem; line-height: 1.8;"><?= $plainDescription !== '' ? e(truncate($plainDescription, 190)) : 'Mari ikut berkontribusi untuk mendukung program ini.' ?></p>

                        <div class="public-donasi-meta">
                            <div class="public-donasi-meta-item">
                                <span>Terkumpul</span>
                                <strong><?= rupiah((float)$record['total_pemasukan']) ?></strong>
                            </div>
                            <div class="public-donasi-meta-item">
                                <span>Progress</span>
                                <strong><?= $progress ?>%</strong>
                            </div>
                            <div class="public-donasi-meta-item">
                                <span>Deadline</span>
                                <strong><?= e($deadlineLabel) ?></strong>
                            </div>
                            <div class="public-donasi-meta-item">
                                <span>Lokasi</span>
                                <strong><?= e($record['lokasi_kegiatan'] ?? '-') ?></strong>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-white-50">Progres pengumpulan dana</small>
                                <small class="fw-semibold"><?= rupiah(max(0, ((float)$record['target_nominal']) - ((float)$record['total_pemasukan']))) ?> lagi</small>
                            </div>
                            <div class="progress-custom" style="background: rgba(255,255,255,0.18);">
                                <div class="progress-bar-custom" style="width: <?= $progress ?>%; background: linear-gradient(90deg, #f8d36a, #f7ebc1);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="public-donasi-body">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-custom mb-4">
                        <div class="card-header-custom">
                            <h6><i class="bi bi-card-text me-2"></i>Deskripsi Program</h6>
                        </div>
                        <div class="card-body-custom">
                            <?php if (!empty($record['deskripsi_lengkap'])): ?>
                                <div class="public-donasi-richtext donasi-richtext">
                                    <?= $record['deskripsi_lengkap'] ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Deskripsi program belum tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($dokumentasiFiles)): ?>
                        <div class="card-custom">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-images me-2"></i>Dokumentasi Program</h6>
                            </div>
                            <div class="card-body-custom">
                                <div class="public-donasi-gallery">
                                    <?php foreach ($dokumentasiFiles as $file): ?>
                                        <?php if (isImageFile($file)): ?>
                                            <a href="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>" target="_blank">
                                                <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>" alt="<?= e($record['nama_donasi']) ?>">
                                            </a>
                                        <?php elseif (isVideoFile($file)): ?>
                                            <div class="media-card">
                                                <video controls preload="metadata">
                                                    <source src="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>">
                                                </video>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>/uploads/donasi/<?= e($file) ?>" target="_blank" class="media-card">
                                                <span><i class="bi bi-file-earmark"></i></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4">
                    <div class="public-donasi-side-card">
                        <div class="card-custom mb-4">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-bank me-2"></i>Salurkan Donasi</h6>
                            </div>
                            <div class="card-body-custom">
                                <div class="donasi-bank-card">
                                    <div class="donasi-bank-row"><span>Bank</span><strong><?= e($record['bank_nama'] ?? '-') ?></strong></div>
                                    <div class="donasi-bank-row"><span>No. Rekening</span><strong><?= e($record['no_rekening'] ?? '-') ?></strong></div>
                                    <div class="donasi-bank-row"><span>Atas Nama</span><strong><?= e($record['atas_nama_rekening'] ?? '-') ?></strong></div>
                                </div>

                                <?php if (!empty($record['qris_file'])): ?>
                                    <div class="text-center mt-4">
                                        <img src="<?= BASE_URL ?>/uploads/qris/<?= e($record['qris_file']) ?>" alt="QRIS" class="img-fluid rounded-4 border" style="max-height: 260px;">
                                        <div class="mt-2">
                                            <a href="<?= BASE_URL ?>/uploads/qris/<?= e($record['qris_file']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-download"></i> Buka QRIS
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($record['flyer_file'])): ?>
                                    <div class="mt-4">
                                        <a href="<?= BASE_URL ?>/uploads/donasi/<?= e($record['flyer_file']) ?>" target="_blank" class="btn btn-outline-secondary w-100">
                                            <i class="bi bi-file-earmark-image"></i> Lihat Flyer Program
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-custom">
                            <div class="card-header-custom">
                                <h6><i class="bi bi-person-lines-fill me-2"></i>Hubungi Admin</h6>
                            </div>
                            <div class="card-body-custom">
                                <p class="text-muted mb-3">Butuh konfirmasi donasi atau ingin bertanya lebih lanjut? Hubungi kontak program berikut.</p>
                                <div class="donasi-info-grid">
                                    <div class="donasi-info-item">
                                        <span class="donasi-info-label">Nomor Kontak</span>
                                        <strong><?= e($record['nomor_kontak'] ?? '-') ?></strong>
                                    </div>
                                    <div class="donasi-info-item">
                                        <span class="donasi-info-label">Lokasi</span>
                                        <strong><?= e($record['lokasi_kegiatan'] ?? '-') ?></strong>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 mt-3">
                                    <?php if (!empty($record['nomor_kontak'])): ?>
                                        <a href="tel:<?= e($record['nomor_kontak']) ?>" class="btn btn-outline-secondary">
                                            <i class="bi bi-telephone"></i> Telepon
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($waUrl !== ''): ?>
                                        <a href="<?= e($waUrl) ?>" target="_blank" class="btn btn-primary-custom justify-content-center">
                                            <i class="bi bi-whatsapp"></i> WhatsApp Admin
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyShareLink() {
            const shareUrl = <?= json_encode($shareUrl) ?>;
            if (!shareUrl) {
                return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(shareUrl).then(function () {
                    alert('Link berhasil disalin.');
                });
                return;
            }

            window.prompt('Salin link berikut:', shareUrl);
        }
    </script>
</body>
</html>
