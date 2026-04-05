<?php
class BackupController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin']);

        // List existing backups
        $backups = [];
        if (is_dir(BACKUP_PATH)) {
            $files = glob(BACKUP_PATH . '*.sql');
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'date' => date('Y-m-d H:i:s', filemtime($file)),
                ];
            }
            usort($backups, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
        }

        $this->renderPage('backup/index', ['pageTitle' => 'Backup Database', 'backups' => $backups]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            if (!is_dir(BACKUP_PATH)) {
                mkdir(BACKUP_PATH, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = BACKUP_PATH . $filename;

            try {
                $db = Database::getConnection();
                $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

                $sql = "-- Backup Database: " . DB_NAME . "\n";
                $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
                $sql .= "-- ========================================\n\n";
                $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

                foreach ($tables as $table) {
                    // Table structure
                    $createStmt = $db->query("SHOW CREATE TABLE `{$table}`")->fetch();
                    $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                    $sql .= $createStmt['Create Table'] . ";\n\n";

                    // Table data
                    $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll();
                    foreach ($rows as $row) {
                        $values = array_map(function($v) use ($db) {
                            return $v === null ? 'NULL' : $db->quote($v);
                        }, $row);
                        $sql .= "INSERT INTO `{$table}` VALUES(" . implode(',', $values) . ");\n";
                    }
                    $sql .= "\n";
                }

                $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
                file_put_contents($filepath, $sql);

                AuditLogger::log('CREATE', 'backup', null, null, ['filename' => $filename]);
                $this->setFlash('success', 'Backup berhasil dibuat: ' . $filename);
            } catch (Exception $e) {
                $this->setFlash('error', 'Backup gagal: ' . $e->getMessage());
            }
        }
        $this->redirect('backup');
    }

    public function download(string $filename = ''): void {
        $this->requireRole(['Super Admin']);
        $filepath = BACKUP_PATH . basename($filename);

        if (!file_exists($filepath)) {
            $this->setFlash('error', 'File backup tidak ditemukan.');
            $this->redirect('backup');
            return;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }

    public function delete(string $filename = ''): void {
        $this->requireRole(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $filepath = BACKUP_PATH . basename($filename);
            if (file_exists($filepath)) {
                unlink($filepath);
                AuditLogger::log('DELETE', 'backup', null, null, ['filename' => $filename]);
                $this->setFlash('success', 'Backup berhasil dihapus.');
            }
        }
        $this->redirect('backup');
    }
}
