<?php
/**
 * Public Dashboard - Accessible without login
 */
$namaApp = $settings['nama_masjid'] ?? 'Kas Masjid';
$alamat = $settings['alamat'] ?? '';
$bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Laporan Keuangan Transparan <?= e($namaApp) ?>">
    <title>Dashboard Publik - <?= e($namaApp) ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false } // Prevent conflict with Bootstrap
        }
    </script>
    <style>
        :root {
            --primary-bg: #f8fafc;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            --accent-color: #0ea5e9;
        }
        body { background-color: var(--primary-bg); font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1e293b; }
        .public-header {
            background: linear-gradient(135deg, #2388cf 0%, #0d4f7a 100%);
            color: #fff;
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
        }
        .public-header::before {
            content: ""; position: absolute; top: -10%; right: -10%; width: 40%; height: 60%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, transparent 70%);
            border-radius: 50%; blur: 60px;
        }
        .public-header h1 { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 2.75rem; letter-spacing: -0.025em; margin-bottom: 0.5rem; }
        .public-header p { color: #94a3b8; font-size: 1.1rem; }
        
        .public-container { max-width: 1240px; margin: -60px auto 60px; padding: 0 24px; position: relative; z-index: 10; }
        
        .stat-card-v2 {
            background: #fff; border-radius: 20px; padding: 24px; border: 1px solid rgba(255,255,255,0.7);
            box-shadow: var(--card-shadow); transition: transform 0.3s ease;
        }
        .stat-card-v2:hover { transform: translateY(-5px); }
        .stat-card-v2 .icon { 
            width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 16px;
        }
        .stat-card-v2.income .icon { background: #dcfce7; color: #16a34a; }
        .stat-card-v2.expense .icon { background: #fee2e2; color: #dc2626; }
        .stat-card-v2.balance .icon { background: #e0f2fe; color: #0284c7; }
        .stat-card-v2.total .icon { background: #f1f5f9; color: #475569; }
        .stat-card-v2 .label { color: #64748b; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; }
        .stat-card-v2 .value { color: #0f172a; font-size: 1.5rem; font-weight: 700; margin-top: 4px; }

        /* New metric cards */
        .stat-card-pub {
            background: #fff; border-radius: 20px; padding: 22px 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06); transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }
        .stat-card-pub:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.10); }

        .pub-icon {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
        }
        .pub-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 9px; font-weight: 800; letter-spacing: 0.08em;
            text-transform: uppercase; padding: 4px 10px; border-radius: 100px;
        }
        .pub-dot {
            width: 7px; height: 7px; border-radius: 50%; display: inline-block;
            animation: pulse-dot 2s infinite;
        }
        .pub-label {
            color: #94a3b8; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px;
        }
        .pub-value {
            color: #0f172a; font-size: 1.3rem; font-weight: 800;
            font-family: 'Outfit', sans-serif; margin-bottom: 12px;
            word-break: break-word;
        }
        .pub-footer {
            display: flex; align-items: center; gap: 6px;
            padding-top: 10px; border-top: 1px solid #f1f5f9;
            font-size: 10px; color: #94a3b8; font-weight: 600;
        }
        .pub-bar-wrap {
            display: flex; align-items: center; gap: 8px;
            padding-top: 10px; border-top: 1px solid #f1f5f9;
        }
        .pub-bar-track {
            flex: 1; background: #f1f5f9; border-radius: 100px; height: 6px; overflow: hidden;
        }
        .pub-bar-fill {
            height: 100%; border-radius: 100px;
            transition: width 1.2s cubic-bezier(0.4,0,0.2,1);
        }
        .pub-bar-pct {
            font-size: 10px; font-weight: 800; color: #64748b; white-space: nowrap;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(74, 222, 128, 0); }
        }

        /* Mobile adjustments */
        @media (max-width: 576px) {
            .pub-value { font-size: 1.1rem; }
            .pub-icon { width: 38px; height: 38px; font-size: 1rem; }
        }


        .bank-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border-radius: 24px; border: 1px dashed #cbd5e1; padding: 32px;
            display: flex; flex-direction: column; align-items: center; text-align: center;
        }
        .bank-item {
            background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            width: 100%; max-width: 400px; border: 1px solid #e2e8f0; margin-bottom: 16px;
            transition: all 0.2s ease; cursor: pointer;
        }
        .bank-item:hover { border-color: var(--accent-color); box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.1); }
        
        .program-card {
            background: #fff; border-radius: 24px; overflow: hidden; box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9; transition: all 0.3s ease; height: 100%;
        }
        .program-card:hover { transform: scale(1.02); }
        .program-card img { height: 240px; width: 100%; object-fit: cover; }
        
        .section-title { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.75rem; color: #0f172a; margin-bottom: 2rem; display: flex; align-items: center; gap: 12px; }
        .section-title i { color: var(--accent-color); }
        .card-custom { border-radius: 20px; border: none; box-shadow: var(--card-shadow); background: #fff; overflow: hidden; }
    </style>
</head>
<body>
    <div class="public-header text-center">
        <div class="container">
            <?php if (!empty($settings['logo'])): ?>
                <img src="<?= BASE_URL ?>/uploads/logo/<?= e($settings['logo']) ?>" style="width: 90px; height: 90px; object-fit: contain; background: #fff; padding: 8px; border-radius: 24px; margin-bottom: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);" alt="Logo">
            <?php else: ?>
                <div style="font-size:3.5rem; margin-bottom:20px;">🕌</div>
            <?php endif; ?>
            <h1><?= e($namaApp) ?></h1>
            <p><i class="bi bi-geo-alt-fill me-2"></i><?= e($alamat) ?></p>
            <div class="mt-4 inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white/90 text-sm">
                <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
                Data Transparan &bull; Terakhir Diperbarui: <?= formatDate(date('Y-m-d')) ?>
            </div>
        </div>
    </div>

    <div class="public-container">

        <!-- ===== FINANCIAL SUMMARY SECTION ===== -->
        <?php
            $bulanIni = $bulanNames[date('n') - 1];
            $totalSaldo = $totalCash + $totalBank;
            $selisih = $pemasukanBulanIni - $pengeluaranBulanIni;
            $pctK = ($pemasukanBulanIni > 0) ? min(100, round($pengeluaranBulanIni / $pemasukanBulanIni * 100)) : 0;
        ?>

        <!-- HERO: Total Saldo Card -->
        <div style="background: linear-gradient(135deg, #1e3a5f 0%, #2388cf 60%, #0ea5e9 100%); border-radius: 28px; padding: 36px 40px; margin-bottom: 20px; position: relative; overflow: hidden; box-shadow: 0 20px 60px rgba(35,136,207,0.35);">
            <div style="position:absolute;top:-40px;right:-40px;width:220px;height:220px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
            <div style="position:absolute;bottom:-60px;right:80px;width:160px;height:160px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
            <div class="row align-items-center g-4">
                <div class="col-md-7">
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:100px;padding:6px 16px;margin-bottom:16px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#4ade80;display:inline-block;animation:pulse 2s infinite;"></span>
                        <span style="color:rgba(255,255,255,0.9);font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Data Real-Time · <?= formatDate(date('Y-m-d')) ?></span>
                    </div>
                    <p style="color:rgba(255,255,255,0.65);font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;">Total Saldo (Kas Tunai &amp; Bank)</p>
                    <h2 style="color:#fff;font-size:clamp(2rem,5vw,3.2rem);font-weight:900;font-family:'Outfit',sans-serif;letter-spacing:-0.03em;margin-bottom:6px;" id="totalSaldoCounter">
                        <?= rupiah($totalSaldo) ?>
                    </h2>
                    <p style="color:rgba(255,255,255,0.5);font-size:13px;">Konsolidasi seluruh aset kas &amp; rekening bank masjid</p>
                </div>
                <div class="col-md-5">
                    <div class="row g-3">
                        <div class="col-6">
                            <div style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:16px;padding:16px;backdrop-filter:blur(10px);">
                                <i class="bi bi-cash-stack" style="color:#4ade80;font-size:1.3rem;"></i>
                                <p style="color:rgba(255,255,255,0.6);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin:8px 0 4px;">Kas Tunai</p>
                                <p style="color:#fff;font-size:1rem;font-weight:800;margin:0;font-family:'Outfit',sans-serif;"><?= rupiah($totalCash) ?></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:16px;padding:16px;backdrop-filter:blur(10px);">
                                <i class="bi bi-bank" style="color:#60a5fa;font-size:1.3rem;"></i>
                                <p style="color:rgba(255,255,255,0.6);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin:8px 0 4px;">Kas Bank</p>
                                <p style="color:#fff;font-size:1rem;font-weight:800;margin:0;font-family:'Outfit',sans-serif;"><?= rupiah($totalBank) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 METRIC CARDS: Kas Tunai, Kas Bank, Pemasukan, Pengeluaran -->
        <div class="row g-4 mb-5">

            <!-- Saldo Kas Tunai -->
            <div class="col-6 col-lg-3">
                <div class="stat-card-pub h-100" style="border-top: 4px solid #10b981;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                        <div class="pub-icon" style="background:#dcfce7;color:#16a34a;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <span class="pub-badge" style="color:#16a34a;background:#dcfce7;">
                            <span class="pub-dot" style="background:#10b981;"></span>TUNAI
                        </span>
                    </div>
                    <p class="pub-label">Saldo Kas Tunai</p>
                    <h3 class="pub-value"><?= rupiah($totalCash) ?></h3>
                    <div class="pub-footer">
                        <i class="bi bi-wallet2" style="color:#10b981;font-size:11px;"></i>
                        <span>Saldo aktual kas fisik</span>
                    </div>
                </div>
            </div>

            <!-- Saldo Kas Bank -->
            <div class="col-6 col-lg-3">
                <div class="stat-card-pub h-100" style="border-top: 4px solid #2388cf;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                        <div class="pub-icon" style="background:#dbeafe;color:#1d4ed8;">
                            <i class="bi bi-bank"></i>
                        </div>
                        <span class="pub-badge" style="color:#1d4ed8;background:#dbeafe;">
                            <span class="pub-dot" style="background:#2388cf;"></span>BANK
                        </span>
                    </div>
                    <p class="pub-label">Saldo Kas Bank</p>
                    <h3 class="pub-value"><?= rupiah($totalBank) ?></h3>
                    <div class="pub-footer">
                        <i class="bi bi-credit-card" style="color:#2388cf;font-size:11px;"></i>
                        <span>Rekening bank resmi</span>
                    </div>
                </div>
            </div>

            <!-- Pemasukan Bulan Ini -->
            <div class="col-6 col-lg-3">
                <div class="stat-card-pub h-100" style="border-top: 4px solid #0d9488;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                        <div class="pub-icon" style="background:#ccfbf1;color:#0d9488;">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span class="pub-badge" style="color:#0d9488;background:#ccfbf1;">
                            <i class="bi bi-arrow-up-short"></i> MASUK
                        </span>
                    </div>
                    <p class="pub-label">Pemasukan <?= $bulanIni ?></p>
                    <h3 class="pub-value"><?= rupiah($pemasukanBulanIni) ?></h3>
                    <div class="pub-bar-wrap">
                        <?php $pctMasuk = ($totalSaldo > 0) ? min(100, round($pemasukanBulanIni / max($totalSaldo,1) * 100)) : 0; ?>
                        <div class="pub-bar-track">
                            <div class="pub-bar-fill" style="width:<?= $pctMasuk ?>%;background:#0d9488;"></div>
                        </div>
                        <span class="pub-bar-pct"><?= $pctMasuk ?>%</span>
                    </div>
                </div>
            </div>

            <!-- Pengeluaran Bulan Ini -->
            <div class="col-6 col-lg-3">
                <div class="stat-card-pub h-100" style="border-top: 4px solid #ef4444;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                        <div class="pub-icon" style="background:#fee2e2;color:#dc2626;">
                            <i class="bi bi-graph-down-arrow"></i>
                        </div>
                        <span class="pub-badge" style="color:#dc2626;background:#fee2e2;">
                            <i class="bi bi-arrow-down-short"></i> KELUAR
                        </span>
                    </div>
                    <p class="pub-label">Pengeluaran <?= $bulanIni ?></p>
                    <h3 class="pub-value"><?= rupiah($pengeluaranBulanIni) ?></h3>
                    <div class="pub-bar-wrap">
                        <?php $pctKeluar = ($pemasukanBulanIni > 0) ? min(100, round($pengeluaranBulanIni / max($pemasukanBulanIni,1) * 100)) : 0; ?>
                        <div class="pub-bar-track">
                            <div class="pub-bar-fill" style="width:<?= $pctKeluar ?>%;background:#ef4444;"></div>
                        </div>
                        <span class="pub-bar-pct" style="color:<?= $selisih >= 0 ? '#16a34a' : '#dc2626' ?>;">
                            <?= $selisih >= 0 ? '▲ ' : '▼ ' ?><?= rupiah(abs($selisih)) ?>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <!-- ===== END FINANCIAL SUMMARY ===== -->

        <div class="row g-5">
            <!-- Left: Bank Accounts & Chart -->
            <div class="col-lg-5">
                <h2 class="section-title"><i class="bi bi-wallet2"></i>Salurkan Infaq</h2>
                <div class="bank-card shadow-sm mb-5">
                    <div class="mb-4">
                        <h6 class="fw-bold text-slate-800 mb-1">Rekening Infaq Resmi</h6>
                        <p class="text-slate-500 text-sm">Salurkan infaq/shadaqah Anda melalui rekening resmi di bawah ini.</p>
                    </div>
                    <?php if (empty($bankAccounts)): ?>
                        <div class="text-muted italic py-3">Belum ada informasi rekening bank yang tersedia.</div>
                    <?php else: ?>
                        <?php foreach ($bankAccounts as $bank): ?>
                            <div class="bank-item group" onclick="copyToClipboard('<?= e($bank['nomor_rekening']) ?>')">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-slate-900"><?= e($bank['nama_bank']) ?></span>
                                    <i class="bi bi-copy text-slate-300 group-hover:text-sky-500"></i>
                                </div>
                                <div class="text-xl font-mono font-bold tracking-wider text-sky-600 mb-1"><?= e($bank['nomor_rekening']) ?></div>
                                <div class="text-xs text-slate-400 font-medium tracking-wide uppercase">A.N. <?= e($bank['nama_pemilik']) ?></div>
                            </div>
                        <?php endforeach; ?>
                        <p class="text-[10px] text-slate-400 mt-2 uppercase font-bold tracking-widest flex items-center justify-center gap-1">
                            <i class="bi bi-info-circle"></i> Klik nomor rekening untuk menyalin
                        </p>
                    <?php endif; ?>
                </div>

                <div class="card-custom">
                    <div class="p-4 border-b border-slate-100 flex items-center gap-2">
                        <i class="bi bi-bar-chart-fill text-sky-500"></i>
                        <h6 class="m-0 font-bold">Tren Arus Kas <?= date('Y') ?></h6>
                    </div>
                    <div class="p-4">
                        <div style="height: 300px;"><canvas id="chartPublik"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- Right: Active Donation Programs -->
            <div class="col-lg-7">
                <h2 class="section-title"><i class="bi bi-heart-pulse"></i>Program Donasi</h2>
                <?php if (empty($donasiList)): ?>
                    <div class="text-center py-5 bg-white rounded-3xl border border-dashed border-slate-300">
                        <i class="bi bi-heartbreak text-slate-200" style="font-size: 4rem;"></i>
                        <p class="text-slate-400 mt-3 font-medium">Belum ada program donasi aktif saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="grid gap-5">
                        <?php foreach ($donasiList as $d): ?>
                            <?php $progress = $d['target_nominal'] > 0 ? min(100, round(($d['total_pemasukan'] / $d['target_nominal']) * 100)) : 0; ?>
                            <?php 
                                $displayImage = $d['gambar'] ?? '';
                                if (empty($displayImage) && !empty($d['flyer_file']) && isImageFile($d['flyer_file'])) {
                                    $displayImage = $d['flyer_file'];
                                }
                            ?>
                            <div class="program-card">
                                <div class="row g-0">
                                    <?php if (!empty($displayImage)): ?>
                                        <div class="col-md-5">
                                            <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($displayImage) ?>" alt="<?= e($d['nama_donasi']) ?>">
                                        </div>
                                    <?php endif; ?>
                                    <div class="<?= !empty($displayImage) ? 'col-md-7' : 'col-md-12' ?> p-4 d-flex flex-column">
                                        <h4 class="fw-bold text-slate-800 mb-2"><?= e($d['nama_donasi']) ?></h4>
                                        <p class="text-slate-500 text-sm mb-4 line-clamp-3"><?= e(truncate($d['uraian'] ?? '', 140)) ?></p>
                                        
                                        <div class="mt-auto">
                                            <div class="flex justify-between items-end mb-2">
                                                <div>
                                                    <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Terkumpul</span>
                                                    <div class="text-lg font-bold text-sky-600"><?= rupiah((float)$d['total_pemasukan']) ?></div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Target</span>
                                                    <div class="text-slate-600 font-semibold"><?= rupiah((float)$d['target_nominal']) ?></div>
                                                </div>
                                            </div>
                                            <div class="w-full bg-slate-100 h-3 rounded-full mb-4 overflow-hidden">
                                                <div class="bg-sky-500 h-full transition-all duration-1000" style="width: <?= $progress ?>%; shadow: 0 0 10px rgba(14, 165, 233, 0.5);"></div>
                                            </div>
                                            <div class="flex gap-3">
                                                <a href="<?= e(donasiPublicUrl($d)) ?>" class="btn btn-primary d-block w-100 rounded-xl py-2 font-bold shadow-lg shadow-sky-200" style="background-color: var(--accent-color); border: none;">
                                                    <i class="bi bi-arrow-right-circle me-2"></i>Donasi Sekarang
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Middle: Kegiatan Section -->
        <div class="mt-5 pt-5">
            <h2 class="section-title"><i class="bi bi-calendar-check"></i>Agenda & Kegiatan</h2>
            <div class="row g-4">
                <?php if (empty($kegiatanList)): ?>
                    <div class="col-12"><p class="text-center py-5 text-slate-400">Belum ada agenda kegiatan publik.</p></div>
                <?php else: ?>
                    <?php foreach ($kegiatanList as $k): ?>
                        <div class="col-lg-6">
                            <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all h-100">
                                <div class="row g-0 h-100">
                                    <div class="col-sm-5 relative">
                                        <?php if (!empty($k['gambar'])): ?>
                                            <img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($k['gambar']) ?>" class="w-full h-full object-cover" style="min-height: 200px;">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-sky-200">
                                                <i class="bi bi-calendar4-event" style="font-size: 4rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="absolute top-4 left-4">
                                            <span class="px-3 py-1 rounded-full bg-sky-500 text-white text-[10px] font-bold uppercase tracking-widest shadow-lg"><?= e($k['status'] ?? 'aktif') ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 p-5">
                                        <h5 class="fw-bold text-slate-800 mb-3 font-outfit"><?= e($k['nama_kegiatan']) ?></h5>
                                        <div class="space-y-2 mb-4">
                                            <div class="flex items-center gap-2 text-slate-500 text-sm"><i class="bi bi-geo-alt text-sky-500"></i> <?= e($k['waktu_tempat'] ?? '-') ?></div>
                                            <div class="flex items-center gap-2 text-slate-500 text-sm"><i class="bi bi-person text-sky-500"></i> PJ: <?= e($k['penanggung_jawab'] ?? '-') ?></div>
                                            <div class="flex items-center gap-2 text-slate-500 text-sm"><i class="bi bi-cash-stack text-sky-500"></i> Dana: <?= e($k['sumber_dana'] ?? '-') ?></div>
                                        </div>
                                        <div class="pt-3 border-t border-slate-50 flex justify-between items-center">
                                            <span class="text-xs text-slate-400 font-medium">Sisa Anggaran:</span>
                                            <span class="font-bold text-slate-700"><?= rupiah((float)($k['sisa_anggaran'] ?? 0)) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-12 py-8 border-t border-slate-200">
            <p class="text-slate-400 font-medium">&copy; <?= date('Y') ?> <?= e($namaApp) ?> &bull; Transparansi Keuangan Digital</p>
            <?php if (!Auth::check()): ?>
                <a href="<?= BASE_URL ?>/login" class="text-slate-400 hover:text-sky-500 transition-colors text-sm font-semibold">Portal Admin &rarr;</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Nomor rekening disalin ke clipboard!');
        });
    }

    new Chart(document.getElementById('chartPublik').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode($bulanNames) ?>,
            datasets: [{
                label: 'Pemasukan', data: <?= $chartPemasukan ?>,
                borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, 0.1)',
                fill: true, tension: 0.4, borderWidth: 4, pointRadius: 4, pointHoverRadius: 6
            }, {
                label: 'Pengeluaran', data: <?= $chartPengeluaran ?>,
                borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.05)',
                fill: true, tension: 0.4, borderWidth: 4, pointRadius: 4, pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 6, font: { weight: 'bold' } } } 
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'Jt', font: { weight: '600' } }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false }, ticks: { font: { weight: '600' } } }
            }
        }
    });
    </script>
</body>
</html>
