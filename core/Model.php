<?php
/**
 * Base Model - PDO wrapper with prepared statements
 */
class Model {
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(string $orderBy = 'id DESC', int $limit = 0, int $offset = 0): array {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY {$orderBy}";
        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->db->prepare($sql);
        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findWhere(array $conditions, string $orderBy = 'id DESC'): array {
        $where = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $where[] = "`{$key}` = :{$key}";
            $params[":{$key}"] = $value;
        }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE {$whereStr} ORDER BY {$orderBy}");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findOneWhere(array $conditions): ?array {
        $where = [];
        $params = [];
        foreach ($conditions as $key => $value) {
            $where[] = "`{$key}` = :{$key}";
            $params[":{$key}"] = $value;
        }
        $whereStr = implode(' AND ', $where);
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE {$whereStr} LIMIT 1");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int {
        $columns = implode('`, `', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $params = [];
        foreach ($data as $key => $value) {
            $params[":{$key}"] = $value;
        }
        $stmt = $this->db->prepare("INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})");
        $stmt->execute($params);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = [];
        $params = [':id' => $id];
        foreach ($data as $key => $value) {
            $set[] = "`{$key}` = :{$key}";
            $params[":{$key}"] = $value;
        }
        $setStr = implode(', ', $set);
        $stmt = $this->db->prepare("UPDATE `{$this->table}` SET {$setStr} WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute($params);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function count(array $conditions = []): int {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        $params = [];
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "`{$key}` = :{$key}";
                $params[":{$key}"] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch()['total'];
    }

    public function sum(string $column, array $conditions = []): float {
        $sql = "SELECT COALESCE(SUM(`{$column}`), 0) as total FROM `{$this->table}`";
        $params = [];
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "`{$key}` = :{$key}";
                $params[":{$key}"] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (float) $stmt->fetch()['total'];
    }

    public function query(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getDb(): PDO {
        return $this->db;
    }
}
