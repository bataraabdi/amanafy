<?php
class CashAccount extends Model {
    protected string $table = 'cash_accounts';

    public function getAllAccounts(bool $activeOnly = false): array {
        $sql = "SELECT * FROM cash_accounts";
        $params = [];

        if ($activeOnly) {
            $sql .= " WHERE is_active = :active";
            $params[':active'] = 1;
        }

        $sql .= " ORDER BY nama_kas ASC";
        return $this->query($sql, $params);
    }

    public function getAllWithPosition(?string $endDate = null): array {
        if ($endDate === null || trim($endDate) === '') {
            return $this->query("
                SELECT ca.*, ca.saldo_saat_ini AS saldo_posisi
                FROM cash_accounts ca
                ORDER BY ca.nama_kas ASC
            ");
        }

        return $this->query("
            SELECT
                ca.*,
                (
                    ca.saldo_awal +
                    COALESCE((
                        SELECT SUM(
                            CASE
                                WHEN am.entry_type = 'debet' THEN am.amount
                                ELSE -am.amount
                            END
                        )
                        FROM account_mutations am
                        WHERE am.account_type = 'cash'
                          AND am.account_id = ca.id
                          AND am.tanggal <= :end_date
                    ), 0)
                ) AS saldo_posisi
            FROM cash_accounts ca
            ORDER BY ca.nama_kas ASC
        ", [':end_date' => $endDate]);
    }
}
