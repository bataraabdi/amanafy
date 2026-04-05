<?php
class User extends Model {
    protected string $table = 'users';

    public function findByUsername(string $username): ?array {
        return $this->findOneWhere(['username' => $username]);
    }

    public function findByEmail(string $email): ?array {
        return $this->findOneWhere(['email' => $email]);
    }

    public function findByUsernameExcludingId(string $username, int $excludeId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username AND `id` != :id LIMIT 1");
        $stmt->execute([
            ':username' => $username,
            ':id' => $excludeId,
        ]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByEmailExcludingId(string $email, int $excludeId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `email` = :email AND `id` != :id LIMIT 1");
        $stmt->execute([
            ':email' => $email,
            ':id' => $excludeId,
        ]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findWithRoleById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nama_role, r.hak_akses
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getAllWithRole(): array {
        return $this->query("SELECT u.*, r.nama_role FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id ASC");
    }

    public function createUser(array $data): int {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }

    public function updateUser(int $id, array $data): bool {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->update($id, $data);
    }
}
