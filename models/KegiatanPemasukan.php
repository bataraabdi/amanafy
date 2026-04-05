<?php
class KegiatanPemasukan extends Model {
    protected string $table = 'kegiatan_pemasukan';

    public function getByKegiatan(int $kegiatanId): array {
        return $this->query("
            SELECT kp.*, u.nama_lengkap as petugas,
                   COALESCE(ca.nama_kas, CONCAT(ba.nama_bank, ' - ', ba.nomor_rekening)) AS nama_akun
            FROM kegiatan_pemasukan kp
            LEFT JOIN users u ON kp.user_id = u.id
            LEFT JOIN cash_accounts ca ON kp.account_type = 'cash' AND kp.account_id = ca.id
            LEFT JOIN bank_accounts ba ON kp.account_type = 'bank' AND kp.account_id = ba.id
            WHERE kp.kegiatan_id = :kid
            ORDER BY kp.tanggal DESC
        ", [':kid' => $kegiatanId]);
    }

    public function getTotalByKegiatan(int $kegiatanId): float {
        $result = $this->query("SELECT COALESCE(SUM(jumlah), 0) as total FROM kegiatan_pemasukan WHERE kegiatan_id = :kid", [':kid' => $kegiatanId]);
        return (float)($result[0]['total'] ?? 0);
    }
}
