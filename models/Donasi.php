<?php
class Donasi extends Model {
    protected string $table = 'program_donasi';
    private static bool $schemaEnsured = false;

    public function __construct() {
        parent::__construct();
        $this->ensureSchema();
    }

    public function getAllWithStats(): array {
        return $this->query("SELECT d.*, 
            COALESCE((SELECT SUM(jumlah) FROM donasi_pemasukan WHERE program_id = d.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM donasi_pengeluaran WHERE program_id = d.id), 0) as total_pengeluaran
            FROM program_donasi d
            ORDER BY (d.status = 'aktif') DESC, d.created_at DESC");
    }

    public function getWithStats(int $id): ?array {
        $results = $this->query("SELECT d.*, 
            COALESCE((SELECT SUM(jumlah) FROM donasi_pemasukan WHERE program_id = d.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM donasi_pengeluaran WHERE program_id = d.id), 0) as total_pengeluaran
            FROM program_donasi d WHERE d.id = :id", [':id' => $id]);
        return $results[0] ?? null;
    }

    public function getDashboardStats(): array {
        $result = $this->query("SELECT 
            COUNT(*) as total_program,
            COALESCE((SELECT SUM(jumlah) FROM donasi_pemasukan), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM donasi_pengeluaran), 0) as total_pengeluaran
            FROM program_donasi");
        return $result[0] ?? ['total_program' => 0, 'total_pemasukan' => 0, 'total_pengeluaran' => 0];
    }

    public function getPublishedWithStats(): array {
        return $this->query("SELECT d.*, 
            COALESCE((SELECT SUM(jumlah) FROM donasi_pemasukan WHERE program_id = d.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM donasi_pengeluaran WHERE program_id = d.id), 0) as total_pengeluaran
            FROM program_donasi d
            WHERE d.status = 'aktif'
            ORDER BY d.created_at DESC");
    }

    public function getPublicBySlug(string $slug): ?array {
        $results = $this->query("SELECT d.*, 
            COALESCE((SELECT SUM(jumlah) FROM donasi_pemasukan WHERE program_id = d.id), 0) as total_pemasukan,
            COALESCE((SELECT SUM(jumlah) FROM donasi_pengeluaran WHERE program_id = d.id), 0) as total_pengeluaran
            FROM program_donasi d
            WHERE d.slug = :slug AND d.status = 'aktif'
            LIMIT 1", [':slug' => $slug]);

        return $results[0] ?? null;
    }

    public function slugExists(string $slug, int $ignoreId = 0): bool {
        $sql = "SELECT id FROM program_donasi WHERE slug = :slug";
        $params = [':slug' => $slug];

        if ($ignoreId > 0) {
            $sql .= " AND id != :id";
            $params[':id'] = $ignoreId;
        }

        $sql .= " LIMIT 1";
        return !empty($this->query($sql, $params));
    }

    public function generateUniqueSlug(string $text, int $ignoreId = 0): string {
        $baseSlug = slugify($text);
        if ($baseSlug === '') {
            $baseSlug = 'program-donasi';
        }

        $slug = $baseSlug;
        $counter = 2;
        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function ensureSchema(): void {
        if (self::$schemaEnsured) {
            return;
        }

        $columns = [];
        foreach ($this->query("SHOW COLUMNS FROM `program_donasi`") as $column) {
            $columns[$column['Field']] = true;
        }

        $definitions = [
            'slug' => "ADD COLUMN `slug` VARCHAR(220) NULL AFTER `nama_donasi`",
            'deskripsi_lengkap' => "ADD COLUMN `deskripsi_lengkap` LONGTEXT NULL AFTER `uraian`",
            'bank_nama' => "ADD COLUMN `bank_nama` VARCHAR(120) NULL AFTER `rekening_bank`",
            'no_rekening' => "ADD COLUMN `no_rekening` VARCHAR(60) NULL AFTER `bank_nama`",
            'atas_nama_rekening' => "ADD COLUMN `atas_nama_rekening` VARCHAR(120) NULL AFTER `no_rekening`",
            'qris_file' => "ADD COLUMN `qris_file` VARCHAR(255) NULL AFTER `atas_nama_rekening`",
            'deadline' => "ADD COLUMN `deadline` DATE NULL AFTER `qris_file`",
            'lokasi_kegiatan' => "ADD COLUMN `lokasi_kegiatan` VARCHAR(255) NULL AFTER `deadline`",
            'dokumentasi_files' => "ADD COLUMN `dokumentasi_files` LONGTEXT NULL AFTER `lokasi_kegiatan`",
            'flyer_file' => "ADD COLUMN `flyer_file` VARCHAR(255) NULL AFTER `dokumentasi_files`",
            'nomor_kontak' => "ADD COLUMN `nomor_kontak` VARCHAR(30) NULL AFTER `flyer_file`",
        ];

        $alterStatements = [];
        foreach ($definitions as $field => $statement) {
            if (!isset($columns[$field])) {
                $alterStatements[] = $statement;
            }
        }

        if (!empty($alterStatements)) {
            $this->execute("ALTER TABLE `program_donasi` " . implode(', ', $alterStatements));
        }

        $records = $this->query("SELECT id, nama_donasi, slug, uraian, deskripsi_lengkap, rekening_bank, bank_nama
            FROM program_donasi ORDER BY id ASC");

        foreach ($records as $record) {
            $updates = [];

            $currentSlug = trim((string)($record['slug'] ?? ''));
            if ($currentSlug === '' || $this->slugExists($currentSlug, (int)$record['id'])) {
                $updates['slug'] = $this->generateUniqueSlug($currentSlug !== '' ? $currentSlug : (string)($record['nama_donasi'] ?? ''), (int)$record['id']);
            }

            if (empty($record['deskripsi_lengkap']) && !empty($record['uraian'])) {
                $updates['deskripsi_lengkap'] = sanitizeRichText(nl2br(e((string)$record['uraian'])));
            }

            if (empty($record['bank_nama']) && !empty($record['rekening_bank'])) {
                $updates['bank_nama'] = trim((string)$record['rekening_bank']);
            }

            if (!empty($updates)) {
                $this->update((int)$record['id'], $updates);
            }
        }

        if (!$this->hasIndex('uniq_program_donasi_slug')) {
            $this->execute("ALTER TABLE `program_donasi` ADD UNIQUE KEY `uniq_program_donasi_slug` (`slug`)");
        }

        self::$schemaEnsured = true;
    }

    private function hasIndex(string $indexName): bool {
        $indexes = $this->query("SHOW INDEX FROM `program_donasi` WHERE Key_name = :key_name", [
            ':key_name' => $indexName,
        ]);

        return !empty($indexes);
    }
}
