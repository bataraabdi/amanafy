<?php
class DonasiPengeluaran extends Model {
    protected string $table = 'donasi_pengeluaran';

    public function getByProgram(int $programId): array {
        return $this->query("
            SELECT dp.*, u.nama_lengkap as petugas,
                   COALESCE(ca.nama_kas, CONCAT(ba.nama_bank, ' - ', ba.nomor_rekening)) AS nama_akun
            FROM donasi_pengeluaran dp
            LEFT JOIN users u ON dp.user_id = u.id
            LEFT JOIN cash_accounts ca ON dp.account_type = 'cash' AND dp.account_id = ca.id
            LEFT JOIN bank_accounts ba ON dp.account_type = 'bank' AND dp.account_id = ba.id
            WHERE dp.program_id = :pid
            ORDER BY dp.tanggal DESC
        ", [':pid' => $programId]);
    }

    public function getTotalByProgram(int $programId): float {
        $result = $this->query("SELECT COALESCE(SUM(jumlah), 0) as total FROM donasi_pengeluaran WHERE program_id = :pid", [':pid' => $programId]);
        return (float)($result[0]['total'] ?? 0);
    }
}
