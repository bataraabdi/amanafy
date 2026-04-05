<?php
class AuditLogModel extends Model {
    protected string $table = 'audit_log';

    public function getAll(int $limit = 50, int $offset = 0, array $filters = []): array {
        $sql = "SELECT a.*, u.username FROM audit_log a LEFT JOIN users u ON a.user_id = u.id WHERE 1=1";
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND a.user_id = :uid";
            $params[':uid'] = $filters['user_id'];
        }
        if (!empty($filters['action'])) {
            $sql .= " AND a.action = :act";
            $params[':act'] = $filters['action'];
        }
        if (!empty($filters['tanggal_dari'])) {
            $sql .= " AND DATE(a.created_at) >= :dari";
            $params[':dari'] = $filters['tanggal_dari'];
        }
        if (!empty($filters['tanggal_sampai'])) {
            $sql .= " AND DATE(a.created_at) <= :sampai";
            $params[':sampai'] = $filters['tanggal_sampai'];
        }

        $sql .= " ORDER BY a.created_at DESC LIMIT {$limit} OFFSET {$offset}";
        return $this->query($sql, $params);
    }

    public function countFiltered(array $filters = []): int {
        $sql = "SELECT COUNT(*) as total FROM audit_log WHERE 1=1";
        $params = [];

        if (!empty($filters['user_id'])) {
            $sql .= " AND user_id = :uid";
            $params[':uid'] = $filters['user_id'];
        }
        if (!empty($filters['action'])) {
            $sql .= " AND action = :act";
            $params[':act'] = $filters['action'];
        }

        $result = $this->query($sql, $params);
        return (int)($result[0]['total'] ?? 0);
    }
}
