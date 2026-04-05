-- Backup Database: amanafy
-- Date: 2026-03-27 07:10:34
-- ========================================

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `account_mutations`;
CREATE TABLE `account_mutations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `reference_table` varchar(50) NOT NULL,
  `reference_id` int(11) NOT NULL,
  `account_type` enum('cash','bank') NOT NULL,
  `account_id` int(11) NOT NULL,
  `entry_type` enum('debet','kredit') NOT NULL,
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_mutation_ref` (`reference_table`,`reference_id`),
  KEY `idx_mutation_acc` (`account_type`,`account_id`),
  KEY `idx_mutation_date` (`tanggal`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `account_mutations` VALUES('1','2026-03-20','pengeluaran','3','cash','2','kredit','Tidak Terikat','350000.00','Kredit pengeluaran kas','1','2026-03-20 11:44:17');
INSERT INTO `account_mutations` VALUES('2','2026-03-18','pemasukan','1','cash','2','debet','Tidak Terikat','875000.00','Debet pemasukan kas','1','2026-03-20 13:55:41');
INSERT INTO `account_mutations` VALUES('3','2026-03-18','pengeluaran','1','cash','2','kredit','Tidak Terikat','100000.00','Kredit pengeluaran kas','1','2026-03-20 13:56:02');
INSERT INTO `account_mutations` VALUES('4','2026-03-18','pengeluaran','2','cash','2','kredit','Tidak Terikat','200000.00','Kredit pengeluaran kas','1','2026-03-20 13:56:06');
INSERT INTO `account_mutations` VALUES('5','2026-03-31','pengeluaran','4','bank','2','kredit','Tidak Terikat','1500.00','Kredit biaya admin bank','1','2026-03-25 21:34:17');

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action` varchar(50) NOT NULL COMMENT 'CREATE, UPDATE, DELETE, LOGIN, LOGOUT',
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_user` (`user_id`),
  KEY `idx_audit_action` (`action`),
  KEY `idx_audit_table` (`table_name`),
  KEY `idx_audit_date` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `audit_log` VALUES('1','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 12:12:25');
INSERT INTO `audit_log` VALUES('2','1','admin','CREATE','program_donasi','1',NULL,'{\"nama_donasi\":\"Pembangunan Teras Masjid\",\"target_nominal\":150000000,\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"status\":\"aktif\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 12:14:46');
INSERT INTO `audit_log` VALUES('3','1','admin','CREATE','donatur','1',NULL,'{\"nama_donatur\":\"Hamba Allah\",\"no_hp\":\"08222222\",\"alamat\":\"alamat\",\"jenis_donatur\":\"tidak_tetap\",\"catatan\":\"catatan tambahan\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 12:15:30');
INSERT INTO `audit_log` VALUES('4','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"update_umum\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 12:18:18');
INSERT INTO `audit_log` VALUES('5','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"add_bank\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 12:36:40');
INSERT INTO `audit_log` VALUES('6','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"update_umum\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:09:31');
INSERT INTO `audit_log` VALUES('7','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:13:30');
INSERT INTO `audit_log` VALUES('8','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:21:37');
INSERT INTO `audit_log` VALUES('9','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:23:27');
INSERT INTO `audit_log` VALUES('10','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:23:36');
INSERT INTO `audit_log` VALUES('11','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"add_kategori_pemasukan\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:28:41');
INSERT INTO `audit_log` VALUES('12','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"delete_kategori_pemasukan\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:31:10');
INSERT INTO `audit_log` VALUES('13','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"add_kategori_pengeluaran\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:32:38');
INSERT INTO `audit_log` VALUES('14','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"delete_kategori_pemasukan\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:33:22');
INSERT INTO `audit_log` VALUES('15','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"delete_kategori_pengeluaran\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:35:36');
INSERT INTO `audit_log` VALUES('16','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"delete_kategori_pengeluaran\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:48:12');
INSERT INTO `audit_log` VALUES('17','1','admin','UPDATE','users','1','{\"id\":1,\"nama_lengkap\":\"Administrator\",\"email\":\"admin@masjid.id\",\"no_hp\":null,\"username\":\"admin\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"role_id\":1,\"status\":\"aktif\",\"last_login\":\"2026-03-18 13:23:36\",\"created_at\":\"2026-03-18 11:59:04\",\"updated_at\":\"2026-03-18 13:23:36\"}','{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:48:50');
INSERT INTO `audit_log` VALUES('18','1','admin','CREATE','users','3',NULL,'{\"username\":\"batara\",\"role_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:49:24');
INSERT INTO `audit_log` VALUES('19','1','admin','UPDATE','program_donasi','1','{\"id\":1,\"nama_donasi\":\"Pembangunan Teras Masjid\",\"target_nominal\":\"150000000.00\",\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"status\":\"aktif\",\"gambar\":null,\"created_at\":\"2026-03-18 12:14:46\",\"updated_at\":\"2026-03-18 12:14:46\"}','{\"nama_donasi\":\"Pembangunan Teras Masjid\",\"target_nominal\":150000000,\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"status\":\"aktif\",\"gambar\":\"69ba4c48a0c19_1773816904.png\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:55:04');
INSERT INTO `audit_log` VALUES('20','1','admin','CREATE','donasi_pemasukan','1',NULL,'{\"program_id\":1,\"tanggal\":\"2026-03-18\",\"uraian\":\"Infaq Kajian Masjid\",\"jumlah\":2500000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:55:56');
INSERT INTO `audit_log` VALUES('21','1','admin','CREATE','pemasukan','1',NULL,'{\"tanggal\":\"2026-03-18\",\"donatur_id\":null,\"nama_donatur\":\"Infaq Kajian Masjid Raya\",\"kategori_id\":1,\"jumlah\":875000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:57:28');
INSERT INTO `audit_log` VALUES('22','1','admin','CREATE','donasi_pengeluaran','1',NULL,'{\"program_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":525000,\"uraian\":\"Pembelian Semen 20 sak\",\"keterangan\":\"\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:58:17');
INSERT INTO `audit_log` VALUES('23','1','admin','CREATE','pengeluaran','1',NULL,'{\"tanggal\":\"2026-03-18\",\"kategori_id\":1,\"jumlah\":100000,\"penerima\":\"Mail\",\"keterangan\":\"Uang Kebersihan\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:59:06');
INSERT INTO `audit_log` VALUES('24','1','admin','CREATE','pengeluaran','2',NULL,'{\"tanggal\":\"2026-03-18\",\"kategori_id\":6,\"jumlah\":200000,\"penerima\":\"Ustadz Fulan\",\"keterangan\":\"Kajian Masjid Raya\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 13:59:26');
INSERT INTO `audit_log` VALUES('25','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:16:17');
INSERT INTO `audit_log` VALUES('26','3','batara','LOGIN','users','3',NULL,'{\"username\":\"batara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:16:30');
INSERT INTO `audit_log` VALUES('27','3','batara','LOGOUT','users','3',NULL,'{\"username\":\"batara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:18:10');
INSERT INTO `audit_log` VALUES('28','3','batara','LOGIN','users','3',NULL,'{\"username\":\"batara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:18:22');
INSERT INTO `audit_log` VALUES('29','3','batara','DELETE','users','2','{\"id\":2,\"nama_lengkap\":\"Bendahara\",\"email\":\"bendahara@masjid.id\",\"no_hp\":null,\"username\":\"bendahara\",\"password\":\"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC\\/.og\\/at2.uheWG\\/igi\",\"role_id\":2,\"status\":\"aktif\",\"last_login\":null,\"created_at\":\"2026-03-18 11:59:04\",\"updated_at\":\"2026-03-18 11:59:04\"}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:19:05');
INSERT INTO `audit_log` VALUES('30','3','batara','CREATE','users','4',NULL,'{\"username\":\"bendahara\",\"role_id\":2}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:19:29');
INSERT INTO `audit_log` VALUES('31','4','bendahara','LOGIN','users','4',NULL,'{\"username\":\"bendahara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:19:55');
INSERT INTO `audit_log` VALUES('32','4','bendahara','CREATE','kegiatan','1',NULL,'{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"status\":\"aktif\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:21:12');
INSERT INTO `audit_log` VALUES('33','4','bendahara','CREATE','kegiatan_pemasukan','1',NULL,'{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"uraian\":\"Pemindahan Uang Infaq\",\"jumlah\":300000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"user_id\":4}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 14:22:32');
INSERT INTO `audit_log` VALUES('34','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:50:49');
INSERT INTO `audit_log` VALUES('35','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:57:08');
INSERT INTO `audit_log` VALUES('36','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:57:08');
INSERT INTO `audit_log` VALUES('37','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:57:43');
INSERT INTO `audit_log` VALUES('38','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:57:43');
INSERT INTO `audit_log` VALUES('39','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:58:41');
INSERT INTO `audit_log` VALUES('40','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-18 14:58:41');
INSERT INTO `audit_log` VALUES('41','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 16:18:43');
INSERT INTO `audit_log` VALUES('42','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 16:21:45');
INSERT INTO `audit_log` VALUES('43','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 16:35:40');
INSERT INTO `audit_log` VALUES('44','1','admin','UPDATE','kegiatan','1','{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":\"0.00\",\"status\":\"aktif\",\"gambar\":null,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 14:21:12\",\"total_pemasukan\":\"300000.00\",\"total_pengeluaran\":\"0.00\",\"sisa_anggaran\":\"0.00\"}','{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":5000000,\"status\":\"aktif\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"gambar\":\"69ba770631339_1773827846.png\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 16:57:26');
INSERT INTO `audit_log` VALUES('45','1','admin','CREATE','kegiatan_pengeluaran','1',NULL,'{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":500000,\"penerima\":\"Toko A\",\"keterangan\":\"pembelian minuman gelas 10 kotak\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 16:58:22');
INSERT INTO `audit_log` VALUES('46','1','admin','UPDATE','kegiatan_pemasukan','1','{\"id\":1,\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"uraian\":\"Pemindahan Uang Infaq\",\"jumlah\":\"300000.00\",\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"bukti_transfer\":null,\"user_id\":4,\"created_at\":\"2026-03-18 14:22:32\",\"updated_at\":\"2026-03-18 14:22:32\"}','{\"tanggal\":\"2026-03-18\",\"uraian\":\"Uang Infaq\",\"jumlah\":350000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 16:58:45');
INSERT INTO `audit_log` VALUES('47','1','admin','UPDATE','kegiatan','1','{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":\"5000000.00\",\"status\":\"aktif\",\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 16:57:26\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"500000.00\",\"sisa_anggaran\":\"4500000.00\"}','{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"jumlah_anggaran\":5500000,\"status\":\"aktif\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 17:02:36');
INSERT INTO `audit_log` VALUES('48','1','admin','UPDATE','kegiatan','1','{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":null,\"penanggung_jawab\":null,\"sumber_dana\":null,\"jumlah_anggaran\":\"5500000.00\",\"status\":\"aktif\",\"tampil_publik\":1,\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 17:02:36\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"500000.00\",\"total_anggaran\":\"5850000.00\",\"sisa_anggaran\":\"5350000.00\"}','{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":5500000,\"tampil_publik\":1,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 17:30:20');
INSERT INTO `audit_log` VALUES('49','1','admin','CREATE','kegiatan_pengeluaran','2',NULL,'{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":100000,\"penerima\":\"Juru Parkir\",\"keterangan\":\"Jasa Penjaga Parkir\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 17:31:16');
INSERT INTO `audit_log` VALUES('50','1','admin','CREATE','kegiatan_pengeluaran','3',NULL,'{\"kegiatan_id\":1,\"tanggal\":\"2026-03-18\",\"jumlah\":100000,\"penerima\":\"Tukang Kebersihan\",\"keterangan\":\"Kebersihan Masjid\",\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 17:31:46');
INSERT INTO `audit_log` VALUES('51','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 18:02:52');
INSERT INTO `audit_log` VALUES('52','1','admin','UPDATE','kegiatan','1','{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":\"5500000.00\",\"status\":\"aktif\",\"tampil_publik\":1,\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 17:30:20\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"700000.00\",\"total_anggaran\":\"5850000.00\",\"sisa_anggaran\":\"5150000.00\"}','{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":5500000,\"tampil_publik\":0,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 18:03:18');
INSERT INTO `audit_log` VALUES('53','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 18:38:46');
INSERT INTO `audit_log` VALUES('54','1','admin','UPDATE','program_donasi','1','{\"id\":1,\"nama_donasi\":\"Pembangunan Teras Masjid\",\"slug\":\"pembangunan-teras-masjid\",\"target_nominal\":\"150000000.00\",\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"deskripsi_lengkap\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI No. Rek : 0922839292\",\"bank_nama\":\"Bank BSI No. Rek : 0922839292\",\"no_rekening\":null,\"atas_nama_rekening\":null,\"qris_file\":null,\"deadline\":null,\"lokasi_kegiatan\":null,\"dokumentasi_files\":null,\"flyer_file\":null,\"nomor_kontak\":null,\"status\":\"aktif\",\"gambar\":\"69ba4c48a0c19_1773816904.png\",\"created_at\":\"2026-03-18 12:14:46\",\"updated_at\":\"2026-03-18 18:36:06\"}','{\"nama_donasi\":\"Pembangunan Teras Masjid\",\"slug\":\"pembangunan-teras-masjid\",\"target_nominal\":150000000,\"uraian\":\"pembangunan teras ukuran 10 x 30 meter\",\"deskripsi_lengkap\":\"pembangunan teras ukuran 10 x 30 meter\",\"rekening_bank\":\"Bank BSI | No. Rek 0922839292 | a.n. Masjid Al Ikhlas\",\"bank_nama\":\"Bank BSI\",\"no_rekening\":\"0922839292\",\"atas_nama_rekening\":\"Masjid Al Ikhlas\",\"qris_file\":\"\",\"deadline\":null,\"lokasi_kegiatan\":\"Binjai Utara - Binjai\",\"dokumentasi_files\":\"[]\",\"flyer_file\":\"\",\"nomor_kontak\":\"085359090207\",\"status\":\"aktif\",\"gambar\":\"69ba4c48a0c19_1773816904.png\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-18 18:39:56');
INSERT INTO `audit_log` VALUES('55','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 17:11:14');
INSERT INTO `audit_log` VALUES('56','1','admin','UPDATE','settings',NULL,NULL,'{\"action\":\"update_pengurus\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 17:35:20');
INSERT INTO `audit_log` VALUES('57','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 18:14:50');
INSERT INTO `audit_log` VALUES('58','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 18:14:59');
INSERT INTO `audit_log` VALUES('59','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 19:18:16');
INSERT INTO `audit_log` VALUES('60','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 19:18:20');
INSERT INTO `audit_log` VALUES('61','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:38:39');
INSERT INTO `audit_log` VALUES('62','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:38:52');
INSERT INTO `audit_log` VALUES('63','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:39:08');
INSERT INTO `audit_log` VALUES('64','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:39:32');
INSERT INTO `audit_log` VALUES('65','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:39:54');
INSERT INTO `audit_log` VALUES('66','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:40:33');
INSERT INTO `audit_log` VALUES('67','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Microsoft Windows 10.0.26100; en-ID) PowerShell/7.5.5','2026-03-19 19:43:17');
INSERT INTO `audit_log` VALUES('68','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 21:31:58');
INSERT INTO `audit_log` VALUES('69','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-19 21:32:04');
INSERT INTO `audit_log` VALUES('70','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 10:15:38');
INSERT INTO `audit_log` VALUES('71','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 11:25:56');
INSERT INTO `audit_log` VALUES('72','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 11:26:01');
INSERT INTO `audit_log` VALUES('73','1','admin','CREATE','pengeluaran','3',NULL,'{\"tanggal\":\"2026-03-20\",\"kategori_id\":1,\"jumlah\":350000,\"penerima\":\"Tukang Kebersihan\",\"keterangan\":\"kajian\",\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":2,\"user_id\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 11:44:17');
INSERT INTO `audit_log` VALUES('74','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 13:16:46');
INSERT INTO `audit_log` VALUES('75','1','admin','UPDATE','pemasukan','1','{\"id\":1,\"tanggal\":\"2026-03-18\",\"donatur_id\":null,\"nama_donatur\":\"Infaq Kajian Masjid Raya\",\"kategori_id\":1,\"jumlah\":\"875000.00\",\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"bukti_transfer\":null,\"user_id\":1,\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":null,\"is_system_generated\":0,\"created_at\":\"2026-03-18 13:57:28\",\"updated_at\":\"2026-03-18 13:57:28\"}','{\"tanggal\":\"2026-03-18\",\"donatur_id\":null,\"nama_donatur\":\"Infaq Kajian Masjid Raya\",\"kategori_id\":1,\"jumlah\":875000,\"metode_pembayaran\":\"tunai\",\"keterangan\":\"\",\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":2}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 13:55:41');
INSERT INTO `audit_log` VALUES('76','1','admin','UPDATE','pengeluaran','1','{\"id\":1,\"tanggal\":\"2026-03-18\",\"kategori_id\":1,\"jumlah\":\"100000.00\",\"penerima\":\"Mail\",\"keterangan\":\"Uang Kebersihan\",\"bukti_nota\":null,\"user_id\":1,\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":null,\"is_system_generated\":0,\"created_at\":\"2026-03-18 13:59:06\",\"updated_at\":\"2026-03-18 13:59:06\"}','{\"tanggal\":\"2026-03-18\",\"kategori_id\":1,\"jumlah\":100000,\"penerima\":\"Mail\",\"keterangan\":\"Uang Kebersihan\",\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":2}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 13:56:02');
INSERT INTO `audit_log` VALUES('77','1','admin','UPDATE','pengeluaran','2','{\"id\":2,\"tanggal\":\"2026-03-18\",\"kategori_id\":6,\"jumlah\":\"200000.00\",\"penerima\":\"Ustadz Fulan\",\"keterangan\":\"Kajian Masjid Raya\",\"bukti_nota\":null,\"user_id\":1,\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":null,\"is_system_generated\":0,\"created_at\":\"2026-03-18 13:59:26\",\"updated_at\":\"2026-03-18 13:59:26\"}','{\"tanggal\":\"2026-03-18\",\"kategori_id\":6,\"jumlah\":200000,\"penerima\":\"Ustadz Fulan\",\"keterangan\":\"Kajian Masjid Raya\",\"fund_category\":\"Tidak Terikat\",\"account_type\":\"cash\",\"account_id\":2}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 13:56:06');
INSERT INTO `audit_log` VALUES('78','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 14:06:42');
INSERT INTO `audit_log` VALUES('79','4','bendahara','LOGIN','users','4',NULL,'{\"username\":\"bendahara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 14:06:50');
INSERT INTO `audit_log` VALUES('80','4','bendahara','UPDATE','kegiatan','1','{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":\"5500000.00\",\"status\":\"aktif\",\"tampil_publik\":0,\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-18 18:03:18\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"700000.00\",\"total_anggaran\":\"5850000.00\",\"sisa_anggaran\":\"5150000.00\"}','{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":5500000,\"tampil_publik\":1,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 14:29:14');
INSERT INTO `audit_log` VALUES('81','4','bendahara','LOGOUT','users','4',NULL,'{\"username\":\"bendahara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 15:07:23');
INSERT INTO `audit_log` VALUES('82','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-20 15:07:58');
INSERT INTO `audit_log` VALUES('83','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-25 21:34:04');
INSERT INTO `audit_log` VALUES('84','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-25 21:59:39');
INSERT INTO `audit_log` VALUES('85','4','bendahara','LOGIN','users','4',NULL,'{\"username\":\"bendahara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-25 21:59:47');
INSERT INTO `audit_log` VALUES('86','4','bendahara','LOGOUT','users','4',NULL,'{\"username\":\"bendahara\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-25 22:10:38');
INSERT INTO `audit_log` VALUES('87','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-25 22:10:43');
INSERT INTO `audit_log` VALUES('88','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 06:20:55');
INSERT INTO `audit_log` VALUES('89','1','admin','UPDATE','kegiatan','1','{\"id\":1,\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":\"5500000.00\",\"status\":\"aktif\",\"tampil_publik\":1,\"gambar\":\"69ba770631339_1773827846.png\",\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\",\"created_at\":\"2026-03-18 14:21:12\",\"updated_at\":\"2026-03-20 14:29:14\",\"total_pemasukan\":\"350000.00\",\"total_pengeluaran\":\"700000.00\",\"total_anggaran\":\"5850000.00\",\"sisa_anggaran\":\"5150000.00\"}','{\"nama_kegiatan\":\"Tabligh Akbar Ustadz Jakarta\",\"waktu_tempat\":\"Masjid Raya Binjai\",\"penanggung_jawab\":\"Batara\",\"sumber_dana\":\"Infaq Kajian Rutin\",\"jumlah_anggaran\":5500000,\"status\":\"aktif\",\"tampil_publik\":0,\"keterangan\":\"di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 06:33:25');
INSERT INTO `audit_log` VALUES('90','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 07:23:10');
INSERT INTO `audit_log` VALUES('91','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 07:23:16');
INSERT INTO `audit_log` VALUES('92','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 07:24:12');
INSERT INTO `audit_log` VALUES('93','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 07:29:55');
INSERT INTO `audit_log` VALUES('94','1','admin','LOGOUT','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-26 07:30:04');
INSERT INTO `audit_log` VALUES('95','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-27 06:26:11');
INSERT INTO `audit_log` VALUES('96','1','admin','LOGIN','users','1',NULL,'{\"username\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','2026-03-27 06:31:06');

DROP TABLE IF EXISTS `bank_accounts`;
CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(100) NOT NULL,
  `nomor_rekening` varchar(50) NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `saldo_awal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `saldo_saat_ini` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bank_admin_fee` decimal(15,2) DEFAULT 0.00,
  `bank_interest` decimal(15,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `is_public` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bank_accounts` VALUES('2','Bank BSI','0979222200','Masjid Al Ikhlas','600000.00','598500.00','1500.00','0.00','1','1','2026-03-20 11:32:40','2026-03-25 21:34:17');
INSERT INTO `bank_accounts` VALUES('3','Bank BSI','998208752','Masjid Al Ikhlas Bagian Zakat','100000.00','100000.00','0.00','0.00','1','0','2026-03-25 22:12:23','2026-03-26 07:02:13');

DROP TABLE IF EXISTS `bank_monthly_postings`;
CREATE TABLE `bank_monthly_postings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_account_id` int(11) NOT NULL,
  `posting_type` enum('bank_admin_fee','bank_interest') NOT NULL,
  `periode_bulan` varchar(7) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_table` varchar(50) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `executed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_posting` (`bank_account_id`,`posting_type`,`periode_bulan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bank_monthly_postings` VALUES('1','2','bank_admin_fee','2026-03','1500.00','pengeluaran','4','2026-03-25 21:34:17');

DROP TABLE IF EXISTS `cash_accounts`;
CREATE TABLE `cash_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kas` varchar(100) NOT NULL,
  `saldo_awal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `saldo_saat_ini` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cash_accounts` VALUES('2','Kas Tunai','1200000.00','1425000.00','1','2026-03-20 11:40:05','2026-03-20 13:56:06');

DROP TABLE IF EXISTS `donasi_pemasukan`;
CREATE TABLE `donasi_pemasukan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL DEFAULT 'Terikat',
  `account_type` enum('cash','bank') NOT NULL DEFAULT 'cash',
  `account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `donasi_pemasukan_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program_donasi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donasi_pemasukan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `donasi_pemasukan` VALUES('1','1','2026-03-18','Infaq Kajian Masjid','2500000.00','tunai','',NULL,'1','Terikat','cash','2','2026-03-18 13:55:56','2026-03-20 14:29:41');

DROP TABLE IF EXISTS `donasi_pengeluaran`;
CREATE TABLE `donasi_pengeluaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `uraian` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_nota` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL DEFAULT 'Terikat',
  `account_type` enum('cash','bank') NOT NULL DEFAULT 'cash',
  `account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `donasi_pengeluaran_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program_donasi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `donasi_pengeluaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `donasi_pengeluaran` VALUES('1','1','2026-03-18','525000.00','Pembelian Semen 20 sak','',NULL,'1','Terikat','cash','2','2026-03-18 13:58:17','2026-03-20 14:29:41');

DROP TABLE IF EXISTS `donatur`;
CREATE TABLE `donatur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_donatur` varchar(150) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_donatur` enum('tetap','tidak_tetap') DEFAULT 'tidak_tetap',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_donatur_jenis` (`jenis_donatur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `donatur` VALUES('1','Hamba Allah','08222222','alamat','tidak_tetap','catatan tambahan','2026-03-18 12:15:30','2026-03-18 12:15:30');

DROP TABLE IF EXISTS `internal_transfers`;
CREATE TABLE `internal_transfers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `akun_asal_type` enum('cash','bank') NOT NULL,
  `akun_asal_id` int(11) NOT NULL,
  `akun_tujuan_type` enum('cash','bank') NOT NULL,
  `akun_tujuan_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `kategori_pemasukan`;
CREATE TABLE `kategori_pemasukan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `fund_category` enum('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori_pemasukan` VALUES('1','Infaq Kajian','Tidak Terikat','Infaq dari kegiatan kajian rutin','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('2','Infaq Jumat','Tidak Terikat','Infaq shalat Jumat','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('3','Kotak Amal','Tidak Terikat','Pemasukan dari kotak amal','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('4','Sedekah','Tidak Terikat','Sedekah umum','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('5','Donasi','Terikat','Donasi dari donatur','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('6','Wakaf','Terikat','Wakaf tunai','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('7','Zakat','Terikat','Zakat mal dan fitrah','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('8','Transfer Bank','Tidak Terikat','Pemasukan via transfer bank','2026-03-18 11:59:04');
INSERT INTO `kategori_pemasukan` VALUES('11','Jasa Giro / Pendapatan Non-Halal','Tidak Terikat','Pendapatan non-halal atau jasa giro otomatis bulanan','2026-03-20 11:39:40');

DROP TABLE IF EXISTS `kategori_pengeluaran`;
CREATE TABLE `kategori_pengeluaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `fund_category` enum('Terikat','Tidak Terikat') NOT NULL DEFAULT 'Tidak Terikat',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori_pengeluaran` VALUES('1','Operasional Masjid','Tidak Terikat','Listrik, air, keamanan, kebersihan','2026-03-18 11:59:04');
INSERT INTO `kategori_pengeluaran` VALUES('2','Dakwah','Tidak Terikat','Kajian, seminar, cetakan','2026-03-18 11:59:04');
INSERT INTO `kategori_pengeluaran` VALUES('3','Sosial','Terikat','Santunan, bantuan musibah','2026-03-18 11:59:04');
INSERT INTO `kategori_pengeluaran` VALUES('4','Pembangunan & Renovasi','Terikat','Pembangunan dan renovasi masjid','2026-03-18 11:59:04');
INSERT INTO `kategori_pengeluaran` VALUES('5','Honor Petugas','Tidak Terikat','Honor imam, muadzin, marbot','2026-03-18 11:59:04');
INSERT INTO `kategori_pengeluaran` VALUES('6','Transport Ustadz','Tidak Terikat','Biaya transport ustadz','2026-03-18 11:59:04');
INSERT INTO `kategori_pengeluaran` VALUES('9','Biaya Admin Bank','Tidak Terikat','Beban biaya administrasi bank otomatis bulanan','2026-03-20 11:39:40');

DROP TABLE IF EXISTS `kegiatan`;
CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kegiatan` VALUES('1','Tabligh Akbar Ustadz Jakarta','Masjid Raya Binjai','Batara','Infaq Kajian Rutin','5500000.00','aktif','0','69ba770631339_1773827846.png','di masjid Raya Binjai, pada Jum;at Bada Magrib tanggal 20 Maret 2026','2026-03-18 14:21:12','2026-03-26 06:33:25');

DROP TABLE IF EXISTS `kegiatan_pemasukan`;
CREATE TABLE `kegiatan_pemasukan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kegiatan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL DEFAULT 'Tidak Terikat',
  `account_type` enum('cash','bank') NOT NULL DEFAULT 'cash',
  `account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kegiatan_id` (`kegiatan_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `kegiatan_pemasukan_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kegiatan_pemasukan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kegiatan_pemasukan` VALUES('1','1','2026-03-18','Uang Infaq','350000.00','tunai','',NULL,'4','Tidak Terikat','cash','2','2026-03-18 14:22:32','2026-03-20 14:29:41');

DROP TABLE IF EXISTS `kegiatan_pengeluaran`;
CREATE TABLE `kegiatan_pengeluaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kegiatan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penerima` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_nota` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL DEFAULT 'Tidak Terikat',
  `account_type` enum('cash','bank') NOT NULL DEFAULT 'cash',
  `account_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kegiatan_id` (`kegiatan_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `kegiatan_pengeluaran_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kegiatan_pengeluaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kegiatan_pengeluaran` VALUES('1','1','2026-03-18','500000.00','Toko A','pembelian minuman gelas 10 kotak',NULL,'1','Tidak Terikat','cash','2','2026-03-18 16:58:22','2026-03-20 14:29:41');
INSERT INTO `kegiatan_pengeluaran` VALUES('2','1','2026-03-18','100000.00','Juru Parkir','Jasa Penjaga Parkir',NULL,'1','Tidak Terikat','cash','2','2026-03-18 17:31:16','2026-03-20 14:29:41');
INSERT INTO `kegiatan_pengeluaran` VALUES('3','1','2026-03-18','100000.00','Tukang Kebersihan','Kebersihan Masjid',NULL,'1','Tidak Terikat','cash','2','2026-03-18 17:31:46','2026-03-20 14:29:41');

DROP TABLE IF EXISTS `pemasukan`;
CREATE TABLE `pemasukan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `donatur_id` int(11) DEFAULT NULL,
  `nama_donatur` varchar(150) DEFAULT NULL COMMENT 'Untuk donatur non-terdaftar',
  `kategori_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Petugas input',
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL DEFAULT 'Tidak Terikat',
  `account_type` enum('cash','bank') DEFAULT 'cash',
  `account_id` int(11) DEFAULT NULL,
  `is_system_generated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `donatur_id` (`donatur_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_pemasukan_tanggal` (`tanggal`),
  KEY `idx_pemasukan_kategori` (`kategori_id`),
  CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`donatur_id`) REFERENCES `donatur` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pemasukan_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pemasukan` (`id`),
  CONSTRAINT `pemasukan_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pemasukan` VALUES('1','2026-03-18',NULL,'Infaq Kajian Masjid Raya','1','875000.00','tunai','',NULL,'1','Tidak Terikat','cash','2','0','2026-03-18 13:57:28','2026-03-20 13:55:41');

DROP TABLE IF EXISTS `pengeluaran`;
CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penerima` varchar(150) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_nota` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Petugas input',
  `fund_category` enum('Tidak Terikat','Terikat') NOT NULL DEFAULT 'Tidak Terikat',
  `account_type` enum('cash','bank') DEFAULT 'cash',
  `account_id` int(11) DEFAULT NULL,
  `is_system_generated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_pengeluaran_tanggal` (`tanggal`),
  KEY `idx_pengeluaran_kategori` (`kategori_id`),
  CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pengeluaran` (`id`),
  CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengeluaran` VALUES('1','2026-03-18','1','100000.00','Mail','Uang Kebersihan',NULL,'1','Tidak Terikat','cash','2','0','2026-03-18 13:59:06','2026-03-20 13:56:02');
INSERT INTO `pengeluaran` VALUES('2','2026-03-18','6','200000.00','Ustadz Fulan','Kajian Masjid Raya',NULL,'1','Tidak Terikat','cash','2','0','2026-03-18 13:59:26','2026-03-20 13:56:06');
INSERT INTO `pengeluaran` VALUES('3','2026-03-20','1','350000.00','Tukang Kebersihan','kajian',NULL,'1','Tidak Terikat','cash','2','0','2026-03-20 11:44:17','2026-03-20 11:44:17');
INSERT INTO `pengeluaran` VALUES('4','2026-03-31','9','1500.00','Bank BSI','Posting otomatis biaya admin bank periode 2026-03',NULL,'1','Tidak Terikat','bank','2','1','2026-03-25 21:34:17','2026-03-25 21:34:17');

DROP TABLE IF EXISTS `program_donasi`;
CREATE TABLE `program_donasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_program_donasi_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `program_donasi` VALUES('1','Pembangunan Teras Masjid','pembangunan-teras-masjid','150000000.00','pembangunan teras ukuran 10 x 30 meter','pembangunan teras ukuran 10 x 30 meter','Bank BSI | No. Rek 0922839292 | a.n. Masjid Al Ikhlas','Bank BSI','0922839292','Masjid Al Ikhlas','',NULL,'Binjai Utara - Binjai','[]','','085359090207','aktif','69ba4c48a0c19_1773816904.png','2026-03-18 12:14:46','2026-03-18 18:39:56');

DROP TABLE IF EXISTS `rekening_bank`;
CREATE TABLE `rekening_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(100) NOT NULL,
  `no_rekening` varchar(50) NOT NULL,
  `atas_nama` varchar(100) NOT NULL,
  `qris` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rekening_bank` VALUES('1','Bank Default','0000','Admin',NULL,'Rekening Utama','2026-03-18 12:31:05','2026-03-18 12:31:05');
INSERT INTO `rekening_bank` VALUES('2','Bank BSI','0979222200','Masjid Al Ikhlas',NULL,'Rekening Infaq Umum','2026-03-18 12:36:40','2026-03-18 12:36:40');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(50) NOT NULL,
  `hak_akses` text DEFAULT NULL COMMENT 'JSON array of permissions',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` VALUES('1','Super Admin','[\"all\"]','2026-03-18 11:59:04','2026-03-18 11:59:04');
INSERT INTO `roles` VALUES('2','Bendahara','[\"dashboard\",\"donatur\",\"pemasukan\",\"pengeluaran\",\"kegiatan\",\"donasi\",\"laporan\",\"zakat\",\"kurban\"]','2026-03-18 11:59:04','2026-03-18 11:59:04');
INSERT INTO `roles` VALUES('3','Viewer','[\"public_dashboard\"]','2026-03-18 11:59:04','2026-03-18 11:59:04');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'umum',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` VALUES('1','nama_masjid','Masjid Al-Ikhlas','umum','2026-03-18 11:59:04');
INSERT INTO `settings` VALUES('2','jenis_lembaga','masjid','umum','2026-03-18 11:59:04');
INSERT INTO `settings` VALUES('3','alamat','Jl. Soekarno - Hatta, Binjai','umum','2026-03-18 13:09:31');
INSERT INTO `settings` VALUES('4','no_telepon','08123456789','umum','2026-03-18 11:59:04');
INSERT INTO `settings` VALUES('5','logo','69ba359a9ea34_1773811098.png','umum','2026-03-18 12:18:18');
INSERT INTO `settings` VALUES('6','status_lembaga','aktif','umum','2026-03-18 11:59:04');
INSERT INTO `settings` VALUES('11','zakat_beras_per_jiwa','2.5','zakat','2026-03-18 11:59:04');
INSERT INTO `settings` VALUES('12','zakat_uang_per_jiwa','45000','zakat','2026-03-18 11:59:04');
INSERT INTO `settings` VALUES('13','ketua_nama','Batara','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('14','ketua_jabatan','Ketua','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('15','ketua_email','ketua@gmail.com','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('16','ketua_hp','085222323','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('17','sekretaris_nama','Nama Sekretaris','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('18','sekretaris_jabatan','Sekretaris','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('19','sekretaris_email','bendahara@gmail.com','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('20','sekretaris_hp','085222323','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('21','bendahara_nama','Nama Bendahara','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('22','bendahara_jabatan','Bendahara','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('23','bendahara_email','bendahara@gmail.com','umum','2026-03-19 17:35:20');
INSERT INTO `settings` VALUES('24','bendahara_hp','085222323','umum','2026-03-19 17:35:20');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES('1','Administrator','admin@masjid.id','08222222','admin','$2y$10$FXNsX9RanBviVnfe8SNdpuDrj0DQv9Zz3mgDVFSuk33.3DR2I/1sK','1','aktif','2026-03-27 06:31:06','2026-03-18 11:59:04','2026-03-27 06:31:06');
INSERT INTO `users` VALUES('3','Batara','alif.tara@gmail.com','08535909','batara','$2y$10$jIwudBKUaGcUiY7x3NOwlOvKDSXzy7jJAYJquJQZy8DqX60SdGMYe','1','aktif','2026-03-18 14:18:22','2026-03-18 13:49:24','2026-03-18 14:18:22');
INSERT INTO `users` VALUES('4','Bendahara','bendahara@gmail.com','08222222','bendahara','$2y$10$wnyw6JTksVViBDwsHoY2DOPkWbSuZkoKMQFS5N6Jw9E2H94HQDb5q','2','aktif','2026-03-25 21:59:47','2026-03-18 14:19:28','2026-03-25 21:59:47');

SET FOREIGN_KEY_CHECKS=1;
