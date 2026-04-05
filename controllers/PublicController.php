<?php
class PublicController extends Controller {

    public function index(): void {
        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/Setting.php';
        require_once BASE_PATH . '/models/Donasi.php';
        require_once BASE_PATH . '/models/Kegiatan.php';
        require_once BASE_PATH . '/models/BankAccount.php';
        require_once BASE_PATH . '/models/CashAccount.php';

        $pemasukan      = new Pemasukan();
        $pengeluaran    = new Pengeluaran();
        $settingModel   = new Setting();
        $donasi         = new Donasi();
        $kegiatan       = new Kegiatan();
        $bankAccountModel = new BankAccount();
        $cashAccountModel = new CashAccount();

        $totalPemasukan     = $pemasukan->getTotal();
        $totalPengeluaran   = $pengeluaran->getTotal();
        $saldo              = $totalPemasukan - $totalPengeluaran;

        $pemasukanBulanIni  = $pemasukan->getTotalBulanIni();
        $pengeluaranBulanIni = $pengeluaran->getTotalBulanIni();

        // Account balances
        $bankAccounts = $bankAccountModel->getAllAccounts(true, true);
        $cashAccounts = $cashAccountModel->getAllAccounts(true);
        $totalBank    = array_sum(array_column($bankAccountModel->getAllAccounts(), 'saldo_saat_ini'));
        $totalCash    = array_sum(array_column($cashAccountModel->getAllAccounts(), 'saldo_saat_ini'));

        $year = date('Y');
        $pemasukanBulanan  = $pemasukan->getMonthlyData($year);
        $pengeluaranBulanan = $pengeluaran->getMonthlyData($year);

        $chartPemasukan  = array_fill(0, 12, 0);
        $chartPengeluaran = array_fill(0, 12, 0);
        foreach ($pemasukanBulanan  as $d) $chartPemasukan[$d['bulan'] - 1]  = (float)$d['total'];
        foreach ($pengeluaranBulanan as $d) $chartPengeluaran[$d['bulan'] - 1] = (float)$d['total'];

        $donasiList   = $donasi->getPublishedWithStats();
        $kegiatanList = $kegiatan->getPublishedWithStats();
        $settings     = $settingModel->getAllSettings();

        $this->view('public/dashboard', [
            'settings'            => $settings,
            'totalPemasukan'      => $totalPemasukan,
            'totalPengeluaran'    => $totalPengeluaran,
            'saldo'               => $saldo,
            'pemasukanBulanIni'   => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'totalBank'           => $totalBank,
            'totalCash'           => $totalCash,
            'chartPemasukan'      => json_encode($chartPemasukan),
            'chartPengeluaran'    => json_encode($chartPengeluaran),
            'donasiList'          => $donasiList,
            'kegiatanList'        => $kegiatanList,
            'bankAccounts'        => $bankAccounts,
        ]);
    }

    public function donasi(string $slug = ''): void {
        require_once BASE_PATH . '/models/Donasi.php';
        require_once BASE_PATH . '/models/Setting.php';

        $donasi = new Donasi();
        $settingModel = new Setting();
        $record = $donasi->getPublicBySlug($slug);

        if (!$record) {
            http_response_code(404);
            require BASE_PATH . '/views/errors/404.php';
            return;
        }

        $this->view('public/donasi-detail', [
            'settings' => $settingModel->getAllSettings(),
            'record' => $record,
            'dokumentasiFiles' => decodeJsonArray($record['dokumentasi_files'] ?? null),
            'shareUrl' => donasiPublicUrl($record),
        ]);
    }
}
