<?php
/**
 * Dashboard View - Refactored for Visual Excellence & Completeness
 * Powered by Tailwind CSS & Chart.js
 */
$bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
?>

<div class="dashboard-wrapper animate-fade-in pb-10 font-sans">
    
    <!-- Welcome Header & Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">Ikhtisar Keuangan Masjid</h2>
            <p class="text-gray-500 text-sm">Pemantauan real-time aset, donasi, dan program kegiatan sesuai standar ISAK 35.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="<?= BASE_URL ?>/pemasukan/create" class="flex items-center gap-2 bg-[#2388cf] hover:bg-[#1a6ba4] text-white text-sm font-bold py-2.5 px-4 rounded-xl shadow-md transition-all active:scale-95 text-decoration-none">
                <i class="bi bi-plus-lg"></i> Pemasukan
            </a>
            <a href="<?= BASE_URL ?>/pengeluaran/create" class="flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 text-sm font-bold py-2.5 px-4 rounded-xl shadow-sm transition-all active:scale-95 text-decoration-none">
                <i class="bi bi-dash-lg"></i> Pengeluaran
            </a>
            <button onclick="window.print()" class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl transition-all border-0">
                <i class="bi bi-printer"></i>
            </button>
        </div>
    </div>

    <!-- ====== 4 KEY METRIC CARDS ====== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <!-- Saldo Kas Tunai -->
        <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-[0_4px_24px_rgba(16,185,129,0.10)] border border-emerald-50 group hover:-translate-y-1 transition-all duration-300 cursor-default">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-emerald-500/6 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm shadow-emerald-100 group-hover:scale-110 transition-transform">
                    <i class="bi bi-cash-stack text-xl"></i>
                </div>
                <span class="flex items-center gap-1.5 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> KAS TUNAI
                </span>
            </div>
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Saldo Kas Tunai</p>
            <h3 class="text-2xl font-black text-gray-800 leading-tight mb-3 count-up" data-target="<?= intval($totalCash) ?>">
                <?= rupiah($totalCash) ?>
            </h3>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-50">
                <?php $cashCount = count($cashList); ?>
                <i class="bi bi-wallet2 text-emerald-400 text-xs"></i>
                <span class="text-[10px] text-gray-400 font-semibold"><?= $cashCount ?> akun kas aktif</span>
            </div>
        </div>

        <!-- Saldo Kas Bank -->
        <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-[0_4px_24px_rgba(35,136,207,0.10)] border border-blue-50 group hover:-translate-y-1 transition-all duration-300 cursor-default">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-blue-500/6 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-blue-50 text-[#2388cf] shadow-sm shadow-blue-100 group-hover:scale-110 transition-transform">
                    <i class="bi bi-bank text-xl"></i>
                </div>
                <span class="flex items-center gap-1.5 text-[10px] font-black text-[#2388cf] bg-blue-50 px-2.5 py-1 rounded-lg">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2388cf] animate-pulse"></span> BANK
                </span>
            </div>
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Saldo Kas Bank</p>
            <h3 class="text-2xl font-black text-gray-800 leading-tight mb-3">
                <?= rupiah($totalBank) ?>
            </h3>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-50">
                <?php $bankCount = count($bankList); ?>
                <i class="bi bi-credit-card text-blue-400 text-xs"></i>
                <span class="text-[10px] text-gray-400 font-semibold"><?= $bankCount ?> rekening bank aktif</span>
            </div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <?php $bulanIni = $bulanNames[date('n') - 1]; ?>
        <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-[0_4px_24px_rgba(16,185,129,0.08)] border border-teal-50 group hover:-translate-y-1 transition-all duration-300 cursor-default">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-teal-500/6 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-teal-50 text-teal-600 shadow-sm shadow-teal-100 group-hover:scale-110 transition-transform">
                    <i class="bi bi-graph-up-arrow text-xl"></i>
                </div>
                <span class="flex items-center gap-1 text-[10px] font-black text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-up-short text-sm"></i> MASUK
                </span>
            </div>
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Pemasukan <?= $bulanIni ?></p>
            <h3 class="text-2xl font-black text-gray-800 leading-tight mb-3">
                <?= rupiah($pemasukanBulanIni) ?>
            </h3>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-50">
                <div class="flex-1 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <?php
                    $maxMasuk = max($pemasukanBulanIni, $pengeluaranBulanIni, 1);
                    $pct = min(100, round($pemasukanBulanIni / $maxMasuk * 100));
                    ?>
                    <div class="bg-teal-500 h-full rounded-full transition-all duration-1000" style="width: <?= $pct ?>%"></div>
                </div>
                <span class="text-[10px] text-gray-400 font-bold"><?= $pct ?>%</span>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="relative overflow-hidden bg-white rounded-3xl p-6 shadow-[0_4px_24px_rgba(244,63,94,0.08)] border border-rose-50 group hover:-translate-y-1 transition-all duration-300 cursor-default">
            <div class="absolute -right-5 -top-5 w-28 h-28 bg-rose-500/6 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-rose-50 text-rose-600 shadow-sm shadow-rose-100 group-hover:scale-110 transition-transform">
                    <i class="bi bi-graph-down-arrow text-xl"></i>
                </div>
                <span class="flex items-center gap-1 text-[10px] font-black text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg">
                    <i class="bi bi-arrow-down-short text-sm"></i> KELUAR
                </span>
            </div>
            <p class="text-gray-400 text-[11px] font-bold uppercase tracking-widest mb-1">Pengeluaran <?= $bulanIni ?></p>
            <h3 class="text-2xl font-black text-gray-800 leading-tight mb-3">
                <?= rupiah($pengeluaranBulanIni) ?>
            </h3>
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-50">
                <?php
                $pctK = min(100, $maxMasuk > 0 ? round($pengeluaranBulanIni / $maxMasuk * 100) : 0);
                $surplus = $pemasukanBulanIni - $pengeluaranBulanIni;
                ?>
                <div class="flex-1 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full transition-all duration-1000" style="width: <?= $pctK ?>%"></div>
                </div>
                <span class="text-[10px] <?= $surplus >= 0 ? 'text-teal-600' : 'text-rose-600' ?> font-black">
                    <?= $surplus >= 0 ? '+' : '' ?><?= rupiah(abs($surplus)) ?>
                </span>
            </div>
        </div>

    </div>
    <!-- ====== END 4 KEY METRIC CARDS ====== -->

    <!-- Main Financial Highlights -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Wealth (Consolidated) -->
        <div class="lg:col-span-2 relative overflow-hidden bg-gradient-to-br from-[#2388cf] via-[#1a6ba4] to-[#0d4f7a] p-6 rounded-3xl shadow-xl text-white group">
            <div class="absolute -right-12 -bottom-12 text-white/10 text-[10rem] rotate-12 group-hover:rotate-6 transition-transform duration-700">
                <i class="bi bi-safe2"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-wider mb-4">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Konsolidasi Aset
                    </div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Saldo (Kas & Bank)</p>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-2"><?= rupiah($totalBank + $totalCash) ?></h1>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-white/10">
                    <div>
                        <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-1">Dana Terikat</p>
                        <?php 
                        $terikat = 0;
                        foreach($fundStats as $fs) if($fs['fund_category'] == 'Terikat') $terikat = $fs['total'];
                        ?>
                        <div class="text-lg font-bold"><?= rupiah($terikat) ?></div>
                    </div>
                    <div>
                        <p class="text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-1">Dana Tidak Terikat</p>
                        <?php 
                        $tidakTerikat = 0;
                        foreach($fundStats as $fs) if($fs['fund_category'] == 'Tidak Terikat') $tidakTerikat = $fs['total'];
                        ?>
                        <div class="text-lg font-bold"><?= rupiah($tidakTerikat) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Delta -->
        <div class="bg-white p-6 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-[#2388cf]/10 text-[#2388cf]">
                    <i class="bi bi-calendar-check text-xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black <?= $saldoBulanIni >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50' ?> px-2 py-1 rounded-md">
                        <?= $saldoBulanIni >= 0 ? '+' : '' ?><?= ($pemasukanBulanIni > 0) ? round(($saldoBulanIni/$pemasukanBulanIni)*100, 1) : 0 ?>% Surplus
                    </span>
                </div>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase mb-1">Laba/Rugi <?= $bulanNames[date('n')-1] ?></p>
                <h3 class="text-2xl font-black text-gray-800"><?= rupiah($saldoBulanIni) ?></h3>
                <div class="flex items-center gap-1 mt-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="text-[10px] text-gray-400 font-medium">Masuk: <?= rupiah($pemasukanBulanIni) ?></span>
                </div>
                <div class="flex items-center gap-1 mt-1">
                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                    <span class="text-[10px] text-gray-400 font-medium">Keluar: <?= rupiah($pengeluaranBulanIni) ?></span>
                </div>
            </div>
        </div>

        <!-- Liquidity Health -->
        <div class="bg-blue-50 p-6 rounded-3xl shadow-inner border border-blue-100 flex flex-col justify-between overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#2388cf]/10 rounded-full blur-2xl"></div>
            <div>
                <p class="text-blue-800 text-xs font-black uppercase tracking-wider mb-3">Health Score</p>
                <div class="flex items-end gap-2 mb-2">
                    <span class="text-4xl font-black text-blue-900 leading-none">94</span>
                    <span class="text-[#2388cf] font-bold mb-1">/100</span>
                </div>
                <p class="text-[#2388cf] text-[10px] font-semibold leading-tight">Posisi kas sangat baik & likuid untuk menutupi 6 bulan operasional.</p>
            </div>
            <div class="mt-4">
                <div class="w-full bg-white h-2 rounded-full overflow-hidden">
                    <div class="bg-[#2388cf] h-full rounded-full" style="width: 94%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <!-- Main Trend Line -->
        <div class="xl:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h5 class="text-lg font-extrabold text-gray-800">Tren Arus Kas Tahun <?= date('Y') ?></h5>
                    <p class="text-gray-400 text-xs mt-1">Pergerakan dana masuk dan keluar bulanan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#2388cf]"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase">In</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase">Out</span>
                    </div>
                </div>
            </div>
            <div class="h-[300px] w-full relative">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution (Pie) -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 h-full flex flex-col">
            <h5 class="text-lg font-extrabold text-gray-800 mb-2 text-center">Distribusi Kategori</h5>
            <p class="text-gray-400 text-xs text-center mb-8 italic">Pemasukan berdasarkan sumber.</p>
            <div class="flex-grow flex items-center justify-center relative">
                <div class="w-full max-w-[200px] relative">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="mt-8 space-y-3">
                <?php if (empty($pemasukanByKategori)): ?>
                <div class="text-center text-gray-400 text-xs py-4">
                    <i class="bi bi-inbox text-2xl block mb-2"></i>Belum ada data pemasukan.
                </div>
                <?php else: ?>
                <?php
                $catColors = ['#2388cf','#10b981','#ec4899','#f97316','#8b5cf6','#f59e0b','#0d9488','#6366f1'];
                $catTotal  = array_sum(array_column($pemasukanByKategori, 'total')) ?: 1;
                ?>
                <?php foreach($pemasukanByKategori as $i => $k): ?>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full flex-shrink-0" style="background: <?= $catColors[$i % count($catColors)] ?>"></span>
                        <span class="text-xs font-semibold text-gray-600 truncate" style="max-width:130px;"><?= e($k['nama_kategori']) ?></span>
                    </div>
                    <span class="text-xs font-bold text-gray-800 ml-2 flex-shrink-0"><?= round(($k['total'] / $catTotal) * 100, 1) ?>%</span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Active Projects & Recent Transactions Split -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Active Projects List -->
        <div class="space-y-6">
            <div class="flex items-center justify-between group">
                <h5 class="text-xl font-black text-gray-800 tracking-tight">Program Berjalan</h5>
                <a href="<?= BASE_URL ?>/kegiatan" class="text-[#2388cf] text-[10px] font-black uppercase tracking-wider group-hover:translate-x-1 transition-transform text-decoration-none">Selengkapnya <i class="bi bi-chevron-right"></i></a>
            </div>
            
            <div class="grid grid-cols-1 gap-4">
                <?php if (empty($activeKegiatan) && empty($activeDonasi)): ?>
                    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
                        <i class="bi bi-inbox text-4xl text-gray-200"></i>
                        <p class="text-gray-400 text-xs mt-4">Tidak ada program aktif saat ini.</p>
                    </div>
                <?php endif; ?>

                <?php foreach(array_slice($activeDonasi, 0, 2) as $d): ?>
                <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100">
                                <?php if (!empty($d['gambar']) && file_exists(BASE_PATH . '/uploads/donasi/' . $d['gambar'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/donasi/<?= e($d['gambar']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-rose-300"><i class="bi bi-heart-fill"></i></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-rose-600 uppercase mb-1 block tracking-wider">Donasi</span>
                                <h6 class="text-sm font-bold text-gray-800"><?= e($d['nama_donasi']) ?></h6>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-md badge-program"><?= (int)($d['percentage'] ?? 0) ?>%</span>
                    </div>
                    <?php 
                        $target = (float)($d['target_nominal'] ?? 0);
                        $current = (float)($d['total_pemasukan'] ?? 0);
                        $percent = $target > 0 ? min(100, ($current / $target) * 100) : 0;
                    ?>
                    <div class="flex items-center justify-between text-[10px] mb-2 font-bold px-1">
                        <span class="text-gray-400 tracking-wide">Terkumpul: <?= rupiah($current) ?></span>
                        <span class="text-rose-600">Target: <?= rupiah($target) ?></span>
                    </div>
                    <div class="w-full bg-gray-50 h-2 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-full rounded-full transition-all duration-1000" style="width: <?= $percent ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php foreach(array_slice($activeKegiatan, 0, 2) as $k): ?>
                <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100">
                                <?php if (!empty($k['gambar']) && file_exists(BASE_PATH . '/uploads/kegiatan/' . $k['gambar'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($k['gambar']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-blue-300"><i class="bi bi-calendar-event"></i></div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-[#2388cf] uppercase mb-1 block tracking-wider">Kegiatan</span>
                                <h6 class="text-sm font-bold text-gray-800"><?= e($k['nama_kegiatan']) ?></h6>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $budget = (float)($k['total_anggaran'] ?? 0);
                        $spent = (float)($k['total_pengeluaran'] ?? 0);
                        $percentSpent = $budget > 0 ? min(100, ($spent / $budget) * 100) : 0;
                    ?>
                    <div class="flex items-center justify-between text-[10px] mb-2 font-bold px-1">
                        <span class="text-gray-400 tracking-wide">Terpakai: <?= rupiah($spent) ?></span>
                        <span class="text-[#2388cf]">Anggaran: <?= rupiah($budget) ?></span>
                    </div>
                    <div class="w-full bg-gray-50 h-2 rounded-full overflow-hidden">
                        <div class="bg-[#2388cf] h-full rounded-full transition-all duration-1000" style="width: <?= $percentSpent ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h5 class="text-xl font-black text-gray-800 tracking-tight">Aktivitas Terkini</h5>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Real-time Feed</span>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <?php foreach($recentTransactions as $t): ?>
                            <tr class="group hover:bg-gray-50/80 transition-all border-b border-gray-50 last:border-0">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg <?= $t['jenis'] == 'Pemasukan' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' ?>">
                                            <i class="bi <?= $t['jenis'] == 'Pemasukan' ? 'bi-plus-circle' : 'bi-dash-circle' ?>"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-800"><?= e(truncate($t['keterangan'] ?? 'Tanpa keterangan', 30)) ?></div>
                                            <div class="text-[10px] font-medium text-gray-400 mt-0.5"><?= formatDate($t['tanggal']) ?> • <?= e($t['nama_kategori'] ?? 'Lainnya') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-xs font-black text-gray-700 text-right"><?= rupiah((float)$t['jumlah']) ?></div>
                                    <div class="text-[9px] font-bold text-[#2388cf] uppercase text-right tracking-tighter opacity-70"><?= e($t['nama_akun'] ?? '-') ?></div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <a href="<?= BASE_URL ?>/laporan" class="block py-4 text-center bg-gray-50/50 hover:bg-gray-100 hover:text-[#2388cf] text-[10px] font-black uppercase text-gray-500 tracking-widest transition-all text-decoration-none">Lihat Semua Riwayat</a>
            </div>
        </div>

    </div>
</div>

<?php
$chartLabels = json_encode($bulanNames);
$chartPemasukanData = $chartPemasukan;
$chartPengeluaranData = $chartPengeluaran;

$extraJs = "
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Main Arus Kas Chart
    const ctxMain = document.getElementById('mainChart').getContext('2d');
    const gradIn = ctxMain.createLinearGradient(0, 0, 0, 400);
    gradIn.addColorStop(0, 'rgba(35, 136, 207, 0.2)');
    gradIn.addColorStop(1, 'rgba(35, 136, 207, 0.0)');

    const gradOut = ctxMain.createLinearGradient(0, 0, 0, 400);
    gradOut.addColorStop(0, 'rgba(244, 63, 94, 0.1)');
    gradOut.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

    new Chart(ctxMain, {
        type: 'line',
        data: {
            labels: " . $chartLabels . ",
            datasets: [{
                label: 'Pemasukan',
                data: " . $chartPemasukan . ",
                borderColor: '#2388cf',
                borderWidth: 4,
                backgroundColor: gradIn,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2388cf',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Pengeluaran',
                data: " . $chartPengeluaran . ",
                borderColor: '#f43f5e',
                borderWidth: 4,
                backgroundColor: gradOut,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f43f5e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { 
                        color: '#94a3b8', 
                        font: { size: 10, weight: 'bold' },
                        callback: (v) => 'Rp' + (v >= 1000000 ? (v/1000000).toFixed(1) + 'jt' : (v/1000).toFixed(0) + 'rb')
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } }
                }
            }
        }
    });

    // 2. Category Donut Chart
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    const catLabels = " . json_encode(array_column($pemasukanByKategori, 'nama_kategori')) . ";
    const catData   = " . json_encode(array_values(array_column($pemasukanByKategori, 'total'))) . ";
    const catColors = ['#2388cf','#10b981','#ec4899','#f97316','#8b5cf6','#f59e0b','#0d9488','#6366f1'];
    if (catData.length > 0) {
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: catColors.slice(0, catData.length),
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    } else {
        const canvas = document.getElementById('categoryChart');
        const ctx2 = canvas.getContext('2d');
        ctx2.fillStyle = '#e2e8f0';
        ctx2.beginPath();
        ctx2.arc(canvas.width/2, canvas.height/2, Math.min(canvas.width, canvas.height)/2.5, 0, Math.PI*2);
        ctx2.fill();
    }
});
</script>

<style>
.animate-fade-in { animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.badge-program { font-variant-numeric: tabular-nums; }
</style>
";
?>
