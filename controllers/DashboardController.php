<?php
class DashboardController extends Controller {

    public function index(): void {
        $this->requireAuth();

        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/Kegiatan.php';
        require_once BASE_PATH . '/models/Donasi.php';
        require_once BASE_PATH . '/models/BankAccount.php';
        require_once BASE_PATH . '/models/CashAccount.php';
        require_once BASE_PATH . '/models/AccountMutation.php';

        $pemasukan = new Pemasukan();
        $pengeluaran = new Pengeluaran();
        $kegiatan = new Kegiatan();
        $donasi = new Donasi();
        $bankAccount = new BankAccount();
        $cashAccount = new CashAccount();
        $mutation = new AccountMutation();

        // Stats
        $totalPemasukan = $pemasukan->getTotal();
        $totalPengeluaran = $pengeluaran->getTotal();
        $saldo = $totalPemasukan - $totalPengeluaran;

        $pemasukanBulanIni = $pemasukan->getTotalBulanIni();
        $pengeluaranBulanIni = $pengeluaran->getTotalBulanIni();
        $saldoBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;

        // Account Summaries
        $bankList = $bankAccount->getAllAccounts();
        $cashList = $cashAccount->getAllAccounts();
        $totalBank = array_sum(array_column($bankList, 'saldo_saat_ini'));
        $totalCash = array_sum(array_column($cashList, 'saldo_saat_ini'));

        // Fund Category Summaries (ISAK 35)
        $fundStats = $mutation->getNetByFundCategory();

        // Category balances
        $pemasukanByKategori = $pemasukan->getTotalByKategori();
        $pengeluaranByKategori = $pengeluaran->getTotalByKategori();

        // Monthly chart data
        $year = date('Y');
        $pemasukanBulanan = $pemasukan->getMonthlyData($year);
        $pengeluaranBulanan = $pengeluaran->getMonthlyData($year);

        // Recent transactions
        $recentPemasukan = $pemasukan->getRecentTransactions(5);
        $recentPengeluaran = $pengeluaran->getAllWithRelations(5);

        // Merge and sort by date
        $recentTransactions = [];
        foreach ($recentPemasukan as $r) {
            $r['jenis'] = 'Pemasukan';
            $recentTransactions[] = $r;
        }
        foreach ($recentPengeluaran as $r) {
            $r['jenis'] = 'Pengeluaran';
            $r['nama_donatur'] = $r['penerima'] ?? '-';
            $r['donatur_nama'] = $r['penerima'] ?? '-';
            $recentTransactions[] = $r;
        }
        usort($recentTransactions, fn($a, $b) => strtotime($b['tanggal']) - strtotime($a['tanggal']));
        // Recent transactions (limit 10)
        $recentTransactions = array_slice($recentTransactions, 0, 10);

        // Program Stats & Active Lists
        $kegiatanStats = $kegiatan->getDashboardStats();
        $donasiStats = $donasi->getDashboardStats();
        
        $activeKegiatan = $kegiatan->getActiveWithStats();
        $activeDonasi = $donasi->getPublishedWithStats();

        // Prepare chart data
        $chartPemasukan = array_fill(0, 12, 0);
        $chartPengeluaran = array_fill(0, 12, 0);
        foreach ($pemasukanBulanan as $d) {
            $chartPemasukan[(int)$d['bulan'] - 1] = (float)$d['total'];
        }
        foreach ($pengeluaranBulanan as $d) {
            $chartPengeluaran[(int)$d['bulan'] - 1] = (float)$d['total'];
        }

        $this->renderPage('dashboard/index', [
            'pageTitle' => 'Dashboard Keuangan Masjid',
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'saldoBulanIni' => $saldoBulanIni,
            'totalBank' => $totalBank,
            'totalCash' => $totalCash,
            'bankList' => $bankList,
            'cashList' => $cashList,
            'fundStats' => $fundStats,
            'pemasukanByKategori' => $pemasukanByKategori,
            'pengeluaranByKategori' => $pengeluaranByKategori,
            'recentTransactions' => $recentTransactions,
            'chartPemasukan' => json_encode($chartPemasukan),
            'chartPengeluaran' => json_encode($chartPengeluaran),
            'kegiatanStats' => $kegiatanStats,
            'donasiStats' => $donasiStats,
            'activeKegiatan' => $activeKegiatan,
            'activeDonasi' => $activeDonasi,
        ]);
    }

    public function notFound(): void {
        http_response_code(404);
        require_once BASE_PATH . '/views/errors/404.php';
    }
}
