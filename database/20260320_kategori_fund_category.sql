-- Update Category tables for ISAK 35 Fund Category
ALTER TABLE `kategori_pemasukan` ADD COLUMN `fund_category` ENUM('Terikat', 'Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `nama_kategori`;
ALTER TABLE `kategori_pengeluaran` ADD COLUMN `fund_category` ENUM('Terikat', 'Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat' AFTER `nama_kategori`;

-- Update specific default categories
UPDATE `kategori_pemasukan` SET `fund_category` = 'Terikat' WHERE `nama_kategori` IN ('Zakat', 'Wakaf', 'Donasi');
UPDATE `kategori_pemasukan` SET `fund_category` = 'Tidak Terikat' WHERE `nama_kategori` IN ('Infaq Jumat', 'Kotak Amal', 'Sedekah', 'Jasa Giro / Pendapatan Non-Halal');

UPDATE `kategori_pengeluaran` SET `fund_category` = 'Tidak Terikat' WHERE `nama_kategori` IN ('Operasional Masjid', 'Honor Petugas', 'Biaya Admin Bank');
UPDATE `kategori_pengeluaran` SET `fund_category` = 'Terikat' WHERE `nama_kategori` IN ('Pembangunan & Renovasi', 'Sosial');
