<?php
class BankAccount extends Model {
    protected string $table = 'bank_accounts';

    public function getAllAccounts(bool $activeOnly = false, bool $publicOnly = false): array {
        $sql = "SELECT * FROM bank_accounts";
        $where = [];
        $params = [];

        if ($activeOnly) {
            $where[] = "is_active = :active";
            $params[':active'] = 1;
        }

        if ($publicOnly) {
            $where[] = "is_public = :public";
            $params[':public'] = 1;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY nama_bank ASC, nomor_rekening ASC";
        return $this->query($sql, $params);
    }

    public function getAllWithPosition(?string $endDate = null): array {
        if ($endDate === null || trim($endDate) === '') {
            return $this->query("
                SELECT ba.*, ba.saldo_saat_ini AS saldo_posisi
                FROM bank_accounts ba
                ORDER BY ba.nama_bank ASC, ba.nomor_rekening ASC
            ");
        }

        return $this->query("
            SELECT
                ba.*,
                (
                    ba.saldo_awal +
                    COALESCE((
                        SELECT SUM(
                            CASE
                                WHEN am.entry_type = 'debet' THEN am.amount
                                ELSE -am.amount
                            END
                        )
                        FROM account_mutations am
                        WHERE am.account_type = 'bank'
                          AND am.account_id = ba.id
                          AND am.tanggal <= :end_date
                    ), 0)
                ) AS saldo_posisi
            FROM bank_accounts ba
            ORDER BY ba.nama_bank ASC, ba.nomor_rekening ASC
        ", [':end_date' => $endDate]);
    }
}
