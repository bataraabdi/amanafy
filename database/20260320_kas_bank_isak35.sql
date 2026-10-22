START TRANSACTION;

CREATE TABLE `bank_accounts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_bank` VARCHAR(120) NOT NULL,
    `nomor_rekening` VARCHAR(60) NOT NULL,
    `nama_pemilik` VARCHAR(120) NOT NULL,
    `saldo_awal` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `saldo_saat_ini` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `bank_admin_fee` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `bank_interest` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_bank_accounts_rekening` (`nomor_rekening`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cash_accounts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kas` VARCHAR(120) NOT NULL,
    `saldo_awal` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `saldo_saat_ini` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cash_accounts` (`nama_kas`, `saldo_awal`, `saldo_saat_ini`)
SELECT 'Kas Tunai Utama', 0, 0
WHERE NOT EXISTS (SELECT 1 FROM `cash_accounts`);

INSERT INTO `bank_accounts` (`nama_bank`, `nomor_rekening`, `nama_pemilik`, `saldo_awal`, `saldo_saat_ini`)
SELECT rb.`nama_bank`, rb.`no_rekening`, rb.`atas_nama`, 0, 0
FROM `rekening_bank` rb
WHERE NOT EXISTS (
    SELECT 1 FROM `bank_accounts` ba WHERE ba.`nomor_rekening` = rb.`no_rekening`
);

SET @default_cash_id := (SELECT `id` FROM `cash_accounts` ORDER BY `id` ASC LIMIT 1);
SET @default_bank_id := (SELECT `id` FROM `bank_accounts` ORDER BY `id` ASC LIMIT 1);

ALTER TABLE `pemasukan`
    ADD COLUMN `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `metode_pembayaran`,
    ADD COLUMN `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash' AFTER `fund_category`,
    ADD COLUMN `account_id` INT NULL AFTER `account_type`,
    ADD COLUMN `is_system_generated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `bukti_transfer`;

ALTER TABLE `pengeluaran`
    ADD COLUMN `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `penerima`,
    ADD COLUMN `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash' AFTER `fund_category`,
    ADD COLUMN `account_id` INT NULL AFTER `account_type`,
    ADD COLUMN `is_system_generated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `bukti_nota`;

ALTER TABLE `kegiatan_pemasukan`
    ADD COLUMN `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `metode_pembayaran`,
    ADD COLUMN `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash' AFTER `fund_category`,
    ADD COLUMN `account_id` INT NULL AFTER `account_type`;

ALTER TABLE `kegiatan_pengeluaran`
    ADD COLUMN `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `penerima`,
    ADD COLUMN `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash' AFTER `fund_category`,
    ADD COLUMN `account_id` INT NULL AFTER `account_type`;

ALTER TABLE `donasi_pemasukan`
    ADD COLUMN `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `metode_pembayaran`,
    ADD COLUMN `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash' AFTER `fund_category`,
    ADD COLUMN `account_id` INT NULL AFTER `account_type`;

ALTER TABLE `donasi_pengeluaran`
    ADD COLUMN `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `uraian`,
    ADD COLUMN `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash' AFTER `fund_category`,
    ADD COLUMN `account_id` INT NULL AFTER `account_type`;

UPDATE `pemasukan`
SET
    `fund_category` = 'Tidak Terikat',
    `account_type` = CASE
        WHEN `metode_pembayaran` = 'transfer' AND @default_bank_id IS NOT NULL THEN 'bank'
        ELSE 'cash'
    END,
    `account_id` = CASE
        WHEN `metode_pembayaran` = 'transfer' AND @default_bank_id IS NOT NULL THEN @default_bank_id
        ELSE @default_cash_id
    END;

UPDATE `pengeluaran`
SET
    `fund_category` = 'Tidak Terikat',
    `account_type` = CASE
        WHEN @default_bank_id IS NOT NULL AND `kategori_id` IN (SELECT `id` FROM `kategori_pengeluaran` WHERE `nama_kategori` = 'Biaya Admin Bank') THEN 'bank'
        ELSE 'cash'
    END,
    `account_id` = CASE
        WHEN @default_bank_id IS NOT NULL AND `kategori_id` IN (SELECT `id` FROM `kategori_pengeluaran` WHERE `nama_kategori` = 'Biaya Admin Bank') THEN @default_bank_id
        ELSE @default_cash_id
    END;

UPDATE `kegiatan_pemasukan`
SET
    `fund_category` = 'Tidak Terikat',
    `account_type` = CASE
        WHEN `metode_pembayaran` = 'transfer' AND @default_bank_id IS NOT NULL THEN 'bank'
        ELSE 'cash'
    END,
    `account_id` = CASE
        WHEN `metode_pembayaran` = 'transfer' AND @default_bank_id IS NOT NULL THEN @default_bank_id
        ELSE @default_cash_id
    END;

UPDATE `kegiatan_pengeluaran`
SET
    `fund_category` = 'Tidak Terikat',
    `account_type` = 'cash',
    `account_id` = @default_cash_id;

UPDATE `donasi_pemasukan`
SET
    `fund_category` = 'Tidak Terikat',
    `account_type` = CASE
        WHEN `metode_pembayaran` = 'transfer' AND @default_bank_id IS NOT NULL THEN 'bank'
        ELSE 'cash'
    END,
    `account_id` = CASE
        WHEN `metode_pembayaran` = 'transfer' AND @default_bank_id IS NOT NULL THEN @default_bank_id
        ELSE @default_cash_id
    END;

UPDATE `donasi_pengeluaran`
SET
    `fund_category` = 'Tidak Terikat',
    `account_type` = 'cash',
    `account_id` = @default_cash_id;

INSERT INTO `kategori_pemasukan` (`nama_kategori`, `keterangan`)
SELECT 'Jasa Giro / Pendapatan Non-Halal', 'Pencatatan otomatis jasa giro atau pendapatan non-halal'
WHERE NOT EXISTS (SELECT 1 FROM `kategori_pemasukan` WHERE `nama_kategori` = 'Jasa Giro / Pendapatan Non-Halal');

INSERT INTO `kategori_pengeluaran` (`nama_kategori`, `keterangan`)
SELECT 'Biaya Admin Bank', 'Pencatatan otomatis biaya administrasi bank'
WHERE NOT EXISTS (SELECT 1 FROM `kategori_pengeluaran` WHERE `nama_kategori` = 'Biaya Admin Bank');

CREATE TABLE `internal_transfers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tanggal` DATE NOT NULL,
    `akun_asal_type` ENUM('cash','bank') NOT NULL,
    `akun_asal_id` INT NOT NULL,
    `akun_tujuan_type` ENUM('cash','bank') NOT NULL,
    `akun_tujuan_id` INT NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `keterangan` TEXT NULL,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `account_mutations` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `tanggal` DATE NOT NULL,
    `reference_table` VARCHAR(100) NOT NULL,
    `reference_id` INT NOT NULL,
    `account_type` ENUM('cash','bank') NOT NULL,
    `account_id` INT NOT NULL,
    `entry_type` ENUM('debet','kredit') NOT NULL,
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `description` VARCHAR(255) NULL,
    `user_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_account_mutations_reference` (`reference_table`, `reference_id`),
    INDEX `idx_account_mutations_account` (`account_type`, `account_id`),
    INDEX `idx_account_mutations_tanggal` (`tanggal`),
    INDEX `idx_account_mutations_fund` (`fund_category`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `bank_monthly_postings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `bank_account_id` INT NOT NULL,
    `posting_type` ENUM('bank_admin_fee','bank_interest') NOT NULL,
    `periode_bulan` CHAR(7) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `transaction_table` ENUM('pemasukan','pengeluaran') NOT NULL,
    `transaction_id` INT NOT NULL,
    `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_bank_monthly_posting` (`bank_account_id`, `posting_type`, `periode_bulan`),
    FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `account_mutations`
