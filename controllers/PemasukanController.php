<?php
class PemasukanController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Kategori.php';

        $model = new Pemasukan();
        $kategoriModel = new Kategori('pemasukan');

        $filters = [
            'bulan' => $_GET['bulan'] ?? '',
            'kategori_id' => $_GET['kategori_id'] ?? '',
            'tanggal_dari' => $_GET['tanggal_dari'] ?? '',
            'tanggal_sampai' => $_GET['tanggal_sampai'] ?? '',
            'fund_category' => $_GET['fund_category'] ?? '',
        ];

        $data = $model->getAllWithRelations(0, 0, $filters);
        $kategoriList = $kategoriModel->getAll();
        $total = $model->countFiltered($filters);

        $this->renderPage('pemasukan/index', [
            'pageTitle' => 'Pemasukan Kas',
            'dataList' => $data,
            'kategoriList' => $kategoriList,
            'filters' => $filters,
            'total' => $total,
        ]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Kategori.php';
        require_once BASE_PATH . '/models/Donatur.php';
        require_once BASE_PATH . '/core/FinancialService.php';

        $kategoriModel = new Kategori('pemasukan');
        $donaturModel = new Donatur();
        $financialService = new FinancialService();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $model = new Pemasukan();
            $db = $model->getDb();
            $uploadedFile = '';

            try {
                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $categoryRecord = $kategoriModel->findById((int)($_POST['kategori_id'] ?? 0));
                $fundCategory = $categoryRecord['fund_category'] ?? 'Tidak Terikat';
                $jumlah = normalizeAmountInput($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pemasukan harus lebih dari nol.');
                }

                $data = [
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'donatur_id' => !empty($_POST['donatur_id']) ? (int)$_POST['donatur_id'] : null,
                    'nama_donatur' => trim($_POST['nama_donatur_manual'] ?? ''),
                    'kategori_id' => (int)($_POST['kategori_id'] ?? 0),
                    'jumlah' => $jumlah,
                    'metode_pembayaran' => ($account['account_type'] === 'bank' ? 'transfer' : 'tunai'),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'user_id' => Auth::id(),
                ];

                if (!empty($_FILES['bukti_transfer']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_transfer'], UPLOAD_BUKTI) ?? '';
                    if ($uploadedFile !== '') {
                        $data['bukti_transfer'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $id = $model->create($data);
                $financialService->syncMutations('pemasukan', $id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pemasukan kas',
                    'user_id' => $data['user_id'],
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'debet',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Debet pemasukan kas',
                ]]);

                AuditLogger::log('CREATE', 'pemasukan', $id, null, $data);
                $db->commit();
                $this->setFlash('success', 'Pemasukan berhasil ditambahkan.');
                $this->redirect('pemasukan');
                return;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
                $this->redirectBack();
                return;
            }
        }

        $this->renderPage('pemasukan/create', [
            'pageTitle' => 'Tambah Pemasukan',
            'kategoriList' => $kategoriModel->getAll(),
            'donaturList' => $donaturModel->getAll(),
            'accountOptions' => $financialService->getAccountOptions(true),
        ]);
    }

    public function edit(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Pemasukan.php';
        require_once BASE_PATH . '/models/Kategori.php';
        require_once BASE_PATH . '/models/Donatur.php';
        require_once BASE_PATH . '/core/FinancialService.php';

        $model = new Pemasukan();
        $record = $model->findById((int)$id);
        if (!$record) {
            $this->redirect('pemasukan');
            return;
        }

        $kategoriModel = new Kategori('pemasukan');
        $donaturModel = new Donatur();
        $financialService = new FinancialService();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $db = $model->getDb();
            $oldData = $record;
            $uploadedFile = '';
            $oldFileToDelete = '';

            try {
                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $categoryRecord = $kategoriModel->findById((int)($_POST['kategori_id'] ?? 0));
                $fundCategory = $categoryRecord['fund_category'] ?? ($record['fund_category'] ?? 'Tidak Terikat');
                $jumlah = normalizeAmountInput($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pemasukan harus lebih dari nol.');
                }

                $data = [
                    'tanggal' => $_POST['tanggal'] ?? $record['tanggal'],
                    'donatur_id' => !empty($_POST['donatur_id']) ? (int)$_POST['donatur_id'] : null,
                    'nama_donatur' => trim($_POST['nama_donatur_manual'] ?? ''),
                    'kategori_id' => (int)($_POST['kategori_id'] ?? $record['kategori_id']),
                    'jumlah' => $jumlah,
                    'metode_pembayaran' => ($account['account_type'] === 'bank' ? 'transfer' : 'tunai'),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                ];

                if (!empty($_FILES['bukti_transfer']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_transfer'], UPLOAD_BUKTI) ?? '';
                    if ($uploadedFile !== '') {
                        $oldFileToDelete = $record['bukti_transfer'] ?? '';
                        $data['bukti_transfer'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $model->update((int)$id, $data);
                $financialService->syncMutations('pemasukan', (int)$id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pemasukan kas',
                    'user_id' => Auth::id(),
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'debet',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Debet pemasukan kas',
                ]]);

                AuditLogger::log('UPDATE', 'pemasukan', (int)$id, $oldData, $data);
                $db->commit();
                if ($oldFileToDelete !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $oldFileToDelete);
                }
                $this->setFlash('success', 'Pemasukan berhasil diperbarui.');
                $this->redirect('pemasukan');
                return;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
                $this->redirectBack();
                return;
            }
        }

        $this->renderPage('pemasukan/edit', [
            'pageTitle' => 'Edit Pemasukan',
            'record' => $record,
            'kategoriList' => $kategoriModel->getAll(),
            'donaturList' => $donaturModel->getAll(),
            'accountOptions' => $financialService->getAccountOptions(true),
        ]);
    }

    public function delete(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Pemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';

            $model = new Pemasukan();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);

            if ($old) {
                $db = $model->getDb();

                try {
                    $db->beginTransaction();
                    $financialService->removeMutations('pemasukan', (int)$id);
                    $model->delete((int)$id);
                    AuditLogger::log('DELETE', 'pemasukan', (int)$id, $old, null);
                    $db->commit();

                    if (!empty($old['bukti_transfer'])) {
                        $this->deleteFile(UPLOAD_BUKTI . $old['bukti_transfer']);
                    }

                    $this->setFlash('success', 'Pemasukan berhasil dihapus.');
                } catch (Throwable $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        $this->redirect('pemasukan');
    }
}
