<?php
class LaporanController extends Controller
{

    public function getReportSettings(): array
    {
        require_once BASE_PATH . '/models/Setting.php';
        $settingModel = new Setting();
        return [
            'nama_masjid' => $settingModel->getValue('nama_masjid', 'Masjid Al-Ikhlas'),
            'alamat' => $settingModel->getValue('alamat', 'Jl. Example No.1'),
            'no_telepon' => $settingModel->getValue('no_telepon', '08123456789'),
            'jabatan_ketua' => $settingModel->getValue('ketua_jabatan', 'Ketua Pengurus'),
            'ketua' => $settingModel->getValue('ketua_nama', 'Nama Ketua'),
            'jabatan_sekretaris' => $settingModel->getValue('sekretaris_jabatan', 'Sekretaris'),
            'sekretaris' => $settingModel->getValue('sekretaris_nama', 'Nama Sekretaris'),
            'jabatan_bendahara' => $settingModel->getValue('bendahara_jabatan', 'Bendahara'),
            'bendahara' => $settingModel->getValue('bendahara_nama', 'Nama Bendahara'),
            'dibuat' => date('d F Y H:i')
        ];
    }

    public function index(): void
    {
        $this->requireRole(['Super Admin', 'Bendahara']);
        $report = $_GET['report'] ?? 'dashboard';
        $periode = $_GET['periode'] ?? 'bulanan';
        $bulan = $_GET['bulan'] ?? date('Y-m');
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        $tahun = $_GET['tahun'] ?? date('Y');

        // Dispatch to specific report
        $method = str_replace('-', '', $report) . 'Report';
        if (method_exists($this, $method)) {
            $this->$method($periode, $bulan, $tanggal, $tahun);
            return;
        }

        $this->dashboardReport($periode, $bulan, $tanggal, $tahun);
    }

    public function dashboardReport($periode, $bulan, $tanggal, $tahun, $available_reports = null): void
    {
        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/BankAccount.php';
        require_once BASE_PATH . '/models/CashAccount.php';
        require_once BASE_PATH . '/models/AccountMutation.php';

        $pemasukan = new Pemasukan();
        $pengeluaran = new Pengeluaran();
        $bankAccount = new BankAccount();
        $cashAccount = new CashAccount();
        $mutationModel = new AccountMutation();

        $jenis = $periode;
        $dataPemasukan = [];
        $dataPengeluaran = [];
        $totalMasuk = 0;
        $totalKeluar = 0;
        $reportEndDate = date('Y-m-d');

        switch ($jenis) {
            case 'harian':
                $dataPemasukan = $pemasukan->getAllWithRelations(0, 0, ['tanggal_dari' => $tanggal, 'tanggal_sampai' => $tanggal]);
                $dataPengeluaran = $pengeluaran->getAllWithRelations(0, 0, ['tanggal_dari' => $tanggal, 'tanggal_sampai' => $tanggal]);
                $reportEndDate = $tanggal;
                break;
            case 'bulanan':
                $dataPemasukan = $pemasukan->getAllWithRelations(0, 0, ['bulan' => $bulan]);
                $dataPengeluaran = $pengeluaran->getAllWithRelations(0, 0, ['bulan' => $bulan]);
                $reportEndDate = date('Y-m-t', strtotime($bulan . '-01'));
                break;
            case 'tahunan':
                $startDate = $tahun . '-01-01';
                $endDate = $tahun . '-12-31';
                $dataPemasukan = $pemasukan->getAllWithRelations(0, 0, ['tanggal_dari' => $startDate, 'tanggal_sampai' => $endDate]);
                $dataPengeluaran = $pengeluaran->getAllWithRelations(0, 0, ['tanggal_dari' => $startDate, 'tanggal_sampai' => $endDate]);
                $reportEndDate = $endDate;
                break;
            default:
                $reportEndDate = date('Y-m-d');
                break;
        }

        foreach ($dataPemasukan as $d)
            $totalMasuk += (float) $d['jumlah'];
        foreach ($dataPengeluaran as $d)
            $totalKeluar += (float) $d['jumlah'];

        $pemasukanByKat = $pemasukan->getTotalByKategori();
        $pengeluaranByKat = $pengeluaran->getTotalByKategori();
        $bankPosition = $bankAccount->getAllWithPosition($reportEndDate);
        $cashPosition = $cashAccount->getAllWithPosition($reportEndDate);
        $positionByFund = $mutationModel->getNetByFundCategory($reportEndDate);

        $totalBankPosition = 0;
        foreach ($bankPosition as $item) {
            $totalBankPosition += (float) ($item['saldo_posisi'] ?? 0);
        }

        $totalCashPosition = 0;
        foreach ($cashPosition as $item) {
            $totalCashPosition += (float) ($item['saldo_posisi'] ?? 0);
        }

        $this->renderPage('laporan/index', [
            'pageTitle' => 'Dashboard Laporan Keuangan',
            'jenis' => $periode,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal' => $tanggal,
            'dataPemasukan' => $dataPemasukan,
            'dataPengeluaran' => $dataPengeluaran,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $totalMasuk - $totalKeluar,
            'pemasukanByKat' => $pemasukanByKat,
            'pengeluaranByKat' => $pengeluaranByKat,
            'bankPosition' => $bankPosition,
            'cashPosition' => $cashPosition,
            'positionByFund' => $positionByFund,
            'reportEndDate' => $reportEndDate,
            'totalBankPosition' => $totalBankPosition,
            'totalCashPosition' => $totalCashPosition,
            'available_reports' => $available_reports ?: [
                'arus-kas' => 'Arus Kas (Cash Flow)',
                'kas-tunai' => 'Laporan Kas Tunai',
                'kas-bank' => 'Laporan Kas Bank',
                'posisi-dana' => 'Posisi Dana per Kategori',
                'realisasi-kegiatan' => 'Realisasi Anggaran Kegiatan',
                'program-donasi' => 'Laporan Program Donasi',
                'periodik' => 'Laporan Harian/Bulanan/Tahunan'
            ],
            'settings' => $this->getReportSettings()
        ]);
    }

