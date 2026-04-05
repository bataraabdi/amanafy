<?php
class PengeluaranController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/Kategori.php';

        $model = new Pengeluaran();
        $kategoriModel = new Kategori('pengeluaran');

        $filters = [
            'bulan' => $_GET['bulan'] ?? '',
            'kategori_id' => $_GET['kategori_id'] ?? '',
            'tanggal_dari' => $_GET['tanggal_dari'] ?? '',
            'tanggal_sampai' => $_GET['tanggal_sampai'] ?? '',
            'fund_category' => $_GET['fund_category'] ?? '',
        ];

        $data = $model->getAllWithRelations(0, 0, $filters);

        $this->renderPage('pengeluaran/index', [
            'pageTitle' => 'Pengeluaran Kas',
            'dataList' => $data,
            'kategoriList' => $kategoriModel->getAll(),
            'filters' => $filters,
        ]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/Kategori.php';
        require_once BASE_PATH . '/core/FinancialService.php';

        $kategoriModel = new Kategori('pengeluaran');
        $financialService = new FinancialService();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $model = new Pengeluaran();
            $db = $model->getDb();
            $uploadedFile = '';

            try {
                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $categoryRecord = $kategoriModel->findById((int)($_POST['kategori_id'] ?? 0));
                $fundCategory = $categoryRecord['fund_category'] ?? 'Tidak Terikat';
                $jumlah = normalizeAmountInput($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pengeluaran harus lebih dari nol.');
                }

                $data = [
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'kategori_id' => (int)($_POST['kategori_id'] ?? 0),
                    'jumlah' => $jumlah,
                    'penerima' => trim($_POST['penerima'] ?? ''),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'user_id' => Auth::id(),
                ];

                if (!empty($_FILES['bukti_nota']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_nota'], UPLOAD_BUKTI) ?? '';
                    if ($uploadedFile !== '') {
                        $data['bukti_nota'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $id = $model->create($data);
                $financialService->syncMutations('pengeluaran', $id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pengeluaran kas',
                    'user_id' => $data['user_id'],
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'kredit',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Kredit pengeluaran kas',
                ]]);

                AuditLogger::log('CREATE', 'pengeluaran', $id, null, $data);
                $db->commit();
                $this->setFlash('success', 'Pengeluaran berhasil ditambahkan.');
                $this->redirect('pengeluaran');
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

        $this->renderPage('pengeluaran/create', [
            'pageTitle' => 'Tambah Pengeluaran',
            'kategoriList' => $kategoriModel->getAll(),
            'accountOptions' => $financialService->getAccountOptions(true),
        ]);
    }

    public function edit(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Pengeluaran.php';
        require_once BASE_PATH . '/models/Kategori.php';
        require_once BASE_PATH . '/core/FinancialService.php';

        $model = new Pengeluaran();
        $record = $model->findById((int)$id);
        if (!$record) {
            $this->redirect('pengeluaran');
            return;
        }

        $kategoriModel = new Kategori('pengeluaran');
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
                    throw new InvalidArgumentException('Jumlah pengeluaran harus lebih dari nol.');
                }

                $data = [
                    'tanggal' => $_POST['tanggal'] ?? $record['tanggal'],
                    'kategori_id' => (int)($_POST['kategori_id'] ?? $record['kategori_id']),
                    'jumlah' => $jumlah,
                    'penerima' => trim($_POST['penerima'] ?? ''),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                ];

                if (!empty($_FILES['bukti_nota']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_nota'], UPLOAD_BUKTI) ?? '';
                    if ($uploadedFile !== '') {
                        $oldFileToDelete = $record['bukti_nota'] ?? '';
                        $data['bukti_nota'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $model->update((int)$id, $data);
                $financialService->syncMutations('pengeluaran', (int)$id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pengeluaran kas',
                    'user_id' => Auth::id(),
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'kredit',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Kredit pengeluaran kas',
                ]]);

                AuditLogger::log('UPDATE', 'pengeluaran', (int)$id, $oldData, $data);
                $db->commit();
                if ($oldFileToDelete !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $oldFileToDelete);
                }
                $this->setFlash('success', 'Pengeluaran berhasil diperbarui.');
                $this->redirect('pengeluaran');
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

        $this->renderPage('pengeluaran/edit', [
            'pageTitle' => 'Edit Pengeluaran',
            'record' => $record,
            'kategoriList' => $kategoriModel->getAll(),
            'accountOptions' => $financialService->getAccountOptions(true),
        ]);
    }

    public function delete(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Pengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';

            $model = new Pengeluaran();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);

            if ($old) {
                $db = $model->getDb();

                try {
                    $db->beginTransaction();
                    $financialService->removeMutations('pengeluaran', (int)$id);
                    $model->delete((int)$id);
                    AuditLogger::log('DELETE', 'pengeluaran', (int)$id, $old, null);
                    $db->commit();

                    if (!empty($old['bukti_nota'])) {
                        $this->deleteFile(UPLOAD_BUKTI . $old['bukti_nota']);
                    }

                    $this->setFlash('success', 'Pengeluaran berhasil dihapus.');
                } catch (Throwable $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        $this->redirect('pengeluaran');
    }
}
