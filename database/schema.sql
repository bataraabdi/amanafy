-- ============================================
-- APLIKASI MANAJEMEN KEUANGAN KAS MASJID
-- Database Schema - MySQL/MariaDB
-- Versi Kas & Bank ISAK 35
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

CREATE DATABASE IF NOT EXISTS `amanafy` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `amanafy`;

-- ============================================
-- TABEL ROLES
-- ============================================
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_role` VARCHAR(50) NOT NULL,
    `hak_akses` TEXT NULL COMMENT 'JSON array of permissions',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `nama_role`, `hak_akses`) VALUES
(1, 'Super Admin', '["all"]'),
(2, 'Bendahara', '["dashboard","donatur","pemasukan","pengeluaran","kas-bank","kegiatan","donasi","laporan","zakat","kurban"]'),
(3, 'Viewer', '["public_dashboard"]');

-- ============================================
-- TABEL USERS
-- ============================================
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `no_hp` VARCHAR(20) NULL,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role_id` INT NOT NULL DEFAULT 2,
    `status` ENUM('aktif','nonaktif') DEFAULT 'aktif',
    `last_login` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`nama_lengkap`, `email`, `username`, `password`, `role_id`, `status`) VALUES
('Administrator', 'admin@masjid.id', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'aktif'),
('Bendahara', 'bendahara@masjid.id', 'bendahara', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'aktif');

-- ============================================
-- TABEL SETTINGS
-- ============================================
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NULL,
    `setting_group` VARCHAR(50) DEFAULT 'umum',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('nama_masjid', 'Masjid Al-Ikhlas', 'umum'),
('jenis_lembaga', 'masjid', 'umum'),
('alamat', 'Jl. Contoh No. 1, Kota', 'umum'),
('no_telepon', '08123456789', 'umum'),
('logo', '', 'umum'),
('status_lembaga', 'aktif', 'umum');

-- ============================================
-- TABEL AKUN KAS & BANK
-- ============================================
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

INSERT INTO `cash_accounts` (`nama_kas`, `saldo_awal`, `saldo_saat_ini`) VALUES
('Kas Tunai Utama', 0, 0);

INSERT INTO `bank_accounts` (`nama_bank`, `nomor_rekening`, `nama_pemilik`, `saldo_awal`, `saldo_saat_ini`, `bank_admin_fee`, `bank_interest`) VALUES
('Bank Syariah Indonesia', '1234567890', 'Masjid Al-Ikhlas', 0, 0, 12500, 2500);

-- ============================================
-- TABEL KATEGORI
-- ============================================
CREATE TABLE `kategori_pemasukan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(100) NOT NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori_pemasukan` (`nama_kategori`, `keterangan`) VALUES
('Infaq Kajian', 'Infaq dari kegiatan kajian rutin'),
('Infaq Jumat', 'Infaq shalat Jumat'),
('Kotak Amal', 'Pemasukan dari kotak amal'),
('Sedekah', 'Sedekah umum'),
('Donasi', 'Donasi dari donatur'),
('Wakaf', 'Wakaf tunai'),
('Zakat', 'Zakat mal dan fitrah'),
('Transfer Bank', 'Pemasukan via transfer bank'),
('Jasa Giro / Pendapatan Non-Halal', 'Pencatatan otomatis jasa giro atau pendapatan non-halal'),
('Lainnya', 'Pemasukan lainnya');

CREATE TABLE `kategori_pengeluaran` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kategori` VARCHAR(100) NOT NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori_pengeluaran` (`nama_kategori`, `keterangan`) VALUES
('Operasional Masjid', 'Listrik, air, keamanan, kebersihan'),
('Dakwah', 'Kajian, seminar, cetakan'),
('Sosial', 'Santunan, bantuan musibah'),
('Pembangunan & Renovasi', 'Pembangunan dan renovasi masjid'),
('Honor Petugas', 'Honor imam, muadzin, marbot'),
('Transport Ustadz', 'Biaya transport ustadz'),
('Biaya Admin Bank', 'Pencatatan otomatis biaya administrasi bank'),
('Lainnya', 'Pengeluaran lainnya');

