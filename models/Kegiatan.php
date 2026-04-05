<?php
class Kegiatan extends Model {
    protected string $table = 'kegiatan';

    public function getAllWithStats(): array {
        return $this->query("SELECT k.*, 
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0) as total_pengeluaran,
            (COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) as total_anggaran,
            ((COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) - COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0)) as sisa_anggaran
            FROM kegiatan k ORDER BY k.created_at DESC");
    }

    public function getWithStats(int $id): ?array {
        $results = $this->query("SELECT k.*, 
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0) as total_pengeluaran,
            (COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) as total_anggaran,
            ((COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) - COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0)) as sisa_anggaran
            FROM kegiatan k WHERE k.id = :id", [':id' => $id]);
        return $results[0] ?? null;
    }

    public function getDashboardStats(): array {
        $result = $this->query("SELECT 
            COUNT(*) as total_kegiatan,
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran), 0) as total_pengeluaran,
            (COALESCE(SUM(jumlah_anggaran), 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan), 0)) as total_anggaran,
            ((COALESCE(SUM(jumlah_anggaran), 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan), 0)) - COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran), 0)) as sisa_anggaran
            FROM kegiatan");
        return $result[0] ?? ['total_kegiatan' => 0, 'total_anggaran' => 0, 'total_pemasukan' => 0, 'total_pengeluaran' => 0, 'sisa_anggaran' => 0];
    }

    public function getActiveWithStats(): array {
        return $this->query("SELECT k.*, 
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0) as total_pengeluaran,
            (COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) as total_anggaran,
            ((COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) - COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0)) as sisa_anggaran
            FROM kegiatan k
            WHERE k.status = 'aktif'
            ORDER BY k.created_at DESC");
    }

    public function getPublishedWithStats(): array {
        return $this->query("SELECT k.*, 
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0) as total_pengeluaran,
            (COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) as total_anggaran,
            ((COALESCE(k.jumlah_anggaran, 0) + COALESCE((SELECT SUM(jumlah) FROM kegiatan_pemasukan WHERE kegiatan_id = k.id), 0)) - COALESCE((SELECT SUM(jumlah) FROM kegiatan_pengeluaran WHERE kegiatan_id = k.id), 0)) as sisa_anggaran
            FROM kegiatan k
            WHERE k.tampil_publik = 1
            ORDER BY k.created_at DESC");
    }
}
