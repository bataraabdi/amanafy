-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 10:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amanafy`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action` varchar(50) NOT NULL COMMENT 'CREATE, UPDATE, DELETE, LOGIN, LOGOUT',
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `username`, `action`, `table_name`, `record_id`, `old_data`, `new_data`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 05:12:25'),
(2, 1, 'admin', 'CREATE', 'program_donasi', 1, NULL, '{\"nama_donasi\":\"Pembangunan Teras Masjid\",\"target_nominal\":150000000,\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"status\":\"aktif\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 05:14:46'),
(3, 1, 'admin', 'CREATE', 'donatur', 1, NULL, '{\"nama_donatur\":\"Hamba Allah\",\"no_hp\":\"08222222\",\"alamat\":\"alamat\",\"jenis_donatur\":\"tidak_tetap\",\"catatan\":\"catatan tambahan\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 05:15:30'),
(4, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"update_umum\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 05:18:18'),
(5, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"add_bank\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 05:36:40'),
(6, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"update_umum\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:09:31'),
(7, 1, 'admin', 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:13:30'),
(8, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:21:37'),
(9, 1, 'admin', 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:23:27'),
(10, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:23:36'),
(11, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"add_kategori_pemasukan\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:28:41'),
(12, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"delete_kategori_pemasukan\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:31:10'),
(13, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"add_kategori_pengeluaran\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:32:38'),
(14, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"delete_kategori_pemasukan\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:33:22'),
(15, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"delete_kategori_pengeluaran\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:35:36'),
(16, 1, 'admin', 'UPDATE', 'settings', NULL, NULL, '{\"action\":\"delete_kategori_pengeluaran\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:48:12'),
(17, 1, 'admin', 'UPDATE', 'users', 1, '{\"id\":1,\"nama_lengkap\":\"Administrator\",\"email\":\"admin@masjid.id\",\"no_hp\":null,\"username\":\"admin\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"role_id\":1,\"status\":\"aktif\",\"last_login\":\"2026-03-18 13:23:36\",\"created_at\":\"2026-03-18 11:59:04\",\"updated_at\":\"2026-03-18 13:23:36\"}', '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:48:50'),
(18, 1, 'admin', 'CREATE', 'users', 3, NULL, '{\"username\":\"batara\",\"role_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:49:24'),
(19, 1, 'admin', 'UPDATE', 'program_donasi', 1, '{\"id\":1,\"nama_donasi\":\"Pembangunan Teras Masjid\",\"target_nominal\":\"150000000.00\",\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"status\":\"aktif\",\"gambar\":null,\"created_at\":\"2026-03-18 12:14:46\",\"updated_at\":\"2026-03-18 12:14:46\"}', '{\"nama_donasi\":\"Pembangunan Teras Masjid\",\"target_nominal\":150000000,\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"status\":\"aktif\",\"gambar\":\"69ba4c48a0c19_1773816904.png\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:55:04'),
(20, 1, 'admin', 'CREATE', 'donasi_pemasukan', 1, NULL, '{\"program_id\":1,\"tanggal\":\"2026-03-18\",\"uraian\":\"Infaq Kajian Masjid\",\"jumlah\":2500000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:55:56'),
(21, 1, 'admin', 'CREATE', 'pemasukan', 1, NULL, '{\"tanggal\":\"2026-03-18\",\"donatur_id\":null,\"nama_donatur\":\"Infaq Kajian Masjid Raya\",\"kategori_id\":1,\"jumlah\":875000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:57:28'),
(22, 1, 'admin', 'CREATE', 'donasi_pengeluaran', 1, NULL, '{\"program_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":525000,\"uraian\":\"Pembelian Semen 20 sak\",\"keterangan\":\"\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:58:17'),
(23, 1, 'admin', 'CREATE', 'pengeluaran', 1, NULL, '{\"tanggal\":\"2026-03-18\",\"kategori_id\":1,\"jumlah\":100000,\"penerima\":\"Mail\",\"keterangan\":\"Uang Kebersihan\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:59:06'),
(24, 1, 'admin', 'CREATE', 'pengeluaran', 2, NULL, '{\"tanggal\":\"2026-03-18\",\"kategori_id\":6,\"jumlah\":200000,\"penerima\":\"Ustadz Fulan\",\"keterangan\":\"Kajian Masjid Raya\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 06:59:26'),
(25, 1, 'admin', 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:16:17'),
(26, 3, 'batara', 'LOGIN', 'users', 3, NULL, '{\"username\":\"batara\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:16:30'),
(27, 3, 'batara', 'LOGOUT', 'users', 3, NULL, '{\"username\":\"batara\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:18:10'),
(28, 3, 'batara', 'LOGIN', 'users', 3, NULL, '{\"username\":\"batara\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:18:22'),
(29, 3, 'batara', 'DELETE', 'users', 2, '{\"id\":2,\"nama_lengkap\":\"Bendahara\",\"email\":\"bendahara@masjid.id\",\"no_hp\":null,\"username\":\"bendahara\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"role_id\":2,\"status\":\"aktif\",\"last_login\":null,\"created_at\":\"2026-03-18 11:59:04\",\"updated_at\":\"2026-03-18 11:59:04\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:19:05'),
(30, 3, 'batara', 'CREATE', 'users', 4, NULL, '{\"username\":\"bendahara\",\"role_id\":2}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:19:29'),
(31, 4, 'bendahara', 'LOGIN', 'users', 4, NULL, '{\"username\":\"bendahara\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:19:55'),
(32, 4, 'bendahara', 'CREATE', 'kegiatan', 1, NULL, '{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"status\":\"aktif\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:21:12'),
(33, 4, 'bendahara', 'CREATE', 'kegiatan_pemasukan', 1, NULL, '{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"uraian\":\"Pemindahan Uang Infaq\",\"jumlah\":300000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"user_id\":4}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 07:22:32'),
(34, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:50:49'),
(35, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:57:08'),
(36, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:57:08'),
(37, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:57:43'),
(38, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:57:43'),
(39, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:58:41'),
(40, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5', '2026-03-18 07:58:41'),
(41, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 09:18:43'),
(42, 1, 'admin', 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 09:21:45'),
(43, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 09:35:40'),
(44, 1, 'admin', 'UPDATE', 'kegiatan', 1, '{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":\"0.00\",\"status\":\"aktif\",\"gambar\":null,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 14:21:12\",\"total_pemasukan\":\"300000.00\",\"total_pengeluaran\":\"0.00\",\"sisa_anggaran\":\"0.00\"}', '{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":5000000,\"status\":\"aktif\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"gambar\":\"69ba770631339_1773827846.png\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 09:57:26'),
(45, 1, 'admin', 'CREATE', 'kegiatan_pengeluaran', 1, NULL, '{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":500000,\"penerima\":\"Toko A\",\"keterangan\":\"pembelian minuman gelas 10 kotak\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 09:58:22'),
(46, 1, 'admin', 'UPDATE', 'kegiatan_pemasukan', 1, '{\"id\":1,\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"uraian\":\"Pemindahan Uang Infaq\",\"jumlah\":\"300000.00\",\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"bukti_transfer\":null,\"user_id\":4,\"created_at\":\"2026-03-18 14:22:32\",\"updated_at\":\"2026-03-18 14:22:32\"}', '{\"tanggal\":\"2026-03-18\",\"uraian\":\"Uang Infaq\",\"jumlah\":350000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 09:58:45'),
(47, 1, 'admin', 'UPDATE', 'kegiatan', 1, '{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":\"5000000.00\",\"status\":\"aktif\",\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 16:57:26\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"500000.00\",\"sisa_anggaran\":\"4500000.00\"}', '{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":5500000,\"status\":\"aktif\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 10:02:36'),
(48, 1, 'admin', 'UPDATE', 'kegiatan', 1, '{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":null,\"penanggung_jawab\":null,\"sumber_dana\":null,\"jumlah_anggaran\":\"5500000.00\",\"status\":\"aktif\",\"tampil_publik\":1,\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 17:02:36\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"500000.00\",\"total_anggaran\":\"5850000.00\",\"sisa_anggaran\":\"5350000.00\"}', '{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":5500000,\"tampil_publik\":1,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 10:30:20'),
(49, 1, 'admin', 'CREATE', 'kegiatan_pengeluaran', 2, NULL, '{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":100000,\"penerima\":\"Juru Parkir\",\"keterangan\":\"Jasa Penjaga Parkir\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 10:31:16'),
(50, 1, 'admin', 'CREATE', 'kegiatan_pengeluaran', 3, NULL, '{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":100000,\"penerima\":\"Tukang Kebersihan\",\"keterangan\":\"Kebersihan Masjid\",\"user_id\":1}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 10:31:46'),
(51, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 11:02:52'),
(52, 1, 'admin', 'UPDATE', 'kegiatan', 1, '{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":\"5500000.00\",\"status\":\"aktif\",\"tampil_publik\":1,\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 17:30:20\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"700000.00\",\"total_anggaran\":\"5850000.00\",\"sisa_anggaran\":\"5150000.00\"}', '{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":5500000,\"tampil_publik\":0,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 11:03:18'),
(53, 1, 'admin', 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 11:38:46'),
(54, 1, 'admin', 'UPDATE', 'program_donasi', 1, '{\"id\":1,\"nama_donasi\":\"Pembangunan Teras Masjid\",\"slug\":\"pembangunan-teras-masjid\",\"target_nominal\":\"150000000.00\",\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"deskripsi_lengkap\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"bank_nama\":\"Bank BSI No. Rek : 0922839292\",\"no_rekening\":null,\"atas_nama_rekening\":null,\"qris_file\":null,\"deadline\":null,\"lokasi_kegiatan\":null,\"dokumentasi_files\":null,\"flyer_file\":null,\"nomor_kontak\":null,\"status\":\"aktif\",\"gambar\":\"69ba4c48a0c19_1773816904.png\",\"created_at\":\"2026-03-18 12:14:46\",\"updated_at\":\"2026-03-18 18:36:06\"}', '{\"nama_donasi\":\"Pembangunan Teras Masjid\",\"slug\":\"pembangunan-teras-masjid\",\"target_nominal\":150000000,\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"deskripsi_lengkap\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI | No. Rek 0922839292 | a.n. Masjid Al Ikhlas\",\"bank_nama\":\"Bank BSI\",\"no_rekening\":\"0922839292\",\"atas_nama_rekening\":\"Masjid Al Ikhlas\",\"qris_file\":\"\",\"deadline\":null,\"lokasi_kegiatan\":\"Binjai Utara - Binjai\",\"dokumentasi_files\":\"[]\",\"flyer_file\":\"\",\"nomor_kontak\":\"085359090207\",\"status\":\"aktif\",\"gambar\":\"69ba4c48a0c19_1773816904.png\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2026-03-18 11:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `donasi_pemasukan`
--

CREATE TABLE `donasi_pemasukan` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi_pemasukan`
--

INSERT INTO `donasi_pemasukan` (`id`, `program_id`, `tanggal`, `uraian`, `jumlah`, `metode_pembayaran`, `keterangan`, `bukti_transfer`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-18', 'Infaq Kajian Masjid', 2500000.00, 'tunai', '', NULL, 1, '2026-03-18 06:55:56', '2026-03-18 06:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `donasi_pengeluaran`
--

CREATE TABLE `donasi_pengeluaran` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `uraian` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_nota` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donasi_pengeluaran`
--

INSERT INTO `donasi_pengeluaran` (`id`, `program_id`, `tanggal`, `jumlah`, `uraian`, `keterangan`, `bukti_nota`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-18', 525000.00, 'Pembelian Semen 20 sak', '', NULL, 1, '2026-03-18 06:58:17', '2026-03-18 06:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `donatur`
--

CREATE TABLE `donatur` (
  `id` int(11) NOT NULL,
  `nama_donatur` varchar(150) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_donatur` enum('tetap','tidak_tetap') DEFAULT 'tidak_tetap',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donatur`
--

INSERT INTO `donatur` (`id`, `nama_donatur`, `no_hp`, `alamat`, `jenis_donatur`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'Hamba Allah', '08222222', 'alamat', 'tidak_tetap', 'catatan tambahan', '2026-03-18 05:15:30', '2026-03-18 05:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pemasukan`
--

CREATE TABLE `kategori_pemasukan` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_pemasukan`
--

INSERT INTO `kategori_pemasukan` (`id`, `nama_kategori`, `keterangan`, `created_at`) VALUES
(1, 'Infaq Kajian', 'Infaq dari kegiatan kajian rutin', '2026-03-18 04:59:04'),
(2, 'Infaq Jumat', 'Infaq shalat Jumat', '2026-03-18 04:59:04'),
(3, 'Kotak Amal', 'Pemasukan dari kotak amal', '2026-03-18 04:59:04'),
(4, 'Sedekah', 'Sedekah umum', '2026-03-18 04:59:04'),
(5, 'Donasi', 'Donasi dari donatur', '2026-03-18 04:59:04'),
(6, 'Wakaf', 'Wakaf tunai', '2026-03-18 04:59:04'),
(7, 'Zakat', 'Zakat mal dan fitrah', '2026-03-18 04:59:04'),
(8, 'Transfer Bank', 'Pemasukan via transfer bank', '2026-03-18 04:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pengeluaran`
--

CREATE TABLE `kategori_pengeluaran` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_pengeluaran`
--

INSERT INTO `kategori_pengeluaran` (`id`, `nama_kategori`, `keterangan`, `created_at`) VALUES
(1, 'Operasional Masjid', 'Listrik, air, keamanan, kebersihan', '2026-03-18 04:59:04'),
(2, 'Dakwah', 'Kajian, seminar, cetakan', '2026-03-18 04:59:04'),
(3, 'Sosial', 'Santunan, bantuan musibah', '2026-03-18 04:59:04'),
(4, 'Pembangunan & Renovasi', 'Pembangunan dan renovasi masjid', '2026-03-18 04:59:04'),
(5, 'Honor Petugas', 'Honor imam, muadzin, marbot', '2026-03-18 04:59:04'),
(6, 'Transport Ustadz', 'Biaya transport ustadz', '2026-03-18 04:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(200) NOT NULL,
  `waktu_tempat` varchar(255) DEFAULT NULL,
  `penanggung_jawab` varchar(150) DEFAULT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `jumlah_anggaran` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('aktif','selesai','dibatalkan') DEFAULT 'aktif',
  `tampil_publik` tinyint(1) NOT NULL DEFAULT 1,
  `gambar` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `nama_kegiatan`, `waktu_tempat`, `penanggung_jawab`, `sumber_dana`, `jumlah_anggaran`, `status`, `tampil_publik`, `gambar`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Tabligh Akbar Ustadz Jakarta', 'Masjid Raya Binjai', 'Batara', 'Infaq Kajian Rutin', 5500000.00, 'aktif', 0, '69ba770631339_1773827846.png', 'di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026', '2026-03-18 07:21:12', '2026-03-18 11:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_pemasukan`
--

CREATE TABLE `kegiatan_pemasukan` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_pemasukan`
--

INSERT INTO `kegiatan_pemasukan` (`id`, `kegiatan_id`, `tanggal`, `uraian`, `jumlah`, `metode_pembayaran`, `keterangan`, `bukti_transfer`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-18', 'Uang Infaq', 350000.00, 'tunai', '', NULL, 4, '2026-03-18 07:22:32', '2026-03-18 09:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_pengeluaran`
--

CREATE TABLE `kegiatan_pengeluaran` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penerima` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_nota` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan_pengeluaran`
--

INSERT INTO `kegiatan_pengeluaran` (`id`, `kegiatan_id`, `tanggal`, `jumlah`, `penerima`, `keterangan`, `bukti_nota`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-18', 500000.00, 'Toko A', 'pembelian minuman gelas 10 kotak', NULL, 1, '2026-03-18 09:58:22', '2026-03-18 09:58:22'),
(2, 1, '2026-03-18', 100000.00, 'Juru Parkir', 'Jasa Penjaga Parkir', NULL, 1, '2026-03-18 10:31:16', '2026-03-18 10:31:16'),
(3, 1, '2026-03-18', 100000.00, 'Tukang Kebersihan', 'Kebersihan Masjid', NULL, 1, '2026-03-18 10:31:46', '2026-03-18 10:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `donatur_id` int(11) DEFAULT NULL,
  `nama_donatur` varchar(150) DEFAULT NULL COMMENT 'Untuk donatur non-terdaftar',
  `kategori_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Petugas input',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `tanggal`, `donatur_id`, `nama_donatur`, `kategori_id`, `jumlah`, `metode_pembayaran`, `keterangan`, `bukti_transfer`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '2026-03-18', NULL, 'Infaq Kajian Masjid Raya', 1, 875000.00, 'tunai', '', NULL, 1, '2026-03-18 06:57:28', '2026-03-18 06:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penerima` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_nota` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Petugas input',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `tanggal`, `kategori_id`, `jumlah`, `penerima`, `keterangan`, `bukti_nota`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '2026-03-18', 1, 100000.00, 'Mail', 'Uang Kebersihan', NULL, 1, '2026-03-18 06:59:06', '2026-03-18 06:59:06'),
(2, '2026-03-18', 6, 200000.00, 'Ustadz Fulan', 'Kajian Masjid Raya', NULL, 1, '2026-03-18 06:59:26', '2026-03-18 06:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `program_donasi`
--

CREATE TABLE `program_donasi` (
  `id` int(11) NOT NULL,
  `nama_donasi` varchar(200) NOT NULL,
  `slug` varchar(220) DEFAULT NULL,
  `target_nominal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `uraian` text DEFAULT NULL,
  `deskripsi_lengkap` longtext DEFAULT NULL,
  `rekening_bank` varchar(100) DEFAULT NULL,
  `bank_nama` varchar(120) DEFAULT NULL,
  `no_rekening` varchar(60) DEFAULT NULL,
  `atas_nama_rekening` varchar(120) DEFAULT NULL,
  `qris_file` varchar(255) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `lokasi_kegiatan` varchar(255) DEFAULT NULL,
  `dokumentasi_files` longtext DEFAULT NULL,
  `flyer_file` varchar(255) DEFAULT NULL,
  `nomor_kontak` varchar(30) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_donasi`
--

INSERT INTO `program_donasi` (`id`, `nama_donasi`, `slug`, `target_nominal`, `uraian`, `deskripsi_lengkap`, `rekening_bank`, `bank_nama`, `no_rekening`, `atas_nama_rekening`, `qris_file`, `deadline`, `lokasi_kegiatan`, `dokumentasi_files`, `flyer_file`, `nomor_kontak`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Pembangunan Teras Masjid', 'pembangunan-teras-masjid', 150000000.00, 'pembangunan teras ukuran 10 x 30 meter', 'pembangunan teras ukuran 10 x 30 meter', 'Bank BSI | No. Rek 0922839292 | a.n. Masjid Al Ikhlas', 'Bank BSI', '0922839292', 'Masjid Al Ikhlas', '', NULL, 'Binjai Utara - Binjai', '[]', '', '085359090207', 'aktif', '69ba4c48a0c19_1773816904.png', '2026-03-18 05:14:46', '2026-03-18 11:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `rekening_bank`
--

CREATE TABLE `rekening_bank` (
  `id` int(11) NOT NULL,
  `nama_bank` varchar(100) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `atas_nama` varchar(100) NOT NULL,
  `qris` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekening_bank`
--

INSERT INTO `rekening_bank` (`id`, `nama_bank`, `no_rekening`, `atas_nama`, `qris`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Bank Default', '0000', 'Admin', NULL, 'Rekening Utama', '2026-03-18 05:31:05', '2026-03-18 05:31:05'),
(2, 'Bank BSI', '0979222200', 'Masjid Al Ikhlas', NULL, 'Rekening Infaq Umum', '2026-03-18 05:36:40', '2026-03-18 05:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nama_role` varchar(50) NOT NULL,
  `hak_akses` text DEFAULT NULL COMMENT 'JSON array of permissions',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nama_role`, `hak_akses`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', '[\"all\"]', '2026-03-18 04:59:04', '2026-03-18 04:59:04'),
(2, 'Bendahara', '[\"dashboard\",\"donatur\",\"pemasukan\",\"pengeluaran\",\"kegiatan\",\"donasi\",\"laporan\",\"zakat\",\"kurban\"]', '2026-03-18 04:59:04', '2026-03-18 04:59:04'),
(3, 'Viewer', '[\"public_dashboard\"]', '2026-03-18 04:59:04', '2026-03-18 04:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'umum',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `updated_at`) VALUES
(1, 'nama_masjid', 'Masjid Al-Ikhlas', 'umum', '2026-03-18 04:59:04'),
(2, 'jenis_lembaga', 'masjid', 'umum', '2026-03-18 04:59:04'),
(3, 'alamat', 'Jl. Soekarno - Hatta, Binjai', 'umum', '2026-03-18 06:09:31'),
(4, 'no_telepon', '08123456789', 'umum', '2026-03-18 04:59:04'),
(5, 'logo', '69ba359a9ea34_1773811098.png', 'umum', '2026-03-18 05:18:18'),
(6, 'status_lembaga', 'aktif', 'umum', '2026-03-18 04:59:04'),
(11, 'zakat_beras_per_jiwa', '2.5', 'zakat', '2026-03-18 04:59:04'),
(12, 'zakat_uang_per_jiwa', '45000', 'zakat', '2026-03-18 04:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `email`, `no_hp`, `username`, `password`, `role_id`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@masjid.id', '08222222', 'admin', '$2y$10$FXNsX9RanBviVnfe8SNdpuDrj0DQv9Zz3mgDVFSuk33.3DR2I/1sK', 1, 'aktif', '2026-03-18 11:38:46', '2026-03-18 04:59:04', '2026-03-18 11:38:46'),
(3, 'Batara', 'alif.tara@gmail.com', '08535909', 'batara', '$2y$10$jIwudBKUaGcUiY7x3NOwlOvKDSXzy7jJAYJquJQZy8DqX60SdGMYe', 1, 'aktif', '2026-03-18 07:18:22', '2026-03-18 06:49:24', '2026-03-18 07:18:22'),
(4, 'Bendahara', 'bendahara@gmail.com', '08222222', 'bendahara', '$2y$10$wnyw6JTksVViBDwsHoY2DOPkWbSuZkoKMQFS5N6Jw9E2H94HQDb5q', 2, 'aktif', '2026-03-18 07:19:55', '2026-03-18 07:19:28', '2026-03-18 07:19:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_user` (`user_id`),
  ADD KEY `idx_audit_action` (`action`),
  ADD KEY `idx_audit_table` (`table_name`),
  ADD KEY `idx_audit_date` (`created_at`);

--
-- Indexes for table `donasi_pemasukan`
--
ALTER TABLE `donasi_pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donasi_pengeluaran`
--
ALTER TABLE `donasi_pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donatur`
--
ALTER TABLE `donatur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_donatur_jenis` (`jenis_donatur`);

--
-- Indexes for table `kategori_pemasukan`
--
ALTER TABLE `kategori_pemasukan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_pengeluaran`
--
ALTER TABLE `kategori_pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan_pemasukan`
--
ALTER TABLE `kegiatan_pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kegiatan_id` (`kegiatan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kegiatan_pengeluaran`
--
ALTER TABLE `kegiatan_pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kegiatan_id` (`kegiatan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donatur_id` (`donatur_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_pemasukan_tanggal` (`tanggal`),
  ADD KEY `idx_pemasukan_kategori` (`kategori_id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_pengeluaran_tanggal` (`tanggal`),
  ADD KEY `idx_pengeluaran_kategori` (`kategori_id`);

--
-- Indexes for table `program_donasi`
--
ALTER TABLE `program_donasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_program_donasi_slug` (`slug`);

--
-- Indexes for table `rekening_bank`
--
ALTER TABLE `rekening_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `donasi_pemasukan`
--
ALTER TABLE `donasi_pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donasi_pengeluaran`
--
ALTER TABLE `donasi_pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donatur`
--
ALTER TABLE `donatur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori_pemasukan`
--
ALTER TABLE `kategori_pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategori_pengeluaran`
--
ALTER TABLE `kategori_pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kegiatan_pemasukan`
--
ALTER TABLE `kegiatan_pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kegiatan_pengeluaran`
--
ALTER TABLE `kegiatan_pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `program_donasi`
--
ALTER TABLE `program_donasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rekening_bank`
--
ALTER TABLE `rekening_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donasi_pemasukan`
--
ALTER TABLE `donasi_pemasukan`
  ADD CONSTRAINT `donasi_pemasukan_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program_donasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donasi_pemasukan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `donasi_pengeluaran`
--
ALTER TABLE `donasi_pengeluaran`
  ADD CONSTRAINT `donasi_pengeluaran_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program_donasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donasi_pengeluaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kegiatan_pemasukan`
--
ALTER TABLE `kegiatan_pemasukan`
  ADD CONSTRAINT `kegiatan_pemasukan_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kegiatan_pemasukan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kegiatan_pengeluaran`
--
ALTER TABLE `kegiatan_pengeluaran`
  ADD CONSTRAINT `kegiatan_pengeluaran_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kegiatan_pengeluaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`donatur_id`) REFERENCES `donatur` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pemasukan_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pemasukan` (`id`),
  ADD CONSTRAINT `pemasukan_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pengeluaran` (`id`),
  ADD CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