(`tanggal`, `reference_table`, `reference_id`, `account_type`, `account_id`, `entry_type`, `fund_category`, `amount`, `description`, `user_id`)
SELECT `tanggal`, 'pemasukan', `id`, `account_type`, `account_id`, 'debet', `fund_category`, `jumlah`, 'Migrasi pemasukan', `user_id`
FROM `pemasukan`;

INSERT INTO `account_mutations`
(`tanggal`, `reference_table`, `reference_id`, `account_type`, `account_id`, `entry_type`, `fund_category`, `amount`, `description`, `user_id`)
SELECT `tanggal`, 'pengeluaran', `id`, `account_type`, `account_id`, 'kredit', `fund_category`, `jumlah`, 'Migrasi pengeluaran', `user_id`
FROM `pengeluaran`;

INSERT INTO `account_mutations`
(`tanggal`, `reference_table`, `reference_id`, `account_type`, `account_id`, `entry_type`, `fund_category`, `amount`, `description`, `user_id`)
SELECT `tanggal`, 'kegiatan_pemasukan', `id`, `account_type`, `account_id`, 'debet', `fund_category`, `jumlah`, 'Migrasi pemasukan kegiatan', `user_id`
FROM `kegiatan_pemasukan`;

INSERT INTO `account_mutations`
(`tanggal`, `reference_table`, `reference_id`, `account_type`, `account_id`, `entry_type`, `fund_category`, `amount`, `description`, `user_id`)
SELECT `tanggal`, 'kegiatan_pengeluaran', `id`, `account_type`, `account_id`, 'kredit', `fund_category`, `jumlah`, 'Migrasi pengeluaran kegiatan', `user_id`
FROM `kegiatan_pengeluaran`;

INSERT INTO `account_mutations`
(`tanggal`, `reference_table`, `reference_id`, `account_type`, `account_id`, `entry_type`, `fund_category`, `amount`, `description`, `user_id`)
SELECT `tanggal`, 'donasi_pemasukan', `id`, `account_type`, `account_id`, 'debet', `fund_category`, `jumlah`, 'Migrasi pemasukan donasi', `user_id`
FROM `donasi_pemasukan`;

INSERT INTO `account_mutations`
(`tanggal`, `reference_table`, `reference_id`, `account_type`, `account_id`, `entry_type`, `fund_category`, `amount`, `description`, `user_id`)
SELECT `tanggal`, 'donasi_pengeluaran', `id`, `account_type`, `account_id`, 'kredit', `fund_category`, `jumlah`, 'Migrasi pengeluaran donasi', `user_id`
FROM `donasi_pengeluaran`;

UPDATE `cash_accounts` ca
SET `saldo_saat_ini` = `saldo_awal` + COALESCE((
    SELECT SUM(CASE WHEN am.`entry_type` = 'debet' THEN am.`amount` ELSE -am.`amount` END)
    FROM `account_mutations` am
    WHERE am.`account_type` = 'cash' AND am.`account_id` = ca.`id`
), 0);

UPDATE `bank_accounts` ba
SET `saldo_saat_ini` = `saldo_awal` + COALESCE((
    SELECT SUM(CASE WHEN am.`entry_type` = 'debet' THEN am.`amount` ELSE -am.`amount` END)
    FROM `account_mutations` am
    WHERE am.`account_type` = 'bank' AND am.`account_id` = ba.`id`
), 0);

COMMIT;
