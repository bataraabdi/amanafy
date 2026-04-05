<?php
class Pemasukan extends Model {
    protected string $table = 'pemasukan';

    public function getAllWithRelations(int $limit = 0, int $offset = 0, array $filters = []): array {
        $sql = "SELECT p.*, kp.nama_kategori, u.nama_lengkap as petugas, d.nama_donatur as donatur_nama,
                    COALESCE(ca.nama_kas, CONCAT(ba.nama_bank, ' - ', ba.nomor_rekening)) AS nama_akun
                FROM pemasukan p
                LEFT JOIN kategori_pemasukan kp ON p.kategori_id = kp.id
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN donatur d ON p.donatur_id = d.id
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
        $sql = "SELECT COUNT(*) as total FROM pemasukan p WHERE 1=1";
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
        $result = $this->query("SELECT COALESCE(SUM(jumlah), 0) as total FROM pemasukan WHERE DATE_FORMAT(tanggal, '%Y-%m') = :bulan", [':bulan' => $bulan]);
        return (float)($result[0]['total'] ?? 0);
    }

    public function getTotal(): float {
        return $this->sum('jumlah');
    }

    public function getTotalByKategori(): array {
        return $this->query("SELECT kp.nama_kategori, SUM(p.jumlah) as total FROM pemasukan p INNER JOIN kategori_pemasukan kp ON p.kategori_id = kp.id GROUP BY kp.id, kp.nama_kategori HAVING total > 0 ORDER BY total DESC");
    }

    public function getMonthlyData(string $year): array {
        return $this->query("SELECT MONTH(tanggal) as bulan, COALESCE(SUM(jumlah), 0) as total FROM pemasukan WHERE YEAR(tanggal) = :year GROUP BY MONTH(tanggal) ORDER BY bulan", [':year' => $year]);
    }

    public function getRecentTransactions(int $limit = 10): array {
        return $this->getAllWithRelations($limit);
    }
}
