<?php
class Donatur extends Model {
    protected string $table = 'donatur';

    public function search(string $keyword): array {
        return $this->query(
            "SELECT * FROM donatur WHERE nama_donatur LIKE :kw OR no_hp LIKE :kw2 ORDER BY nama_donatur ASC",
            [':kw' => "%{$keyword}%", ':kw2' => "%{$keyword}%"]
        );
    }

    public function getAll(): array {
        return $this->findAll('nama_donatur ASC');
    }
}
