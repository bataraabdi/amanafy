<?php
class Kategori extends Model {
    protected string $table = '';

    public function __construct(string $type = 'pemasukan') {
        parent::__construct();
        $this->table = 'kategori_' . $type;
    }

    public function getAll(): array {
        return $this->findAll('nama_kategori ASC');
    }
}