    public function aruskasReport($periode, $bulan, $tanggal, $tahun): void
    {
        try {
            require_once BASE_PATH . '/models/AccountMutation.php';
            $mutations = new AccountMutation();

            $where = $this->getPeriodWhere($periode, $tanggal, $bulan, $tahun);

            $cashflow = $mutations->query("
                SELECT 
                    DATE(tanggal) as date,
                    fund_category,
                    SUM(CASE WHEN entry_type = 'debet' THEN amount ELSE 0 END) as inflow,
                    SUM(CASE WHEN entry_type = 'kredit' THEN amount ELSE 0 END) as outflow,
                    SUM(CASE WHEN entry_type = 'debet' THEN amount ELSE -amount END) as net
                FROM account_mutations 
                WHERE {$where}
                GROUP BY DATE(tanggal), fund_category 
                ORDER BY date DESC
            ", []);

            $settings = $this->getReportSettings();

            $total_inflow = array_sum(array_column($cashflow, 'inflow'));
            $total_outflow = array_sum(array_column($cashflow, 'outflow'));
            $net_cash = array_sum(array_column($cashflow, 'net'));

            $this->renderPage('laporan/arus-kas', [
                'pageTitle' => 'Laporan Arus Kas',
                'cashflow' => $cashflow,
                'periode' => $periode,
                'settings' => $settings,
                'total_inflow' => $total_inflow,
                'total_outflow' => $total_outflow,
                'net_cash' => $net_cash
            ]);
        } catch (Exception $e) {
            $this->setFlash('error', 'Error loading Arus Kas: ' . $e->getMessage());
            $this->redirect('laporan');
        }
    }

    private function kastunaiReport($periode, $bulan, $tanggal, $tahun): void
    {
        require_once BASE_PATH . '/models/CashAccount.php';
        require_once BASE_PATH . '/models/AccountMutation.php';

        $cash = new CashAccount();
        $mutations = new AccountMutation();

        $where = $this->getPeriodWhere($periode, $tanggal, $bulan, $tahun);

        $accounts = $cash->getAllAccounts(true);
        $data = [];
        foreach ($accounts as $acc) {
            $data[$acc['id']] = [
                'account' => $acc,
                'mutations' => $mutations->query("
                    SELECT * FROM account_mutations 
                    WHERE account_type = 'cash' AND account_id = ? AND {$where}
                    ORDER BY tanggal DESC", [$acc['id']]),
                'balance' => $acc['saldo_saat_ini']
            ];
        }

        $settings = $this->getReportSettings();
        $this->renderPage('laporan/kas-tunai', [
            'pageTitle' => 'Laporan Kas Tunai',
            'accounts' => $data,
            'settings' => $settings
        ]);
    }

    private function kasbankReport($periode, $bulan, $tanggal, $tahun): void
    {
        require_once BASE_PATH . '/models/BankAccount.php';
        require_once BASE_PATH . '/models/BankMonthlyPosting.php';
        require_once BASE_PATH . '/models/AccountMutation.php';

        $bank = new BankAccount();
        $posting = new BankMonthlyPosting();
        $mutations = new AccountMutation();

        $where = $this->getPeriodWhere($periode, $tanggal, $bulan, $tahun);

        $accounts = $bank->getAllAccounts(true);
        $postings = $posting->getRecent(50); // Recent postings
        $data = [];
        foreach ($accounts as $acc) {
            $data[$acc['id']] = [
                'account' => $acc,
                'monthly_postings' => $posting->query("SELECT * FROM bank_monthly_postings WHERE bank_account_id = ? ORDER BY executed_at DESC LIMIT 12", [$acc['id']]),
                'mutations' => $mutations->query("SELECT * FROM account_mutations WHERE account_type = 'bank' AND account_id = ? AND {$where}", [$acc['id']])
            ];
        }

        $settings = $this->getReportSettings();
        $this->renderPage('laporan/kas-bank', [
            'pageTitle' => 'Laporan Kas Bank',
            'accounts' => $data,
            'postings' => $postings,
            'settings' => $settings
        ]);
    }

    private function posisidanaReport($periode, $bulan, $tanggal, $tahun): void
    {
        require_once BASE_PATH . '/models/AccountMutation.php';
        $mutations = new AccountMutation();

        $endDate = $this->getEndDate($periode, $tanggal, $bulan, $tahun);
        $positions = $mutations->getNetByFundCategory($endDate);

        $settings = $this->getReportSettings();
        $this->renderPage('laporan/posisi-dana', [
            'pageTitle' => 'Laporan Posisi Dana',
            'positions' => $positions,
            'endDate' => $endDate,
            'settings' => $settings,
            'grand_total' => array_sum(array_column($positions, 'total'))
        ]);
    }

    private function realisasikegiatanReport($periode, $bulan, $tanggal, $tahun): void
    {
        require_once BASE_PATH . '/models/Kegiatan.php';
        require_once BASE_PATH . '/models/KegiatanPemasukan.php';
        require_once BASE_PATH . '/models/KegiatanPengeluaran.php';

        $kegiatan = new Kegiatan();
        $kegPemasukan = new KegiatanPemasukan();
        $kegPengeluaran = new KegiatanPengeluaran();

        $where = $this->getPeriodWhere($periode, $tanggal, $bulan, $tahun);

        $kegiatans = $kegiatan->query("SELECT * FROM kegiatan WHERE status = 'aktif'");
        $data = [];
        foreach ($kegiatans as $keg) {
            $in = $kegPemasukan->query("SELECT SUM(jumlah) total FROM kegiatan_pemasukan WHERE kegiatan_id = ? AND {$where}", [$keg['id']])[0]['total'] ?? 0;
            $out = $kegPengeluaran->query("SELECT SUM(jumlah) total FROM kegiatan_pengeluaran WHERE kegiatan_id = ? AND {$where}", [$keg['id']])[0]['total'] ?? 0;
            $data[] = [
                'kegiatan' => $keg,
                'pemasukan' => (float) $in,
                'pengeluaran' => (float) $out,
                'sisa' => (float) $keg['jumlah_anggaran'] - (float) $out + (float) $in
            ];
        }

        $settings = $this->getReportSettings();
        $this->renderPage('laporan/realisasi-kegiatan', [
            'pageTitle' => 'Realisasi Anggaran Kegiatan',
            'data' => $data,
            'settings' => $settings
        ]);
    }

    private function programdonasiReport($periode, $bulan, $tanggal, $tahun): void
    {
        require_once BASE_PATH . '/models/Donasi.php';
        require_once BASE_PATH . '/models/DonasiPemasukan.php';
        require_once BASE_PATH . '/models/DonasiPengeluaran.php';

        $where = $this->getPeriodWhere($periode, $tanggal, $bulan, $tahun);
        $programDonasi = new Donasi();
        $donasis = $programDonasi->query("SELECT * FROM program_donasi WHERE status = 'aktif'");
        $data = [];
        foreach ($donasis as $donasi) {
            $in = (new DonasiPemasukan())->query("SELECT SUM(jumlah) total FROM donasi_pemasukan WHERE program_id = ? AND {$where}", [$donasi['id']])[0]['total'] ?? 0;
            $out = (new DonasiPengeluaran())->query("SELECT SUM(jumlah) total FROM donasi_pengeluaran WHERE program_id = ? AND {$where}", [$donasi['id']])[0]['total'] ?? 0;
            $progress = min(100, ($in / $donasi['target_nominal']) * 100);
            $data[] = [
                'donasi' => $donasi,
                'pemasukan' => (float) $in,
                'pengeluaran' => (float) $out,
                'progress' => $progress,
                'sisa_target' => $donasi['target_nominal'] - $in
            ];
        }

        $settings = $this->getReportSettings();
        $this->renderPage('laporan/program-donasi', [
            'pageTitle' => 'Laporan Program Donasi',
            'data' => $data,
            'settings' => $settings
        ]);
    }

    private function periodikReport($periode, $bulan, $tanggal, $tahun): void
    {
        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/BankAccount.php';
        require_once BASE_PATH . '/models/CashAccount.php';
        require_once BASE_PATH . '/models/AccountMutation.php';

        $pemasukanModel  = new Pemasukan();
        $pengeluaranModel = new Pengeluaran();
        $bankAccount     = new BankAccount();
        $cashAccount     = new CashAccount();

        $filters = [];
        $reportEndDate = date('Y-m-d');

        switch ($periode) {
            case 'harian':
                $filters['tanggal_dari'] = $tanggal;
                $filters['tanggal_sampai'] = $tanggal;
                $reportEndDate = $tanggal;
                break;
            case 'bulanan':
                $filters['bulan'] = $bulan;
                $reportEndDate = date('Y-m-t', strtotime($bulan . '-01'));
                break;
            case 'tahunan':
                $filters['tanggal_dari'] = $tahun . '-01-01';
                $filters['tanggal_sampai'] = $tahun . '-12-31';
                $reportEndDate = $tahun . '-12-31';
                break;
        }

        $dataPemasukan  = $pemasukanModel->getAllWithRelations(0, 0, $filters);
        $dataPengeluaran = $pengeluaranModel->getAllWithRelations(0, 0, $filters);

        $totalMasuk  = array_sum(array_column($dataPemasukan, 'jumlah'));
        $totalKeluar = array_sum(array_column($dataPengeluaran, 'jumlah'));
        $saldo       = $totalMasuk - $totalKeluar;

        $bankPosition = $bankAccount->getAllWithPosition($reportEndDate);
        $cashPosition = $cashAccount->getAllWithPosition($reportEndDate);

        $totalBankPosition = array_sum(array_column($bankPosition, 'saldo_posisi'));
        $totalCashPosition = array_sum(array_column($cashPosition, 'saldo_posisi'));

        // Build human-readable period label
        $months = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        if ($periode === 'harian') {
            $periodeLabel = 'Tanggal ' . date('d', strtotime($tanggal)) . ' ' . ($months[(int)date('m', strtotime($tanggal))] ?? '') . ' ' . date('Y', strtotime($tanggal));
        } elseif ($periode === 'bulanan') {
            $parts = explode('-', $bulan);
            $periodeLabel = ($months[(int)($parts[1] ?? 1)] ?? '') . ' ' . ($parts[0] ?? $tahun);
        } else {
            $periodeLabel = 'Tahun ' . $tahun;
        }

        $settings = $this->getReportSettings();
        $this->renderPage('laporan/periodik', [
            'pageTitle'          => 'Laporan Periodik ' . ucfirst($periode),
            'periode'            => $periode,
            'periodeLabel'       => $periodeLabel,
            'bulan'              => $bulan,
            'tahun'              => $tahun,
            'tanggal'            => $tanggal,
            'endDate'            => $reportEndDate,
            'dataPemasukan'      => $dataPemasukan,
            'dataPengeluaran'    => $dataPengeluaran,
            'totalMasuk'         => $totalMasuk,
            'totalKeluar'        => $totalKeluar,
            'saldo'              => $saldo,
            'bankPosition'       => $bankPosition,
            'cashPosition'       => $cashPosition,
            'totalBankPosition'  => $totalBankPosition,
            'totalCashPosition'  => $totalCashPosition,
            'settings'           => $settings,
        ]);
    }

    private function getPeriodWhere($periode, $tanggal, $bulan, $tahun): string
    {
        switch ($periode) {
            case 'harian':
                return "tanggal = '{$tanggal}'";
            case 'bulanan':
                return "DATE_FORMAT(tanggal, '%Y-%m') = '{$bulan}'";
            case 'tahunan':
                return "YEAR(tanggal) = {$tahun}";
            default:
                return '1=1';
        }
    }

    private function getEndDate($periode, $tanggal, $bulan, $tahun): string
    {
        switch ($periode) {
            case 'harian':
                return $tanggal;
            case 'bulanan':
                return date('Y-m-t', strtotime($bulan . '-01'));
            case 'tahunan':
                return $tahun . '-12-31';
            default:
                return date('Y-m-d');
        }
    }

    public function exportCsv(): void
    {
        // Existing CSV...
    }
}
?>