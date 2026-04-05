<?php
class Role extends Model {
    protected string $table = 'roles';

    public function getAllRoles(): array {
        return $this->findAll('id ASC');
    }
}
