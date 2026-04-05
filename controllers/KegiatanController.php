<?php
class KegiatanController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Kegiatan.php';
        $model = new Kegiatan();
        $stats = $model->getDashboardStats();
        $list = $model->getAllWithStats();
        $this->renderPage('kegiatan/index', ['pageTitle' => 'Program Kegiatan', 'stats' => $stats, 'list' => $list]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Kegiatan.php';
            $model = new Kegiatan();
            $data = [
                'nama_kegiatan' => trim($_POST['nama_kegiatan'] ?? ''),
                'waktu_tempat' => trim($_POST['waktu_tempat'] ?? ''),
                'penanggung_jawab' => trim($_POST['penanggung_jawab'] ?? ''),
                'sumber_dana' => trim($_POST['sumber_dana'] ?? ''),
                'jumlah_anggaran' => max(0, $this->normalizeAmount($_POST['jumlah_anggaran'] ?? '0')),
                'status' => in_array($_POST['status'] ?? 'aktif', ['aktif', 'selesai', 'dibatalkan']) ? $_POST['status'] : 'aktif',
                'tampil_publik' => ((int)($_POST['tampil_publik'] ?? 1) === 1) ? 1 : 0,
                'keterangan' => trim($_POST['keterangan'] ?? ''),
            ];
            if (!empty($_FILES['gambar']['name'])) {
                $filename = $this->uploadFile($_FILES['gambar'], UPLOAD_KEGIATAN, ['jpg','jpeg','png','webp']);
                if ($filename) $data['gambar'] = $filename;
            }
            $id = $model->create($data);
            AuditLogger::log('CREATE', 'kegiatan', $id, null, $data);
            $this->setFlash('success', 'Kegiatan berhasil ditambahkan.');
            $this->redirect('kegiatan');
            return;
        }
        $this->renderPage('kegiatan/create', ['pageTitle' => 'Tambah Kegiatan']);
    }

    public function edit(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Kegiatan.php';
        $model = new Kegiatan();
        $record = $model->getWithStats((int)$id);
        if (!$record) { $this->redirect('kegiatan'); return; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $old = $record;
            $data = [
                'nama_kegiatan' => trim($_POST['nama_kegiatan'] ?? ''),
                'waktu_tempat' => trim($_POST['waktu_tempat'] ?? ''),
                'penanggung_jawab' => trim($_POST['penanggung_jawab'] ?? ''),
                'sumber_dana' => trim($_POST['sumber_dana'] ?? ''),
                'jumlah_anggaran' => max(0, $this->normalizeAmount($_POST['jumlah_anggaran'] ?? '0')),
                'status' => in_array($_POST['status'] ?? 'aktif', ['aktif', 'selesai', 'dibatalkan']) ? $_POST['status'] : 'aktif',
                'tampil_publik' => ((int)($_POST['tampil_publik'] ?? 1) === 1) ? 1 : 0,
                'keterangan' => trim($_POST['keterangan'] ?? ''),
            ];
            $totalAnggaranBaru = (float)$data['jumlah_anggaran'] + (float)($record['total_pemasukan'] ?? 0);
            if ($totalAnggaranBaru < (float)($record['total_pengeluaran'] ?? 0)) {
                $this->setFlash('error', 'Jumlah anggaran tidak boleh membuat total anggaran kegiatan lebih kecil dari total pengeluaran yang sudah tercatat.');
                $this->renderPage('kegiatan/edit', [
                    'pageTitle' => 'Edit Kegiatan',
                    'record' => array_merge($record, $data),
                ]);
                return;
            }
            if (!empty($_FILES['gambar']['name'])) {
                $filename = $this->uploadFile($_FILES['gambar'], UPLOAD_KEGIATAN, ['jpg','jpeg','png','webp']);
                if ($filename) {
                    if (!empty($record['gambar'])) $this->deleteFile(UPLOAD_KEGIATAN . $record['gambar']);
                    $data['gambar'] = $filename;
                }
            }
            $model->update((int)$id, $data);
            AuditLogger::log('UPDATE', 'kegiatan', (int)$id, $old, $data);
            $this->setFlash('success', 'Kegiatan berhasil diperbarui.');
            $this->redirect('kegiatan');
            return;
        }
        $this->renderPage('kegiatan/edit', ['pageTitle' => 'Edit Kegiatan', 'record' => $record]);
    }

    public function delete(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Kegiatan.php';
            $model = new Kegiatan();
            $old = $model->findById((int)$id);
            if ($old && !empty($old['gambar'])) $this->deleteFile(UPLOAD_KEGIATAN . $old['gambar']);
            $model->delete((int)$id);
            AuditLogger::log('DELETE', 'kegiatan', (int)$id, $old, null);
            $this->setFlash('success', 'Kegiatan berhasil dihapus.');
        }
        $this->redirect('kegiatan');
    }

    public function detail(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Kegiatan.php';
        require_once BASE_PATH . '/models/KegiatanPemasukan.php';
        require_once BASE_PATH . '/models/KegiatanPengeluaran.php';
        require_once BASE_PATH . '/core/FinancialService.php';
        $model = new Kegiatan();
        $record = $model->getWithStats((int)$id);
        if (!$record) { $this->redirect('kegiatan'); return; }
        $pemasukanModel = new KegiatanPemasukan();
        $pengeluaranModel = new KegiatanPengeluaran();
        $financialService = new FinancialService();
        $this->renderPage('kegiatan/detail', [
            'pageTitle' => 'Detail Kegiatan',
            'record' => $record,
            'pemasukanList' => $pemasukanModel->getByKegiatan((int)$id),
            'pengeluaranList' => $pengeluaranModel->getByKegiatan((int)$id),
            'accountOptions' => $financialService->getAccountOptions(true),
        ]);
    }

    public function tambahPemasukan(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/KegiatanPemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new KegiatanPemasukan();
            $financialService = new FinancialService();
            $db = $model->getDb();
            $uploadedFile = '';

            try {
                $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pemasukan kegiatan harus lebih dari nol.');
                }

                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'kegiatan_id' => (int)$id,
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
                $financialService->syncMutations('kegiatan_pemasukan', $newId, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pemasukan kegiatan',
                    'user_id' => $data['user_id'],
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'debet',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Debet pemasukan kegiatan',
                ]]);

                AuditLogger::log('CREATE', 'kegiatan_pemasukan', $newId, null, $data);
                $db->commit();
                $this->setFlash('success', 'Pemasukan kegiatan berhasil ditambahkan.');
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
        $this->redirect('kegiatan/detail/' . $id);
    }

    public function tambahPengeluaran(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Kegiatan.php';
            require_once BASE_PATH . '/models/KegiatanPengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $kegiatanModel = new Kegiatan();
            $model = new KegiatanPengeluaran();
            $financialService = new FinancialService();
            $kegiatan = $kegiatanModel->getWithStats((int)$id);
            if (!$kegiatan) {
                $this->setFlash('error', 'Data kegiatan tidak ditemukan.');
                $this->redirect('kegiatan');
                return;
            }

            $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
            $anggaran = (float)($kegiatan['total_anggaran'] ?? 0);
            $totalPengeluaran = (float)($kegiatan['total_pengeluaran'] ?? 0);
            if (($totalPengeluaran + $jumlah) > $anggaran) {
                $sisaAnggaran = max(0, $anggaran - $totalPengeluaran);
                $this->setFlash('error', 'Pengeluaran melebihi total anggaran kegiatan. Sisa anggaran tersedia: ' . rupiah($sisaAnggaran) . '.');
                $this->redirect('kegiatan/detail/' . $id);
                return;
            }

            $db = $model->getDb();
            $uploadedFile = '';

            try {
                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'kegiatan_id' => (int)$id,
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
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
                $newId = $model->create($data);
                $financialService->syncMutations('kegiatan_pengeluaran', $newId, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pengeluaran kegiatan',
                    'user_id' => $data['user_id'],
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'kredit',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Kredit pengeluaran kegiatan',
                ]]);

                AuditLogger::log('CREATE', 'kegiatan_pengeluaran', $newId, null, $data);
                $db->commit();
                $this->setFlash('success', 'Pengeluaran kegiatan berhasil ditambahkan.');
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
        $this->redirect('kegiatan/detail/' . $id);
    }

    public function updatePemasukan(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/KegiatanPemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new KegiatanPemasukan();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if (!$old) { $this->redirect('kegiatan'); return; }

            $db = $model->getDb();
            $uploadedFile = '';
            $oldFileToDelete = '';

            try {
                $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
                if ($jumlah <= 0) {
                    throw new InvalidArgumentException('Jumlah pemasukan kegiatan harus lebih dari nol.');
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
                    $uploadedFile = $this->uploadFile($_FILES['bukti_transfer'], UPLOAD_BUKTI, ['jpg','jpeg','png','webp','pdf']) ?? '';
                    if ($uploadedFile !== '') {
                        $oldFileToDelete = $old['bukti_transfer'] ?? '';
                        $data['bukti_transfer'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $model->update((int)$id, $data);
                $financialService->syncMutations('kegiatan_pemasukan', (int)$id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pemasukan kegiatan',
                    'user_id' => Auth::id(),
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'debet',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Debet pemasukan kegiatan',
                ]]);

                AuditLogger::log('UPDATE', 'kegiatan_pemasukan', (int)$id, $old, $data);
                $db->commit();
                if ($oldFileToDelete !== '') $this->deleteFile(UPLOAD_BUKTI . $oldFileToDelete);
                $this->setFlash('success', 'Pemasukan kegiatan berhasil diperbarui.');
                $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                return;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
                $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                return;
            }
        }
    }

    public function deletePemasukan(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/KegiatanPemasukan.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new KegiatanPemasukan();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if ($old) {
                $db = $model->getDb();
                try {
                    $db->beginTransaction();
                    $financialService->removeMutations('kegiatan_pemasukan', (int)$id);
                    $model->delete((int)$id);
                    AuditLogger::log('DELETE', 'kegiatan_pemasukan', (int)$id, $old, null);
                    $db->commit();
                    if (!empty($old['bukti_transfer'])) $this->deleteFile(UPLOAD_BUKTI . $old['bukti_transfer']);
                    $this->setFlash('success', 'Pemasukan kegiatan berhasil dihapus.');
                    $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                    return;
                } catch (Throwable $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        $this->redirect('kegiatan');
    }

    public function updatePengeluaran(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Kegiatan.php';
            require_once BASE_PATH . '/models/KegiatanPengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $kegiatanModel = new Kegiatan();
            $model = new KegiatanPengeluaran();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if (!$old) { $this->redirect('kegiatan'); return; }

            $kegiatan = $kegiatanModel->getWithStats((int)$old['kegiatan_id']);
            if (!$kegiatan) {
                $this->setFlash('error', 'Data kegiatan tidak ditemukan.');
                $this->redirect('kegiatan');
                return;
            }

            $jumlah = $this->normalizeAmount($_POST['jumlah'] ?? '0');
            $anggaran = (float)($kegiatan['total_anggaran'] ?? 0);
            $totalPengeluaranLain = (float)($kegiatan['total_pengeluaran'] ?? 0) - (float)($old['jumlah'] ?? 0);
            if (($totalPengeluaranLain + $jumlah) > $anggaran) {
                $sisaAnggaran = max(0, $anggaran - $totalPengeluaranLain);
                $this->setFlash('error', 'Pengeluaran melebihi total anggaran kegiatan. Sisa anggaran tersedia: ' . rupiah($sisaAnggaran) . '.');
                $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                return;
            }

            $db = $model->getDb();
            $uploadedFile = '';
            $oldFileToDelete = '';

            try {
                $account = $financialService->parseAccountReference($_POST['account_ref'] ?? '');
                $fundCategory = $financialService->validateFundCategory($_POST['fund_category'] ?? '');
                $data = [
                    'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                    'jumlah' => $jumlah,
                    'penerima' => trim($_POST['penerima'] ?? ''),
                    'keterangan' => trim($_POST['keterangan'] ?? ''),
                    'fund_category' => $fundCategory,
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                ];
                if (!empty($_FILES['bukti_nota']['name'])) {
                    $uploadedFile = $this->uploadFile($_FILES['bukti_nota'], UPLOAD_BUKTI, ['jpg','jpeg','png','webp','pdf']) ?? '';
                    if ($uploadedFile !== '') {
                        $oldFileToDelete = $old['bukti_nota'] ?? '';
                        $data['bukti_nota'] = $uploadedFile;
                    }
                }

                $db->beginTransaction();
                $model->update((int)$id, $data);
                $financialService->syncMutations('kegiatan_pengeluaran', (int)$id, [
                    'tanggal' => $data['tanggal'],
                    'fund_category' => $fundCategory,
                    'description' => 'Pengeluaran kegiatan',
                    'user_id' => Auth::id(),
                ], [[
                    'account_type' => $account['account_type'],
                    'account_id' => $account['account_id'],
                    'entry_type' => 'kredit',
                    'amount' => $jumlah,
                    'fund_category' => $fundCategory,
                    'description' => 'Kredit pengeluaran kegiatan',
                ]]);

                AuditLogger::log('UPDATE', 'kegiatan_pengeluaran', (int)$id, $old, $data);
                $db->commit();
                if ($oldFileToDelete !== '') $this->deleteFile(UPLOAD_BUKTI . $oldFileToDelete);
                $this->setFlash('success', 'Pengeluaran kegiatan berhasil diperbarui.');
                $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                return;
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                if ($uploadedFile !== '') {
                    $this->deleteFile(UPLOAD_BUKTI . $uploadedFile);
                }
                $this->setFlash('error', $e->getMessage());
                $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                return;
            }
        }
    }

    public function deletePengeluaran(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/KegiatanPengeluaran.php';
            require_once BASE_PATH . '/core/FinancialService.php';
            $model = new KegiatanPengeluaran();
            $financialService = new FinancialService();
            $old = $model->findById((int)$id);
            if ($old) {
                $db = $model->getDb();
                try {
                    $db->beginTransaction();
                    $financialService->removeMutations('kegiatan_pengeluaran', (int)$id);
                    $model->delete((int)$id);
                    AuditLogger::log('DELETE', 'kegiatan_pengeluaran', (int)$id, $old, null);
                    $db->commit();
                    if (!empty($old['bukti_nota'])) $this->deleteFile(UPLOAD_BUKTI . $old['bukti_nota']);
                    $this->setFlash('success', 'Pengeluaran kegiatan berhasil dihapus.');
                    $this->redirect('kegiatan/detail/' . $old['kegiatan_id']);
                    return;
                } catch (Throwable $e) {
                    if ($db->inTransaction()) {
                        $db->rollBack();
                    }
                    $this->setFlash('error', $e->getMessage());
                }
            }
        }
        $this->redirect('kegiatan');
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
}
