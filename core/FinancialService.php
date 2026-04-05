<?php
require_once BASE_PATH . '/models/BankAccount.php';
require_once BASE_PATH . '/models/CashAccount.php';
require_once BASE_PATH . '/models/AccountMutation.php';
require_once BASE_PATH . '/models/InternalTransfer.php';
require_once BASE_PATH . '/models/BankMonthlyPosting.php';
require_once BASE_PATH . '/models/Pemasukan.php';
require_once BASE_PATH . '/models/Pengeluaran.php';

class FinancialService {
    private PDO $db;
    private BankAccount $bankAccountModel;
    private CashAccount $cashAccountModel;
    private AccountMutation $mutationModel;
    private InternalTransfer $internalTransferModel;
    private BankMonthlyPosting $bankMonthlyPostingModel;
    private Pemasukan $pemasukanModel;
    private Pengeluaran $pengeluaranModel;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->bankAccountModel = new BankAccount();
        $this->cashAccountModel = new CashAccount();
        $this->mutationModel = new AccountMutation();
        $this->internalTransferModel = new InternalTransfer();
        $this->bankMonthlyPostingModel = new BankMonthlyPosting();
        $this->pemasukanModel = new Pemasukan();
        $this->pengeluaranModel = new Pengeluaran();
    }

    public function getAccountOptions(bool $activeOnly = true): array {
        $options = [];

        foreach ($this->cashAccountModel->getAllAccounts($activeOnly) as $account) {
            $options[] = [
                'value' => accountReferenceValue('cash', $account['id']),
                'type' => 'cash',
                'id' => (int)$account['id'],
                'label' => $account['nama_kas'] . ' (Kas) - Saldo ' . rupiah((float)($account['saldo_saat_ini'] ?? 0)),
            ];
        }

        foreach ($this->bankAccountModel->getAllAccounts($activeOnly) as $account) {
            $options[] = [
                'value' => accountReferenceValue('bank', $account['id']),
                'type' => 'bank',
                'id' => (int)$account['id'],
                'label' => $account['nama_bank'] . ' - ' . $account['nomor_rekening'] . ' (Bank) - Saldo ' . rupiah((float)($account['saldo_saat_ini'] ?? 0)),
            ];
        }

        return $options;
    }

    public function validateFundCategory(?string $value): string {
        $value = trim((string)$value);
        if (!in_array($value, fundCategoryOptions(), true)) {
            throw new InvalidArgumentException('Fund category wajib dipilih.');
        }

        return $value;
    }

    public function parseAccountReference(?string $reference, bool $allowInactive = false): array {
        $reference = trim((string)$reference);
        if (!preg_match('/^(cash|bank):(\d+)$/', $reference, $matches)) {
            throw new InvalidArgumentException('Akun kas/bank wajib dipilih.');
        }

        $accountType = $matches[1];
        $accountId = (int)$matches[2];
        $account = $this->findAccount($accountType, $accountId, $allowInactive);

        if (!$account) {
            throw new InvalidArgumentException('Akun kas/bank tidak ditemukan.');
        }

        if (!$allowInactive && isset($account['is_active']) && (int)$account['is_active'] !== 1) {
            throw new InvalidArgumentException('Akun kas/bank yang dipilih tidak aktif.');
        }

        return [
            'account_type' => $accountType,
            'account_id' => $accountId,
            'account' => $account,
            'account_name' => $this->buildAccountName($accountType, $account),
        ];
    }

    public function findAccount(string $accountType, int $accountId, bool $allowInactive = true): ?array {
        $account = $accountType === 'bank'
            ? $this->bankAccountModel->findById($accountId)
            : $this->cashAccountModel->findById($accountId);

        if (!$allowInactive && $account && isset($account['is_active']) && (int)$account['is_active'] !== 1) {
            return null;
        }

        return $account;
    }

    public function syncMutations(string $referenceTable, int $referenceId, array $payload, array $lines): void {
        $this->removeMutations($referenceTable, $referenceId);

        foreach ($lines as $line) {
            $amount = (float)($line['amount'] ?? 0);
            if ($amount <= 0) {
                throw new InvalidArgumentException('Jumlah transaksi harus lebih dari nol.');
            }

            $accountType = (string)($line['account_type'] ?? '');
            $accountId = (int)($line['account_id'] ?? 0);
            $entryType = (string)($line['entry_type'] ?? '');
            if (!in_array($entryType, ['debet', 'kredit'], true)) {
                throw new InvalidArgumentException('Jenis mutasi tidak valid.');
            }

            $fundCategory = $this->validateFundCategory($line['fund_category'] ?? ($payload['fund_category'] ?? ''));
            $account = $this->findAccount($accountType, $accountId, false);

            if (!$account) {
                throw new InvalidArgumentException('Akun transaksi tidak ditemukan.');
            }

            $delta = $entryType === 'debet' ? $amount : -$amount;
            $this->applyBalanceDelta($accountType, $accountId, $delta);

            $this->mutationModel->create([
                'tanggal' => $payload['tanggal'],
                'reference_table' => $referenceTable,
                'reference_id' => $referenceId,
                'account_type' => $accountType,
                'account_id' => $accountId,
                'entry_type' => $entryType,
                'fund_category' => $fundCategory,
                'amount' => $amount,
                'description' => trim((string)($line['description'] ?? $payload['description'] ?? '')),
                'user_id' => $payload['user_id'] ?? null,
            ]);
        }
    }

    public function removeMutations(string $referenceTable, int $referenceId): void {
        $mutations = $this->mutationModel->getByReference($referenceTable, $referenceId);
        if (empty($mutations)) {
            return;
        }

        foreach (array_reverse($mutations) as $mutation) {
            $amount = (float)($mutation['amount'] ?? 0);
            $delta = ($mutation['entry_type'] ?? '') === 'debet' ? -$amount : $amount;
            $this->applyBalanceDelta((string)$mutation['account_type'], (int)$mutation['account_id'], $delta);
        }

        $this->mutationModel->deleteByReference($referenceTable, $referenceId);
    }

    public function assertAccountDeletionAllowed(string $accountType, int $accountId): void {
        $account = $this->findAccount($accountType, $accountId, false);
        if (!$account) {
            throw new RuntimeException('Akun tidak ditemukan.');
        }

        if ($this->mutationModel->countByAccount($accountType, $accountId) > 0) {
            throw new RuntimeException('Akun tidak dapat dihapus karena sudah memiliki riwayat transaksi atau mutasi.');
        }

        if (abs((float)($account['saldo_saat_ini'] ?? 0)) > 0.0001) {
            throw new RuntimeException('Akun hanya dapat dihapus jika saldo saat ini bernilai nol.');
        }
    }

    public function createInternalTransfer(array $data): int {
        $fundCategory = $this->validateFundCategory($data['fund_category'] ?? '');
        $amount = (float)($data['jumlah'] ?? 0);
        if ($amount <= 0) {
            throw new InvalidArgumentException('Jumlah transfer harus lebih dari nol.');
        }

        if (($data['akun_asal_type'] ?? '') === ($data['akun_tujuan_type'] ?? '')
            && (int)($data['akun_asal_id'] ?? 0) === (int)($data['akun_tujuan_id'] ?? 0)) {
            throw new InvalidArgumentException('Akun asal dan akun tujuan tidak boleh sama.');
        }

        $payload = [
            'tanggal' => $data['tanggal'] ?? date('Y-m-d'),
            'akun_asal_type' => $data['akun_asal_type'],
            'akun_asal_id' => (int)$data['akun_asal_id'],
            'akun_tujuan_type' => $data['akun_tujuan_type'],
            'akun_tujuan_id' => (int)$data['akun_tujuan_id'],
            'jumlah' => $amount,
            'fund_category' => $fundCategory,
            'keterangan' => trim((string)($data['keterangan'] ?? '')),
            'user_id' => $data['user_id'] ?? null,
        ];

        $id = $this->internalTransferModel->create($payload);
        $description = 'Transfer internal: ' . $this->buildTransferSummary($payload);

        $this->syncMutations('internal_transfers', $id, [
            'tanggal' => $payload['tanggal'],
            'fund_category' => $fundCategory,
            'description' => $description,
            'user_id' => $payload['user_id'],
        ], [
            [
                'account_type' => $payload['akun_asal_type'],
                'account_id' => $payload['akun_asal_id'],
                'entry_type' => 'kredit',
                'amount' => $payload['jumlah'],
                'fund_category' => $fundCategory,
                'description' => 'Kredit transfer internal',
            ],
            [
                'account_type' => $payload['akun_tujuan_type'],
                'account_id' => $payload['akun_tujuan_id'],
                'entry_type' => 'debet',
                'amount' => $payload['jumlah'],
                'fund_category' => $fundCategory,
                'description' => 'Debet transfer internal',
            ],
        ]);

        return $id;
    }

    public function updateInternalTransfer(int $id, array $data): void {
        $existing = $this->internalTransferModel->findById($id);
        if (!$existing) {
            throw new RuntimeException('Data transfer internal tidak ditemukan.');
        }

        $fundCategory = $this->validateFundCategory($data['fund_category'] ?? '');
        $amount = (float)($data['jumlah'] ?? 0);
        if ($amount <= 0) {
            throw new InvalidArgumentException('Jumlah transfer harus lebih dari nol.');
        }

        if (($data['akun_asal_type'] ?? '') === ($data['akun_tujuan_type'] ?? '')
            && (int)($data['akun_asal_id'] ?? 0) === (int)($data['akun_tujuan_id'] ?? 0)) {
            throw new InvalidArgumentException('Akun asal dan akun tujuan tidak boleh sama.');
        }

        $payload = [
            'tanggal' => $data['tanggal'] ?? $existing['tanggal'],
            'akun_asal_type' => $data['akun_asal_type'],
            'akun_asal_id' => (int)$data['akun_asal_id'],
            'akun_tujuan_type' => $data['akun_tujuan_type'],
            'akun_tujuan_id' => (int)$data['akun_tujuan_id'],
            'jumlah' => $amount,
            'fund_category' => $fundCategory,
            'keterangan' => trim((string)($data['keterangan'] ?? '')),
        ];

        $this->internalTransferModel->update($id, $payload);
        $description = 'Transfer internal: ' . $this->buildTransferSummary($payload);

        $this->syncMutations('internal_transfers', $id, [
            'tanggal' => $payload['tanggal'],
            'fund_category' => $fundCategory,
            'description' => $description,
            'user_id' => $existing['user_id'] ?? null,
        ], [
            [
                'account_type' => $payload['akun_asal_type'],
                'account_id' => $payload['akun_asal_id'],
                'entry_type' => 'kredit',
                'amount' => $payload['jumlah'],
                'fund_category' => $fundCategory,
                'description' => 'Kredit transfer internal',
            ],
            [
                'account_type' => $payload['akun_tujuan_type'],
                'account_id' => $payload['akun_tujuan_id'],
                'entry_type' => 'debet',
                'amount' => $payload['jumlah'],
                'fund_category' => $fundCategory,
                'description' => 'Debet transfer internal',
            ],
        ]);
    }

    public function deleteInternalTransfer(int $id): void {
        $existing = $this->internalTransferModel->findById($id);
        if (!$existing) {
            throw new RuntimeException('Data transfer internal tidak ditemukan.');
        }

        $this->removeMutations('internal_transfers', $id);
        $this->internalTransferModel->delete($id);
    }

    public function runMonthlyBankAutomation(string $period, ?int $userId = null): array {
        $period = trim($period);
        if (!preg_match('/^\d{4}-\d{2}$/', $period)) {
            throw new InvalidArgumentException('Periode otomatisasi harus menggunakan format YYYY-MM.');
        }

        $postingDate = date('Y-m-t', strtotime($period . '-01'));
        $feeCategoryId = $this->ensureCategory(
            'pengeluaran',
            'Biaya Admin Bank',
            'Beban biaya administrasi bank otomatis bulanan'
        );
        $interestCategoryId = $this->ensureCategory(
            'pemasukan',
            'Jasa Giro / Pendapatan Non-Halal',
            'Pendapatan non-halal atau jasa giro otomatis bulanan'
        );

        $result = [
            'fees_created' => 0,
            'interest_created' => 0,
            'errors' => [],
        ];

        foreach ($this->bankAccountModel->getAllAccounts(true) as $bank) {
            $bankId = (int)$bank['id'];
            $petugasId = $userId ?? 1;

            $feeAmount = (float)($bank['bank_admin_fee'] ?? 0);
            if ($feeAmount > 0 && !$this->bankMonthlyPostingModel->exists($bankId, 'bank_admin_fee', $period)) {
                try {
                    $this->db->beginTransaction();

                    $expenseId = $this->pengeluaranModel->create([
                        'tanggal' => $postingDate,
                        'kategori_id' => $feeCategoryId,
                        'jumlah' => $feeAmount,
                        'penerima' => $bank['nama_bank'],
                        'keterangan' => 'Posting otomatis biaya admin bank periode ' . $period,
                        'user_id' => $petugasId,
                        'fund_category' => 'Tidak Terikat',
                        'account_type' => 'bank',
                        'account_id' => $bankId,
                        'is_system_generated' => 1,
                    ]);

                    $this->syncMutations('pengeluaran', $expenseId, [
                        'tanggal' => $postingDate,
                        'fund_category' => 'Tidak Terikat',
                        'description' => 'Posting otomatis biaya admin bank ' . $bank['nama_bank'],
                        'user_id' => $petugasId,
                    ], [[
                        'account_type' => 'bank',
                        'account_id' => $bankId,
                        'entry_type' => 'kredit',
                        'amount' => $feeAmount,
                        'fund_category' => 'Tidak Terikat',
                        'description' => 'Kredit biaya admin bank',
                    ]]);

                    $this->bankMonthlyPostingModel->create([
                        'bank_account_id' => $bankId,
                        'posting_type' => 'bank_admin_fee',
                        'periode_bulan' => $period,
                        'amount' => $feeAmount,
                        'transaction_table' => 'pengeluaran',
                        'transaction_id' => $expenseId,
                    ]);

                    $this->db->commit();
                    $result['fees_created']++;
                } catch (Throwable $e) {
                    if ($this->db->inTransaction()) {
                        $this->db->rollBack();
                    }
                    $result['errors'][] = 'Biaya admin ' . $bank['nama_bank'] . ': ' . $e->getMessage();
                }
            }

            $interestAmount = (float)($bank['bank_interest'] ?? 0);
            if ($interestAmount > 0 && !$this->bankMonthlyPostingModel->exists($bankId, 'bank_interest', $period)) {
                try {
                    $this->db->beginTransaction();

                    $incomeId = $this->pemasukanModel->create([
                        'tanggal' => $postingDate,
                        'donatur_id' => null,
                        'nama_donatur' => $bank['nama_bank'],
                        'kategori_id' => $interestCategoryId,
                        'jumlah' => $interestAmount,
                        'metode_pembayaran' => 'transfer',
                        'keterangan' => 'Posting otomatis jasa giro / pendapatan non-halal periode ' . $period,
                        'user_id' => $petugasId,
                        'fund_category' => 'Tidak Terikat',
                        'account_type' => 'bank',
                        'account_id' => $bankId,
                        'is_system_generated' => 1,
                    ]);

                    $this->syncMutations('pemasukan', $incomeId, [
                        'tanggal' => $postingDate,
                        'fund_category' => 'Tidak Terikat',
                        'description' => 'Posting otomatis jasa giro ' . $bank['nama_bank'],
                        'user_id' => $petugasId,
                    ], [[
                        'account_type' => 'bank',
                        'account_id' => $bankId,
                        'entry_type' => 'debet',
                        'amount' => $interestAmount,
                        'fund_category' => 'Tidak Terikat',
                        'description' => 'Debet jasa giro / pendapatan non-halal',
                    ]]);

                    $this->bankMonthlyPostingModel->create([
                        'bank_account_id' => $bankId,
                        'posting_type' => 'bank_interest',
                        'periode_bulan' => $period,
                        'amount' => $interestAmount,
                        'transaction_table' => 'pemasukan',
                        'transaction_id' => $incomeId,
                    ]);

                    $this->db->commit();
                    $result['interest_created']++;
                } catch (Throwable $e) {
                    if ($this->db->inTransaction()) {
                        $this->db->rollBack();
                    }
                    $result['errors'][] = 'Jasa giro ' . $bank['nama_bank'] . ': ' . $e->getMessage();
                }
            }
        }

        return $result;
    }

    private function ensureCategory(string $type, string $name, string $description): int {
        $table = $type === 'pemasukan' ? 'kategori_pemasukan' : 'kategori_pengeluaran';
        $result = $this->db->prepare("SELECT id FROM `{$table}` WHERE nama_kategori = :name LIMIT 1");
        $result->execute([':name' => $name]);
        $row = $result->fetch();
        if ($row) {
            return (int)$row['id'];
        }

        $insert = $this->db->prepare("INSERT INTO `{$table}` (nama_kategori, keterangan) VALUES (:name, :description)");
        $insert->execute([
            ':name' => $name,
            ':description' => $description,
        ]);

        return (int)$this->db->lastInsertId();
    }

    private function applyBalanceDelta(string $accountType, int $accountId, float $delta): void {
        $account = $this->findAccount($accountType, $accountId, false);
        if (!$account) {
            throw new RuntimeException('Akun saldo tidak ditemukan.');
        }

        $newBalance = (float)($account['saldo_saat_ini'] ?? 0) + $delta;
        if ($newBalance < -0.0001) {
            throw new RuntimeException('Saldo akun ' . $this->buildAccountName($accountType, $account) . ' tidak mencukupi.');
        }

        $table = $accountType === 'bank' ? 'bank_accounts' : 'cash_accounts';
        $stmt = $this->db->prepare("UPDATE `{$table}` SET saldo_saat_ini = :balance WHERE id = :id");
        $stmt->execute([
            ':balance' => $newBalance,
            ':id' => $accountId,
        ]);
    }

    private function buildAccountName(string $accountType, array $account): string {
        if ($accountType === 'bank') {
            return trim(($account['nama_bank'] ?? 'Bank') . ' - ' . ($account['nomor_rekening'] ?? ''));
        }

        return trim((string)($account['nama_kas'] ?? 'Kas'));
    }

    private function buildTransferSummary(array $payload): string {
        $asal = $this->findAccount((string)$payload['akun_asal_type'], (int)$payload['akun_asal_id'], false);
        $tujuan = $this->findAccount((string)$payload['akun_tujuan_type'], (int)$payload['akun_tujuan_id'], false);

        $asalNama = $asal ? $this->buildAccountName((string)$payload['akun_asal_type'], $asal) : 'Akun Asal';
        $tujuanNama = $tujuan ? $this->buildAccountName((string)$payload['akun_tujuan_type'], $tujuan) : 'Akun Tujuan';

        return $asalNama . ' -> ' . $tujuanNama . ' sebesar ' . rupiah((float)($payload['jumlah'] ?? 0));
    }
}
