<?php
class InternalTransfer extends Model {
    protected string $table = 'internal_transfers';

    public function getAllWithRelations(int $limit = 0): array {
        $sql = "
            SELECT
                it.*,
                u.nama_lengkap AS petugas,
                COALESCE(cas.nama_kas, CONCAT(bas.nama_bank, ' - ', bas.nomor_rekening)) AS akun_asal_nama,
                COALESCE(cat.nama_kas, CONCAT(bat.nama_bank, ' - ', bat.nomor_rekening)) AS akun_tujuan_nama
            FROM internal_transfers it
            LEFT JOIN users u ON it.user_id = u.id
            LEFT JOIN cash_accounts cas ON it.akun_asal_type = 'cash' AND it.akun_asal_id = cas.id
            LEFT JOIN bank_accounts bas ON it.akun_asal_type = 'bank' AND it.akun_asal_id = bas.id
            LEFT JOIN cash_accounts cat ON it.akun_tujuan_type = 'cash' AND it.akun_tujuan_id = cat.id
            LEFT JOIN bank_accounts bat ON it.akun_tujuan_type = 'bank' AND it.akun_tujuan_id = bat.id
            ORDER BY it.tanggal DESC, it.id DESC
        ";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return $this->query($sql);
    }

    public function findDetailedById(int $id): ?array {
        $result = $this->query("
            SELECT
                it.*,
                u.nama_lengkap AS petugas,
                COALESCE(cas.nama_kas, CONCAT(bas.nama_bank, ' - ', bas.nomor_rekening)) AS akun_asal_nama,
                COALESCE(cat.nama_kas, CONCAT(bat.nama_bank, ' - ', bat.nomor_rekening)) AS akun_tujuan_nama
            FROM internal_transfers it
            LEFT JOIN users u ON it.user_id = u.id
            LEFT JOIN cash_accounts cas ON it.akun_asal_type = 'cash' AND it.akun_asal_id = cas.id
            LEFT JOIN bank_accounts bas ON it.akun_asal_type = 'bank' AND it.akun_asal_id = bas.id
            LEFT JOIN cash_accounts cat ON it.akun_tujuan_type = 'cash' AND it.akun_tujuan_id = cat.id
            LEFT JOIN bank_accounts bat ON it.akun_tujuan_type = 'bank' AND it.akun_tujuan_id = bat.id
            WHERE it.id = :id
            LIMIT 1
        ", [':id' => $id]);

        return $result[0] ?? null;
    }
}
