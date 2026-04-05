<?php
class AccountMutation extends Model {
    protected string $table = 'account_mutations';

    public function getByReference(string $referenceTable, int $referenceId): array {
        return $this->query(
            "SELECT * FROM account_mutations WHERE reference_table = :table_name AND reference_id = :reference_id ORDER BY id ASC",
            [
                ':table_name' => $referenceTable,
                ':reference_id' => $referenceId,
            ]
        );
    }

    public function deleteByReference(string $referenceTable, int $referenceId): bool {
        return $this->execute(
            "DELETE FROM account_mutations WHERE reference_table = :table_name AND reference_id = :reference_id",
            [
                ':table_name' => $referenceTable,
                ':reference_id' => $referenceId,
            ]
        );
    }

    public function countByAccount(string $accountType, int $accountId): int {
        $result = $this->query(
            "SELECT COUNT(*) AS total FROM account_mutations WHERE account_type = :account_type AND account_id = :account_id",
            [
                ':account_type' => $accountType,
                ':account_id' => $accountId,
            ]
        );

        return (int)($result[0]['total'] ?? 0);
    }

    public function getNetByFundCategory(?string $endDate = null): array {
        $sql = "
            SELECT
                fund_category,
                COALESCE(SUM(
                    CASE
                        WHEN entry_type = 'debet' THEN amount
                        ELSE -amount
                    END
                ), 0) AS total
            FROM account_mutations
            WHERE 1 = 1
        ";
        $params = [];

        if (!empty($endDate)) {
            $sql .= " AND tanggal <= :end_date";
            $params[':end_date'] = $endDate;
        }

        $sql .= " GROUP BY fund_category ORDER BY fund_category ASC";
        return $this->query($sql, $params);
    }
}
