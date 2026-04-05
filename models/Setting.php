<?php
class Setting extends Model {
    protected string $table = 'settings';

    public function getAllSettings(): array {
        $rows = $this->findAll('setting_key ASC');
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function getByGroup(string $group): array {
        return $this->findWhere(['setting_group' => $group], 'setting_key ASC');
    }

    public function getValue(string $key, string $default = ''): string {
        $row = $this->findOneWhere(['setting_key' => $key]);
        return $row ? ($row['setting_value'] ?? $default) : $default;
    }

    public function setValue(string $key, ?string $value): bool {
        $existing = $this->findOneWhere(['setting_key' => $key]);
        if ($existing) {
            return $this->execute("UPDATE settings SET setting_value = :val WHERE setting_key = :key", [':val' => $value, ':key' => $key]);
        }
        return false;
    }

    public function updateSettings(array $data): void {
        foreach ($data as $key => $value) {
            $this->setValue($key, $value);
        }
    }
}
