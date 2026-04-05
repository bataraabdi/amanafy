<?php
class KasBankController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/core/FinancialService.php';

        $service = new FinancialService();
        $bankModel = new BankAccount();
        $cashModel = new CashAccount();
        $transferModel = new InternalTransfer();
        $postingModel = new BankMonthlyPosting();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $action = $_POST['action'] ?? '';
            $db = Database::getConnection();

            try {
                switch ($action) {
                    case 'add_bank':
                        $saldoAwal = max(0, normalizeAmountInput($_POST['saldo_awal'] ?? '0'));
                        $bankModel->create([
                            'nama_bank' => trim($_POST['nama_bank'] ?? ''),
                            'nomor_rekening' => trim($_POST['nomor_rekening'] ?? ''),
                            'nama_pemilik' => trim($_POST['nama_pemilik'] ?? ''),
                            'saldo_awal' => $saldoAwal,
                            'saldo_saat_ini' => $saldoAwal,
                            'bank_admin_fee' => max(0, normalizeAmountInput($_POST['bank_admin_fee'] ?? '0')),
                            'bank_interest' => max(0, normalizeAmountInput($_POST['bank_interest'] ?? '0')),
                            'is_active' => (int)($_POST['is_active'] ?? 1) === 1 ? 1 : 0,
                            'is_public' => (int)($_POST['is_public'] ?? 0) === 1 ? 1 : 0,
                        ]);
                        $this->setFlash('success', 'Akun bank berhasil ditambahkan.');
                        break;

                    case 'edit_bank':
                        $bankId = (int)($_POST['id'] ?? 0);
                        $existingBank = $bankModel->findById($bankId);
                        if (!$existingBank) {
                            throw new RuntimeException('Akun bank tidak ditemukan.');
                        }

                        $saldoAwalBaru = max(0, normalizeAmountInput($_POST['saldo_awal'] ?? '0'));
                        $deltaSaldoAwal = $saldoAwalBaru - (float)($existingBank['saldo_awal'] ?? 0);
                        $saldoSaatIniBaru = (float)($existingBank['saldo_saat_ini'] ?? 0) + $deltaSaldoAwal;
                        if ($saldoSaatIniBaru < 0) {
                            throw new RuntimeException('Perubahan saldo awal membuat saldo saat ini menjadi negatif.');
                        }

                        $bankModel->update($bankId, [
                            'nama_bank' => trim($_POST['nama_bank'] ?? ''),
                            'nomor_rekening' => trim($_POST['nomor_rekening'] ?? ''),
                            'nama_pemilik' => trim($_POST['nama_pemilik'] ?? ''),
                            'saldo_awal' => $saldoAwalBaru,
                            'saldo_saat_ini' => $saldoSaatIniBaru,
                            'bank_admin_fee' => max(0, normalizeAmountInput($_POST['bank_admin_fee'] ?? '0')),
                            'bank_interest' => max(0, normalizeAmountInput($_POST['bank_interest'] ?? '0')),
                            'is_active' => (int)($_POST['is_active'] ?? 1) === 1 ? 1 : 0,
                            'is_public' => (int)($_POST['is_public'] ?? 0) === 1 ? 1 : 0,
                        ]);
                        $this->setFlash('success', 'Akun bank berhasil diperbarui.');
                        break;

                    case 'delete_bank':
                        $bankId = (int)($_POST['id'] ?? 0);
                        $service->assertAccountDeletionAllowed('bank', $bankId);
                        $bankModel->delete($bankId);
                        $this->setFlash('success', 'Akun bank berhasil dihapus.');
                        break;

                    case 'add_cash':
                        $saldoAwal = max(0, normalizeAmountInput($_POST['saldo_awal'] ?? '0'));
                        $cashModel->create([
                            'nama_kas' => trim($_POST['nama_kas'] ?? ''),
                            'saldo_awal' => $saldoAwal,
                            'saldo_saat_ini' => $saldoAwal,
                            'is_active' => (int)($_POST['is_active'] ?? 1) === 1 ? 1 : 0,
                        ]);
                        $this->setFlash('success', 'Akun kas berhasil ditambahkan.');
                        break;

                    case 'edit_cash':
                        $cashId = (int)($_POST['id'] ?? 0);
                        $existingCash = $cashModel->findById($cashId);
                        if (!$existingCash) {
                            throw new RuntimeException('Akun kas tidak ditemukan.');
                        }

                        $saldoAwalBaru = max(0, normalizeAmountInput($_POST['saldo_awal'] ?? '0'));
                        $deltaSaldoAwal = $saldoAwalBaru - (float)($existingCash['saldo_awal'] ?? 0);
                        $saldoSaatIniBaru = (float)($existingCash['saldo_saat_ini'] ?? 0) + $deltaSaldoAwal;
                        if ($saldoSaatIniBaru < 0) {
                            throw new RuntimeException('Perubahan saldo awal membuat saldo saat ini menjadi negatif.');
                        }

                        $cashModel->update($cashId, [
                            'nama_kas' => trim($_POST['nama_kas'] ?? ''),
                            'saldo_awal' => $saldoAwalBaru,
                            'saldo_saat_ini' => $saldoSaatIniBaru,
                            'is_active' => (int)($_POST['is_active'] ?? 1) === 1 ? 1 : 0,
                        ]);
                        $this->setFlash('success', 'Akun kas berhasil diperbarui.');
                        break;

                    case 'delete_cash':
                        $cashId = (int)($_POST['id'] ?? 0);
                        $service->assertAccountDeletionAllowed('cash', $cashId);
                        $cashModel->delete($cashId);
                        $this->setFlash('success', 'Akun kas berhasil dihapus.');
                        break;

                    case 'add_transfer':
                        $db->beginTransaction();
                        $source = $service->parseAccountReference($_POST['akun_asal'] ?? '');
                        $destination = $service->parseAccountReference($_POST['akun_tujuan'] ?? '');
                        $transferId = $service->createInternalTransfer([
                            'tanggal' => $_POST['tanggal'] ?? date('Y-m-d'),
                            'akun_asal_type' => $source['account_type'],
                            'akun_asal_id' => $source['account_id'],
                            'akun_tujuan_type' => $destination['account_type'],
                            'akun_tujuan_id' => $destination['account_id'],
                            'jumlah' => normalizeAmountInput($_POST['jumlah'] ?? '0'),
                            'fund_category' => $_POST['fund_category'] ?? '',
                            'keterangan' => trim($_POST['keterangan'] ?? ''),
                            'user_id' => Auth::id(),
                        ]);
                        AuditLogger::log('CREATE', 'internal_transfers', $transferId, null, ['action' => 'add_transfer']);
                        $db->commit();
                        $this->setFlash('success', 'Transfer internal berhasil dicatat.');
                        break;

                    case 'edit_transfer':
                        $transferId = (int)($_POST['id'] ?? 0);
                        $existingTransfer = $transferModel->findById($transferId);
                        if (!$existingTransfer) {
                            throw new RuntimeException('Transfer internal tidak ditemukan.');
                        }

                        $db->beginTransaction();
                        $source = $service->parseAccountReference($_POST['akun_asal'] ?? '');
                        $destination = $service->parseAccountReference($_POST['akun_tujuan'] ?? '');
                        $service->updateInternalTransfer($transferId, [
                            'tanggal' => $_POST['tanggal'] ?? $existingTransfer['tanggal'],
                            'akun_asal_type' => $source['account_type'],
                            'akun_asal_id' => $source['account_id'],
                            'akun_tujuan_type' => $destination['account_type'],
                            'akun_tujuan_id' => $destination['account_id'],
                            'jumlah' => normalizeAmountInput($_POST['jumlah'] ?? '0'),
                            'fund_category' => $_POST['fund_category'] ?? '',
                            'keterangan' => trim($_POST['keterangan'] ?? ''),
                        ]);
                        AuditLogger::log('UPDATE', 'internal_transfers', $transferId, $existingTransfer, ['action' => 'edit_transfer']);
                        $db->commit();
                        $this->setFlash('success', 'Transfer internal berhasil diperbarui.');
                        break;

                    case 'delete_transfer':
                        $transferId = (int)($_POST['id'] ?? 0);
                        $existingTransfer = $transferModel->findById($transferId);
                        if (!$existingTransfer) {
                            throw new RuntimeException('Transfer internal tidak ditemukan.');
                        }

                        $db->beginTransaction();
                        $service->deleteInternalTransfer($transferId);
                        AuditLogger::log('DELETE', 'internal_transfers', $transferId, $existingTransfer, null);
                        $db->commit();
                        $this->setFlash('success', 'Transfer internal berhasil dihapus.');
                        break;

                    case 'run_monthly_automation':
                        $period = trim($_POST['periode_bulan'] ?? date('Y-m'));
                        $result = $service->runMonthlyBankAutomation($period, Auth::id());
                        
                        if ($result['fees_created'] === 0 && $result['interest_created'] === 0 && empty($result['errors'])) {
                            $this->setFlash('error', 'Peringatan: Tidak ada data mutasi yang diposting. Pastikan Anda telah mengisi nominal Biaya Admin / Jasa Giro lebih dari Rp0 melalui menu Edit Akun Bank.');
                            break;
                        }

                        $message = 'Otomatisasi ' . $period . ' selesai. '
                            . $result['fees_created'] . ' biaya admin dan '
                            . $result['interest_created'] . ' jasa giro berhasil diposting.';
                            
                        if (!empty($result['errors'])) {
                            $message .= ' Ada kendala pada ' . count($result['errors']) . ' akun.';
                            $this->setFlash('error', implode(' ', $result['errors']));
                        } else {
                            $this->setFlash('success', $message);
                        }
                        break;
                }
            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                $this->setFlash('error', $e->getMessage());
            }

            $this->redirect('kas-bank');
            return;
        }

        $bankList = $bankModel->getAllAccounts();
        $cashList = $cashModel->getAllAccounts();
        $transferList = $transferModel->getAllWithRelations(20);
        $postingList = $postingModel->getRecent(12);

        $totalBank = 0;
        foreach ($bankList as $item) {
            $totalBank += (float)($item['saldo_saat_ini'] ?? 0);
        }

        $totalCash = 0;
        foreach ($cashList as $item) {
            $totalCash += (float)($item['saldo_saat_ini'] ?? 0);
        }

        $this->renderPage('kas-bank/index', [
            'pageTitle' => 'Manajemen Kas & Bank',
            'bankList' => $bankList,
            'cashList' => $cashList,
            'transferList' => $transferList,
            'postingList' => $postingList,
            'accountOptions' => $service->getAccountOptions(true),
            'periodeBulan' => date('Y-m'),
            'totalBank' => $totalBank,
            'totalCash' => $totalCash,
            'grandTotal' => $totalBank + $totalCash,
        ]);
    }
}