-- ============================================
-- TABEL DONATUR
-- ============================================
CREATE TABLE `donatur` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_donatur` VARCHAR(150) NOT NULL,
    `no_hp` VARCHAR(20) NULL,
    `alamat` TEXT NULL,
    `jenis_donatur` ENUM('tetap','tidak_tetap') DEFAULT 'tidak_tetap',
    `catatan` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL PEMASUKAN & PENGELUARAN UMUM
-- ============================================
CREATE TABLE `pemasukan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tanggal` DATE NOT NULL,
    `donatur_id` INT NULL,
    `nama_donatur` VARCHAR(150) NULL COMMENT 'Untuk donatur non-terdaftar',
    `kategori_id` INT NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `metode_pembayaran` ENUM('tunai','transfer') DEFAULT 'tunai',
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash',
    `account_id` INT NOT NULL,
    `keterangan` TEXT NULL,
    `bukti_transfer` VARCHAR(255) NULL,
    `is_system_generated` TINYINT(1) NOT NULL DEFAULT 0,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`donatur_id`) REFERENCES `donatur`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pemasukan`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pengeluaran` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tanggal` DATE NOT NULL,
    `kategori_id` INT NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `penerima` VARCHAR(150) NULL,
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash',
    `account_id` INT NOT NULL,
    `keterangan` TEXT NULL,
    `bukti_nota` VARCHAR(255) NULL,
    `is_system_generated` TINYINT(1) NOT NULL DEFAULT 0,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pengeluaran`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL KEGIATAN
-- ============================================
CREATE TABLE `kegiatan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_kegiatan` VARCHAR(200) NOT NULL,
    `waktu_tempat` VARCHAR(255) NULL,
    `penanggung_jawab` VARCHAR(150) NULL,
    `sumber_dana` VARCHAR(255) NULL,
    `jumlah_anggaran` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `status` ENUM('aktif','selesai','dibatalkan') DEFAULT 'aktif',
    `tampil_publik` TINYINT(1) NOT NULL DEFAULT 1,
    `gambar` VARCHAR(255) NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kegiatan_pemasukan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kegiatan_id` INT NOT NULL,
    `tanggal` DATE NOT NULL,
    `uraian` VARCHAR(255) NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `metode_pembayaran` ENUM('tunai','transfer') DEFAULT 'tunai',
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash',
    `account_id` INT NOT NULL,
    `keterangan` TEXT NULL,
    `bukti_transfer` VARCHAR(255) NULL,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kegiatan_pengeluaran` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kegiatan_id` INT NOT NULL,
    `tanggal` DATE NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `penerima` VARCHAR(150) NULL,
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash',
    `account_id` INT NOT NULL,
    `keterangan` TEXT NULL,
    `bukti_nota` VARCHAR(255) NULL,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL DONASI
-- ============================================
CREATE TABLE `program_donasi` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_donasi` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NULL,
    `target_nominal` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `uraian` TEXT NULL,
    `deskripsi_lengkap` LONGTEXT NULL,
    `rekening_bank` VARCHAR(100) NULL,
    `bank_nama` VARCHAR(120) NULL,
    `no_rekening` VARCHAR(60) NULL,
    `atas_nama_rekening` VARCHAR(120) NULL,
    `qris_file` VARCHAR(255) NULL,
    `deadline` DATE NULL,
    `lokasi_kegiatan` VARCHAR(255) NULL,
    `dokumentasi_files` LONGTEXT NULL,
    `flyer_file` VARCHAR(255) NULL,
    `nomor_kontak` VARCHAR(30) NULL,
    `status` ENUM('aktif','nonaktif') DEFAULT 'aktif',
    `gambar` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_program_donasi_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `donasi_pemasukan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `program_id` INT NOT NULL,
    `tanggal` DATE NOT NULL,
    `uraian` VARCHAR(255) NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `metode_pembayaran` ENUM('tunai','transfer') DEFAULT 'tunai',
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash',
    `account_id` INT NOT NULL,
    `keterangan` TEXT NULL,
    `bukti_transfer` VARCHAR(255) NULL,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`program_id`) REFERENCES `program_donasi`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `donasi_pengeluaran` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `program_id` INT NOT NULL,
    `tanggal` DATE NOT NULL,
    `jumlah` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `uraian` VARCHAR(255) NULL,
    `fund_category` ENUM('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
    `account_type` ENUM('cash','bank') NOT NULL DEFAULT 'cash',
    `account_id` INT NOT NULL,
    `keterangan` TEXT NULL,
    `bukti_nota` VARCHAR(255) NULL,
    `user_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`program_id`) REFERENCES `program_donasi`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL TRANSFER INTERNAL & MUTASI AKUN
-- ============================================
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
    `periode_bulan` CHAR(7) NOT NULL COMMENT 'YYYY-MM',
    `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `transaction_table` ENUM('pemasukan','pengeluaran') NOT NULL,
    `transaction_id` INT NOT NULL,
    `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_bank_monthly_posting` (`bank_account_id`, `posting_type`, `periode_bulan`),
    FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL AUDIT LOG
-- ============================================
CREATE TABLE `audit_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `username` VARCHAR(50) NULL,
    `action` VARCHAR(50) NOT NULL,
    `table_name` VARCHAR(100) NULL,
    `record_id` INT NULL,
    `old_data` JSON NULL,
    `new_data` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_table` (`table_name`),
    INDEX `idx_audit_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INDEXES
-- ============================================
ALTER TABLE `pemasukan` ADD INDEX `idx_pemasukan_tanggal` (`tanggal`);
ALTER TABLE `pemasukan` ADD INDEX `idx_pemasukan_kategori` (`kategori_id`);
ALTER TABLE `pemasukan` ADD INDEX `idx_pemasukan_fund` (`fund_category`);
ALTER TABLE `pemasukan` ADD INDEX `idx_pemasukan_account` (`account_type`, `account_id`);
ALTER TABLE `pengeluaran` ADD INDEX `idx_pengeluaran_tanggal` (`tanggal`);
ALTER TABLE `pengeluaran` ADD INDEX `idx_pengeluaran_kategori` (`kategori_id`);
ALTER TABLE `pengeluaran` ADD INDEX `idx_pengeluaran_fund` (`fund_category`);
ALTER TABLE `pengeluaran` ADD INDEX `idx_pengeluaran_account` (`account_type`, `account_id`);
ALTER TABLE `donatur` ADD INDEX `idx_donatur_jenis` (`jenis_donatur`);
ALTER TABLE `internal_transfers` ADD INDEX `idx_internal_transfer_tanggal` (`tanggal`);
ALTER TABLE `internal_transfers` ADD INDEX `idx_internal_transfer_fund` (`fund_category`);

