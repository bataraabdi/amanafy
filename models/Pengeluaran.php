<?php
class Pengeluaran extends Model {
    protected string $table = 'pengeluaran';

    public function getAllWithRelations(int $limit = 0, int $offset = 0, array $filters = []): array {
        $sql = "SELECT p.*, kp.nama_kategori, u.nama_lengkap as petugas,
                    COALESCE(ca.nama_kas, CONCAT(ba.nama_bank, ' - ', ba.nomor_rekening)) AS nama_akun
                FROM pengeluaran p
                LEFT JOIN kategori_pengeluaran kp ON p.kategori_id = kp.id
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN cash_accounts ca ON p.account_type = 'cash' AND p.account_id = ca.id
                LEFT JOIN bank_accounts ba ON p.account_type = 'bank' AND p.account_id = ba.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['tanggal_dari'])) {
            $sql .= " AND p.tanggal >= :dari";
            $params[':dari'] = $filters['tanggal_dari'];
        }
        if (!empty($filters['tanggal_sampai'])) {
            $sql .= " AND p.tanggal <= :sampai";
            $params[':sampai'] = $filters['tanggal_sampai'];
        }
        if (!empty($filters['kategori_id'])) {
            $sql .= " AND p.kategori_id = :kat";
            $params[':kat'] = $filters['kategori_id'];
        }
        if (!empty($filters['bulan'])) {
            $sql .= " AND DATE_FORMAT(p.tanggal, '%Y-%m') = :bulan";
            $params[':bulan'] = $filters['bulan'];
        }
        if (!empty($filters['fund_category'])) {
            $sql .= " AND p.fund_category = :fund_category";
            $params[':fund_category'] = $filters['fund_category'];
        }

        $sql .= " ORDER BY p.tanggal DESC, p.id DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return $this->query($sql, $params);
    }

    public function countFiltered(array $filters = []): int {
        $sql = "SELECT COUNT(*) as total FROM pengeluaran p WHERE 1=1";
        $params = [];

        if (!empty($filters['tanggal_dari'])) {
            $sql .= " AND p.tanggal >= :dari";
            $params[':dari'] = $filters['tanggal_dari'];
        }
        if (!empty($filters['tanggal_sampai'])) {
            $sql .= " AND p.tanggal <= :sampai";
            $params[':sampai'] = $filters['tanggal_sampai'];
        }
        if (!empty($filters['kategori_id'])) {
            $sql .= " AND p.kategori_id = :kat";
            $params[':kat'] = $filters['kategori_id'];
        }
        if (!empty($filters['bulan'])) {
            $sql .= " AND DATE_FORMAT(p.tanggal, '%Y-%m') = :bulan";
            $params[':bulan'] = $filters['bulan'];
        }
        if (!empty($filters['fund_category'])) {
            $sql .= " AND p.fund_category = :fund_category";
            $params[':fund_category'] = $filters['fund_category'];
        }

        $result = $this->query($sql, $params);
        return (int)($result[0]['total'] ?? 0);
    }

    public function getTotalBulanIni(): float {
        $bulan = date('Y-m');
        $result = $this->query("SELECT COALESCE(SUM(jumlah), 0) as total FROM pengeluaran WHERE DATE_FORMAT(tanggal, '%Y-%m') = :bulan", [':bulan' => $bulan]);
        return (float)($result[0]['total'] ?? 0);
    }

    public function getTotal(): float {
        return $this->sum('jumlah');
    }

    public function getTotalByKategori(): array {
        return $this->query("SELECT kp.nama_kategori, COALESCE(SUM(p.jumlah), 0) as total FROM kategori_pengeluaran kp LEFT JOIN pengeluaran p ON kp.id = p.kategori_id GROUP BY kp.id, kp.nama_kategori ORDER BY total DESC");
    }

    public function getMonthlyData(string $year): array {
        return $this->query("SELECT MONTH(tanggal) as bulan, COALESCE(SUM(jumlah), 0) as total FROM pengeluaran WHERE YEAR(tanggal) = :year GROUP BY MONTH(tanggal) ORDER BY bulan", [':year' => $year]);
    }
}
