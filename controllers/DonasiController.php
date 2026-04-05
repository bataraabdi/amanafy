<?php
class DonasiController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Donasi.php';
        $model = new Donasi();
        $stats = $model->getDashboardStats();
        $list = $model->getAllWithStats();
        $this->renderPage('donasi/index', [
            'pageTitle' => 'Program Donasi',
            'stats' => $stats,
            'list' => $list,
        ]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Donasi.php';
        $model = new Donasi();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $result = $this->prepareProgramSubmission($model);

            if (!$result['ok']) {
                $this->setFlash('error', $result['errors'][0] ?? 'Data program donasi belum lengkap.');
                $this->renderPage('donasi/create', [
                    'pageTitle' => 'Tambah Program Donasi',
                    'formData' => $result['formData'],
                ]);
                return;
            }

            $id = $model->create($result['data']);
            AuditLogger::log('CREATE', 'program_donasi', $id, null, $result['data']);
            $this->setFlash('success', 'Program donasi berhasil ditambahkan.');
            $this->redirect('donasi');
            return;
        }

        $this->renderPage('donasi/create', [
            'pageTitle' => 'Tambah Program Donasi',
            'formData' => $this->defaultFormData(),
        ]);
    }

    public function edit(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Donasi.php';
        $model = new Donasi();
        $record = $model->findById((int)$id);
        if (!$record) {
            $this->redirect('donasi');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $result = $this->prepareProgramSubmission($model, $record);

            if (!$result['ok']) {
                $this->setFlash('error', $result['errors'][0] ?? 'Data program donasi belum lengkap.');
                $this->renderPage('donasi/edit', [
                    'pageTitle' => 'Edit Program Donasi',
                    'record' => array_merge($record, $result['formData']),
                ]);
                return;
            }

            $old = $record;
            $model->update((int)$id, $result['data']);
            $this->cleanupStoredFiles($result['deleteAfterSave']);
            AuditLogger::log('UPDATE', 'program_donasi', (int)$id, $old, $result['data']);
            $this->setFlash('success', 'Program donasi berhasil diperbarui.');
            $this->redirect('donasi');
            return;
        }

        $this->renderPage('donasi/edit', [
            'pageTitle' => 'Edit Program Donasi',
            'record' => array_merge($record, [
                'dokumentasi_files' => decodeJsonArray($record['dokumentasi_files'] ?? null),
                'share_url' => !empty($record['slug']) ? donasiPublicUrl($record) : '',
            ]),
        ]);
    }

    public function delete(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Donasi.php';
            $model = new Donasi();
            $old = $model->findById((int)$id);

            if ($old) {
                $model->delete((int)$id);
                $this->cleanupStoredFiles($this->collectStoredFiles($old));
                AuditLogger::log('DELETE', 'program_donasi', (int)$id, $old, null);
                $this->setFlash('success', 'Program donasi berhasil dihapus.');
            }
        }
        $this->redirect('donasi');
    }

    public function detail(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Donasi.php';
        require_once BASE_PATH . '/models/DonasiPemasukan.php';
        require_once BASE_PATH . '/models/DonasiPengeluaran.php';
        require_once BASE_PATH . '/core/FinancialService.php';

        $model = new Donasi();
        $record = $model->getWithStats((int)$id);
        if (!$record) {
            $this->redirect('donasi');
            return;
        }

        $financialService = new FinancialService();

        $this->renderPage('donasi/detail', [
            'pageTitle' => 'Detail Donasi',
            'record' => $record,
            'shareUrl' => donasiPublicUrl($record),
            'dokumentasiFiles' => decodeJsonArray($record['dokumentasi_files'] ?? null),
            'pemasukanList' => (new DonasiPemasukan())->getByProgram((int)$id),
            'pengeluaranList' => (new DonasiPengeluaran())->getByProgram((int)$id),
            'accountOptions' => $financialService->getAccountOptions(true),
        ]);
    }

    public function tambahPemasukan(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/DonasiPemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new DonasiPemasukan();
            $financialService = new FinancialService();
            $db = $model->getDb();
            $uploadedFile = '';

            try {
                $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pemasukan donasi harus lebih dari nol.');
                }

                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'program_id' => (int)$id,
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'uraian' => trim($_POST['uraian'] ?? ''),
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
                $newId = $model->create($data);
                $financialService->syncMutations('donasi_pemasukan', $newId, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pemasukan donasi',
                    'user_id' => $data['user_id'],
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'debet',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Debet pemasukan donasi',
                ]]);

                AuditLogger::log('CREATE', 'donasi_pemasukan', $newId, null, $data);
                $db->commit();
                $this->setFlash('success', 'Pemasukan donasi berhasil ditambahkan.');
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
            }
        }
        $this->redirect('donasi/detail/' . $id);
    }

    public function tambahPengeluaran(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/DonasiPengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new DonasiPengeluaran();
            $financialService = new FinancialService();
            $db = $model->getDb();
            $uploadedFile = '';

            try {
                $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pengeluaran donasi harus lebih dari nol.');
                }

                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'program_id' => (int)$id,
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'jumlah' => $jumlah,
                    'uraian' => trim($_POST['uraian'] ?? ''),
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
                $newId = $model->create($data);
                $financialService->syncMutations('donasi_pengeluaran', $newId, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pengeluaran donasi',
                    'user_id' => $data['user_id'],
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'kredit',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Kredit pengeluaran donasi',
                ]]);

                AuditLogger::log('CREATE', 'donasi_pengeluaran', $newId, null, $data);
                $db->commit();
                $this->setFlash('success', 'Pengeluaran donasi berhasil ditambahkan.');
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
            }
        }
        $this->redirect('donasi/detail/' . $id);
    }

    public function updatePemasukan(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/DonasiPemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new DonasiPemasukan();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if (!$old) {
                $this->redirect('donasi');
                return;
            }

            $db = $model->getDb();
            $uploadedFile = '';
            $oldFileToDelete = '';

            try {
                $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pemasukan donasi harus lebih dari nol.');
                }

                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'uraian' => trim($_POST['uraian'] ?? ''),
                    'jumlah' => $jumlah,
                    'metode_pembayaran' => ($account['account_type'] === 'bank' ? 'transfer' : 'tunai'),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                ];
                if (!empty($_FILES['bukti_transfer']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_transfer'], UPLOAD_BUKTI, ['jpg', 'jpeg', 'png', 'webp', 'pdf']) ?? '';
                    if ($uploadedFile !== '') {
                        $oldFileToDelete = $old['bukti_transfer'] ?? '';
                        $data['bukti_transfer'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $model->update((int)$id, $data);
                $financialService->syncMutations('donasi_pemasukan', (int)$id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pemasukan donasi',
                    'user_id' => Auth::id(),
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'debet',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Debet pemasukan donasi',
                ]]);

                AuditLogger::log('UPDATE', 'donasi_pemasukan', (int)$id, $old, $data);
                $db->commit();
                if ($oldFileToDelete !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $oldFileToDelete);
                }
                $this->setFlash('success', 'Pemasukan donasi berhasil diperbarui.');
                $this->redirect('donasi/detail/' . $old['program_id']);
                return;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
                $this->redirect('donasi/detail/' . $old['program_id']);
                return;
            }
        }
    }

    public function deletePemasukan(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/DonasiPemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new DonasiPemasukan();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if ($old) {
                $db = $model->getDb();
                try {
                    $db->beginTransaction();
                    $financialService->removeMutations('donasi_pemasukan', (int)$id);
                    $model->delete((int)$id);
                    AuditLogger::log('DELETE', 'donasi_pemasukan', (int)$id, $old, null);
                    $db->commit();
                    if (!empty($old['bukti_transfer'])) {
                        $this->deleteFile(UPLOAD_BUKTI . $old['bukti_transfer']);
                    }
                    $this->setFlash('success', 'Pemasukan donasi berhasil dihapus.');
                    $this->redirect('donasi/detail/' . $old['program_id']);
                    return;
                } catch (Throwable $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        $this->redirect('donasi');
    }

    public function updatePengeluaran(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/DonasiPengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new DonasiPengeluaran();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if (!$old) {
                $this->redirect('donasi');
                return;
            }

            $db = $model->getDb();
            $uploadedFile = '';
            $oldFileToDelete = '';

            try {
                $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pengeluaran donasi harus lebih dari nol.');
                }

                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'jumlah' => $jumlah,
                    'uraian' => trim($_POST['uraian'] ?? ''),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                ];
                if (!empty($_FILES['bukti_nota']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_nota'], UPLOAD_BUKTI, ['jpg', 'jpeg', 'png', 'webp', 'pdf']) ?? '';
                    if ($uploadedFile !== '') {
                        $oldFileToDelete = $old['bukti_nota'] ?? '';
                        $data['bukti_nota'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $model->update((int)$id, $data);
                $financialService->syncMutations('donasi_pengeluaran', (int)$id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pengeluaran donasi',
                    'user_id' => Auth::id(),
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'kredit',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Kredit pengeluaran donasi',
                ]]);

                AuditLogger::log('UPDATE', 'donasi_pengeluaran', (int)$id, $old, $data);
                $db->commit();
                if ($oldFileToDelete !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $oldFileToDelete);
                }
                $this->setFlash('success', 'Pengeluaran donasi berhasil diperbarui.');
                $this->redirect('donasi/detail/' . $old['program_id']);
                return;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
                $this->redirect('donasi/detail/' . $old['program_id']);
                return;
            }
        }
    }

    public function deletePengeluaran(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/DonasiPengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new DonasiPengeluaran();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if ($old) {
                $db = $model->getDb();
                try {
                    $db->beginTransaction();
                    $financialService->removeMutations('donasi_pengeluaran', (int)$id);
                    $model->delete((int)$id);
                    AuditLogger::log('DELETE', 'donasi_pengeluaran', (int)$id, $old, null);
                    $db->commit();
                    if (!empty($old['bukti_nota'])) {
                        $this->deleteFile(UPLOAD_BUKTI . $old['bukti_nota']);
                    }
                    $this->setFlash('success', 'Pengeluaran donasi berhasil dihapus.');
                    $this->redirect('donasi/detail/' . $old['program_id']);
                    return;
                } catch (Throwable $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        $this->redirect('donasi');
    }

    private function defaultFormData(): array {
        return [
            'nama_donasi' => '',
            'slug' => '',
            'target_nominal' => '',
            'deskripsi_lengkap' => '',
            'bank_nama' => '',
            'no_rekening' => '',
            'atas_nama_rekening' => '',
            'qris_file' => '',
            'deadline' => '',
            'lokasi_kegiatan' => '',
            'dokumentasi_files' => [],
            'flyer_file' => '',
            'nomor_kontak' => '',
            'status' => 'aktif',
            'share_url' => '',
        ];
    }

    private function prepareProgramSubmission(Donasi $model, ?array $record = null): array {
        $ignoreId = (int)($record['id'] ?? 0);
        $title = trim($_POST['nama_donasi'] ?? '');
        $manualSlug = trim($_POST['slug'] ?? '');
        $slug = $manualSlug !== ''
            ? slugify($manualSlug)
            : $model->generateUniqueSlug($title !== '' ? $title : 'program-donasi', $ignoreId);
        $target = max(0, $this->normalizeAmount($_POST['target_nominal'] ?? '0'));
        $description = sanitizeRichText($_POST['deskripsi_lengkap'] ?? '');
        $bankNama = trim($_POST['bank_nama'] ?? '');
        $noRekening = trim($_POST['no_rekening'] ?? '');
        $atasNama = trim($_POST['atas_nama_rekening'] ?? '');
        $deadline = trim($_POST['deadline'] ?? '');
        $lokasi = trim($_POST['lokasi_kegiatan'] ?? '');
        $nomorKontak = trim($_POST['nomor_kontak'] ?? '');
        $status = ($_POST['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';

        $existingDokumentasi = decodeJsonArray($record['dokumentasi_files'] ?? null);
        $hapusDokumentasi = array_values(array_intersect(
            $existingDokumentasi,
            array_map('strval', $_POST['hapus_dokumentasi'] ?? [])
        ));

        $formData = [
            'nama_donasi' => $title,
            'slug' => $slug,
            'target_nominal' => trim($_POST['target_nominal'] ?? ''),
            'deskripsi_lengkap' => $description,
            'bank_nama' => $bankNama,
            'no_rekening' => $noRekening,
            'atas_nama_rekening' => $atasNama,
            'qris_file' => $record['qris_file'] ?? '',
            'deadline' => $deadline,
            'lokasi_kegiatan' => $lokasi,
            'dokumentasi_files' => array_values(array_diff($existingDokumentasi, $hapusDokumentasi)),
            'flyer_file' => $record['flyer_file'] ?? '',
            'nomor_kontak' => $nomorKontak,
            'status' => $status,
            'share_url' => $slug !== '' ? BASE_URL . '/publik/donasi/' . $slug : '',
        ];

        $errors = [];
        if ($title === '') {
            $errors[] = 'Judul program wajib diisi.';
        }
        if ($description === '') {
            $errors[] = 'Deskripsi lengkap wajib diisi.';
        }
        if ($target <= 0) {
            $errors[] = 'Target dana harus lebih dari nol.';
        }
        if ($slug === '') {
            $errors[] = 'Link publik belum valid. Silakan isi judul atau slug yang benar.';
        } elseif ($model->slugExists($slug, $ignoreId)) {
            $errors[] = 'Link publik sudah digunakan oleh program lain. Silakan ubah slug.';
        }
        if ($bankNama === '') {
            $errors[] = 'Nama bank wajib diisi.';
        }
        if ($noRekening === '') {
            $errors[] = 'Nomor rekening wajib diisi.';
        }
        if ($atasNama === '') {
            $errors[] = 'Atas nama rekening wajib diisi.';
        }
        if ($lokasi === '') {
            $errors[] = 'Lokasi kegiatan wajib diisi.';
        }
        if ($nomorKontak === '') {
            $errors[] = 'Nomor kontak wajib diisi.';
        }
        if ($deadline !== '' && !$this->isValidDate($deadline)) {
            $errors[] = 'Format deadline tidak valid.';
        }

        if (!empty($errors)) {
            return [
                'ok' => false,
                'errors' => $errors,
                'formData' => $formData,
                'data' => [],
                'deleteAfterSave' => [],
            ];
        }

        $uploadedNow = [];
        $deleteAfterSave = [];

        $qrisFile = $record['qris_file'] ?? '';
        $qrisUpload = $this->uploadSingleAsset(
            $_FILES['qris_file'] ?? [],
            UPLOAD_QRIS,
            ['jpg', 'jpeg', 'png', 'webp'],
            'file QRIS'
        );
        if (!empty($qrisUpload['error'])) {
            return $this->failedProgramSubmission($formData, $qrisUpload['error'], $uploadedNow);
        }
        if (!empty($qrisUpload['file'])) {
            $qrisFile = $qrisUpload['file'];
            $uploadedNow[] = ['dir' => UPLOAD_QRIS, 'file' => $qrisUpload['file']];
            if (!empty($record['qris_file'])) {
                $deleteAfterSave[] = ['dir' => UPLOAD_QRIS, 'file' => $record['qris_file']];
            }
        }

        $flyerFile = $record['flyer_file'] ?? '';
        $flyerUpload = $this->uploadSingleAsset(
            $_FILES['flyer_file'] ?? [],
            UPLOAD_DONASI,
            ['jpg', 'jpeg', 'png', 'webp', 'pdf'],
            'file flyer'
        );
        if (!empty($flyerUpload['error'])) {
            return $this->failedProgramSubmission($formData, $flyerUpload['error'], $uploadedNow);
        }
        if (!empty($flyerUpload['file'])) {
            $flyerFile = $flyerUpload['file'];
            $uploadedNow[] = ['dir' => UPLOAD_DONASI, 'file' => $flyerUpload['file']];
            if (!empty($record['flyer_file'])) {
                $deleteAfterSave[] = ['dir' => UPLOAD_DONASI, 'file' => $record['flyer_file']];
            }
        }

        $documentationFiles = array_values(array_diff($existingDokumentasi, $hapusDokumentasi));
        $documentationUpload = $this->uploadMultipleAssets(
            $_FILES['dokumentasi_files'] ?? [],
            UPLOAD_DONASI,
            ['jpg', 'jpeg', 'png', 'webp', 'mp4', 'mov', 'avi', 'mkv', 'webm'],
            'dokumentasi'
        );
        if (!empty($documentationUpload['error'])) {
            return $this->failedProgramSubmission($formData, $documentationUpload['error'], $uploadedNow);
        }
        foreach ($documentationUpload['files'] as $file) {
            $documentationFiles[] = $file;
            $uploadedNow[] = ['dir' => UPLOAD_DONASI, 'file' => $file];
        }

        foreach ($hapusDokumentasi as $file) {
            $deleteAfterSave[] = ['dir' => UPLOAD_DONASI, 'file' => $file];
        }

        $documentationFiles = array_values(array_unique($documentationFiles));
        $coverImage = $this->resolveCoverImage(
            $documentationFiles,
            $record['gambar'] ?? '',
            $hapusDokumentasi
        );

        if ($flyerFile !== '' && isImageFile($flyerFile)) {
            $coverImage = $flyerFile;
        }

        $data = [
            'nama_donasi' => $title,
            'slug' => $slug,
            'target_nominal' => $target,
            'uraian' => truncate(htmlToPlainText($description), 220),
            'deskripsi_lengkap' => $description,
            'rekening_bank' => $this->buildLegacyBankText($bankNama, $noRekening, $atasNama),
            'bank_nama' => $bankNama,
            'no_rekening' => $noRekening,
            'atas_nama_rekening' => $atasNama,
            'qris_file' => $qrisFile,
            'deadline' => $deadline !== '' ? $deadline : null,
            'lokasi_kegiatan' => $lokasi,
            'dokumentasi_files' => json_encode($documentationFiles, JSON_UNESCAPED_UNICODE),
            'flyer_file' => $flyerFile,
            'nomor_kontak' => $nomorKontak,
            'status' => $status,
            'gambar' => $coverImage,
        ];

        $formData['qris_file'] = $qrisFile;
        $formData['flyer_file'] = $flyerFile;
        $formData['dokumentasi_files'] = $documentationFiles;

        return [
            'ok' => true,
            'errors' => [],
            'formData' => $formData,
            'data' => $data,
            'deleteAfterSave' => $deleteAfterSave,
        ];
    }

    private function failedProgramSubmission(array $formData, string $error, array $uploadedNow): array {
        $this->cleanupStoredFiles($uploadedNow);
        return [
            'ok' => false,
            'errors' => [$error],
            'formData' => $formData,
            'data' => [],
            'deleteAfterSave' => [],
        ];
    }

    private function uploadSingleAsset(array $file, string $destination, array $allowedTypes, string $label): array {
        if (empty($file['name'])) {
            return ['file' => '', 'error' => null];
        }

        $filename = $this->uploadFile($file, $destination, $allowedTypes);
        if (!$filename) {
            return [
                'file' => '',
                'error' => 'Upload ' . $label . ' gagal. Pastikan format file sesuai dan ukuran maksimal 10MB.',
            ];
        }

        return ['file' => $filename, 'error' => null];
    }

    private function uploadMultipleAssets(array $files, string $destination, array $allowedTypes, string $label): array {
        if (empty($files['name']) || !is_array($files['name'])) {
            return ['files' => [], 'error' => null];
        }

        $uploadedFiles = [];
        foreach ($files['name'] as $index => $name) {
            if (trim((string)$name) === '') {
                continue;
            }

            $singleFile = [
                'name' => $files['name'][$index] ?? '',
                'type' => $files['type'][$index] ?? '',
                'tmp_name' => $files['tmp_name'][$index] ?? '',
                'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $files['size'][$index] ?? 0,
            ];

            $result = $this->uploadSingleAsset(
                $singleFile,
                $destination,
                $allowedTypes,
                $label . ' #' . ($index + 1)
            );

            if (!empty($result['error'])) {
                $this->cleanupStoredFiles(array_map(fn($file) => ['dir' => $destination, 'file' => $file], $uploadedFiles));
                return ['files' => [], 'error' => $result['error']];
            }

            if (!empty($result['file'])) {
                $uploadedFiles[] = $result['file'];
            }
        }

        return ['files' => $uploadedFiles, 'error' => null];
    }

    private function cleanupStoredFiles(array $files): void {
        $seen = [];
        foreach ($files as $item) {
            $dir = $item['dir'] ?? '';
            $file = $item['file'] ?? '';
            if ($dir === '' || $file === '') {
                continue;
            }

            $key = $dir . '|' . $file;
            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $this->deleteFile($dir . $file);
        }
    }

    private function collectStoredFiles(array $record): array {
        $files = [];

        foreach (decodeJsonArray($record['dokumentasi_files'] ?? null) as $file) {
            $files[] = ['dir' => UPLOAD_DONASI, 'file' => $file];
        }

        if (!empty($record['flyer_file'])) {
            $files[] = ['dir' => UPLOAD_DONASI, 'file' => $record['flyer_file']];
        }

        if (!empty($record['gambar'])) {
            $files[] = ['dir' => UPLOAD_DONASI, 'file' => $record['gambar']];
        }

        if (!empty($record['qris_file'])) {
            $files[] = ['dir' => UPLOAD_QRIS, 'file' => $record['qris_file']];
        }

        return $files;
    }

    private function resolveCoverImage(array $documentationFiles, string $currentCover = '', array $removedFiles = []): ?string {
        if ($currentCover !== '' && !in_array($currentCover, $removedFiles, true) && isImageFile($currentCover)) {
            return $currentCover;
        }

        foreach ($documentationFiles as $file) {
            if (isImageFile($file)) {
                return $file;
            }
        }

        if ($currentCover !== '' && !in_array($currentCover, $removedFiles, true)) {
            return $currentCover;
        }

        return null;
    }

    private function buildLegacyBankText(string $bankNama, string $noRekening, string $atasNama): string {
        $parts = [];
        if ($bankNama !== '') {
            $parts[] = $bankNama;
        }
        if ($noRekening !== '') {
            $parts[] = 'No. Rek ' . $noRekening;
        }
        if ($atasNama !== '') {
            $parts[] = 'a.n. ' . $atasNama;
        }

        return implode(' | ', $parts);
    }

    private function normalizeAmount(string $value): float {
        $normalized = trim($value);
        if ($normalized === '') {
            return 0;
        }

        $normalized = str_replace('.', '', $normalized);
        $normalized = str_replace(',', '.', $normalized);

        return (float)$normalized;
    }

    private function isValidDate(string $value): bool {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        return $date instanceof DateTime && $date->format('Y-m-d') === $value;
    }
}
