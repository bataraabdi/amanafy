<?php
class BankMonthlyPosting extends Model {
    protected string $table = 'bank_monthly_postings';

    public function exists(int $bankAccountId, string $postingType, string $period): bool {
        $row = $this->findOneWhere([
            'bank_account_id' => $bankAccountId,
            'posting_type' => $postingType,
            'periode_bulan' => $period,
        ]);

        return $row !== null;
    }

    public function getRecent(int $limit = 12): array {
        return $this->query("
            SELECT
                bmp.*,
                ba.nama_bank,
                ba.nomor_rekening,
                ba.nama_pemilik
            FROM bank_monthly_postings bmp
            JOIN bank_accounts ba ON bmp.bank_account_id = ba.id
            ORDER BY bmp.executed_at DESC, bmp.id DESC
            LIMIT " . (int)$limit
        );
    }
}
