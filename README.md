# 🕌 Amanafy — Sistem Manajemen Keuangan Masjid

> **Amanafy** (Amanah & Syar'i Financial) adalah aplikasi manajemen keuangan masjid berbasis web yang dirancang untuk meningkatkan **transparansi**, **akuntabilitas**, dan **efisiensi** dalam pengelolaan dana masjid secara modern dan terdigitalisasi.

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/Database-MySQL%2FMariaDB-orange?logo=mysql)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/UI-Bootstrap%205-purple?logo=bootstrap)](https://getbootstrap.com/)
[![Amanafy](https://img.shields.io/badge/Status-Non--Komersial-red)](README.md)

---

## 📖 Daftar Isi

1. [Tujuan Utama](#-tujuan-utama)
2. [Kelebihan](#-kelebihan)
3. [Kekurangan](#-kekurangan--keterbatasan)
4. [Problem yang Diselesaikan](#-problem-yang-diselesaikan)
5. [Tech Stack](#-tech-stack)
6. [Prasyarat](#-prasyarat-prerequisites)
7. [Langkah Instalasi](#️-langkah-instalasi)
8. [Konfigurasi Database](#-konfigurasi-database)
9. [Akun Default](#-akun-default-login)
10. [Fitur Utama](#-fitur-utama)
11. [Struktur Folder](#-struktur-folder)
12. [Pengembangan](#-pengembangan)
13. [FAQ](#-faq-pertanyaan-yang-sering-diajukan)
14. [Changelog](#-changelog)
15. [Ketentuan Penggunaan](#️-peringatan--ketentuan-penggunaan)
16. [Lisensi](#-lisensi)

---

## 🎯 Tujuan Utama

Amanafy dikembangkan dengan satu misi utama: **menjadikan pengelolaan keuangan masjid lebih transparan, akuntabel, dan mudah diakses oleh seluruh pengurus masjid**.

Secara spesifik, aplikasi ini bertujuan untuk:

- **Mendigitalisasi** pencatatan keuangan masjid yang sebelumnya masih dilakukan secara manual (buku tulis / spreadsheet sederhana).
- **Meningkatkan transparansi** kepada jamaah dengan laporan keuangan yang terstruktur dan dapat dicetak.
- **Mempermudah pengelolaan program kegiatan & donasi** masjid dalam satu platform terpadu.
- **Menjaga keamanan dan integritas data** melalui sistem audit log dan manajemen pengguna dengan hak akses berbeda.
- **Menyediakan laporan keuangan standar** (arus kas, posisi dana, realisasi anggaran) yang siap cetak dan ekspor.

---

## ✅ Kelebihan

| No | Kelebihan | Keterangan |
|----|-----------|------------|
| 1 | **Ringan & Tanpa Framework Besar** | Dibangun dengan Vanilla PHP 8+ tanpa Laravel/CodeIgniter, sehingga tidak perlu Composer dan mudah di-deploy di shared hosting manapun. |
| 2 | **Dashboard Interaktif** | Visualisasi data keuangan real-time menggunakan Chart.js (grafik batang, pie chart distribusi kategori). |
| 3 | **Multi-Role Pengguna** | Mendukung peran Administrator, Bendahara, dan Takmir dengan hak akses yang berbeda-beda. |
| 4 | **Laporan Lengkap & Siap Cetak** | 7 jenis laporan keuangan (Arus Kas, Kas Tunai, Kas Bank, Posisi Dana, Realisasi Kegiatan, Program Donasi, Periodik) dengan fitur ekspor ke Excel & PDF. |
| 5 | **Audit Log Terintegrasi** | Setiap aktivitas kritis (login, ubah data, hapus) tercatat otomatis untuk keamanan dan akuntabilitas. |
| 6 | **Manajemen Kas Bank** | Mendukung pencatatan saldo kas tunai dan rekening bank masjid secara terpisah. |
| 7 | **Backup Database Mudah** | Fitur backup database langsung dari antarmuka tanpa perlu akses server. |
| 8 | **Desain Modern & Responsif** | UI modern berbasis Bootstrap 5 yang responsif di berbagai ukuran layar. |
| 9 | **Halaman Publik** | Terdapat halaman publik untuk menampilkan program donasi aktif kepada jamaah. |
| 10 | **Tanpa Biaya Lisensi** | Dilisensikan dengan MIT License, bebas digunakan dan dimodifikasi. |

---

## ⚠️ Kekurangan / Keterbatasan

- **Belum ada API / REST Endpoint** — Aplikasi belum mendukung integrasi pihak ketiga atau aplikasi mobile.
- **Tidak ada notifikasi real-time** — Belum ada sistem notifikasi email/WhatsApp untuk laporan otomatis atau pengingat.
- **Single Server** — Tidak dirancang untuk arsitektur multi-server atau load balancing skala besar.
- **Belum ada modul infografis publik** — Laporan keuangan yang ditampilkan ke publik masih terbatas.
- **Impor data massal** — Belum tersedia fitur impor transaksi dari file Excel/CSV secara massal.

---

## 🧩 Problem yang Diselesaikan

Berikut adalah masalah nyata di lapangan yang diselesaikan oleh Amanafy:

| Masalah | Solusi di Amanafy |
|---------|-------------------|
| Pencatatan keuangan masih manual di buku tulis, rawan hilang & salah hitung | Sistem database terpusat dengan validasi input |
| Laporan keuangan sulit dibuat, memakan waktu lama | 7 jenis laporan otomatis siap cetak / ekspor PDF & Excel |
| Tidak ada transparansi penggunaan dana kepada jamaah | Halaman publik menampilkan program donasi & perkembangannya |
| Pengurus sulit memantau saldo kas & bank secara bersamaan | Modul Kas Bank terintegrasi dengan dashboard saldo terkini |
| Data keuangan bisa diubah tanpa jejak oleh siapapun | Audit Log mencatat setiap perubahan data penting |
| Banyak petugas mengakses sistem tanpa pembatasan hak | Sistem multi-role: Administrator, Bendahara, Takmir |
| Program kegiatan & donasi dikelola terpisah dari keuangan | Modul Kegiatan & Donasi terintegrasi langsung dengan kas |

---

## 🚀 Tech Stack

```
Amanafy
├── Backend       : PHP 8.1+ (Vanilla PHP, pola MVC sederhana)
├── Database      : MySQL / MariaDB (akses via PDO + prepared statements)
├── Frontend      : Bootstrap 5 + Tailwind CSS (untuk komponen spesifik)
├── Grafik        : Chart.js
├── Tabel Data    : DataTables (dengan fitur search & sort)
├── Keamanan      : CSRF Protection, Login Throttling, Honeypot
├── Ikon          : Font Awesome 6 & Bootstrap Icons
├── Export PDF    : html2pdf.js (Locally Hosted)
└── Export Excel  : SheetJS / xlsx.js (Locally Hosted)
```

---

## 📋 Prasyarat (Prerequisites)

Pastikan perangkat Anda sudah terinstal:

1. **XAMPP / Laragon** — Versi yang mendukung **PHP 8.1+** dan MySQL.
2. **Web Browser** — Google Chrome, Firefox, atau Edge versi terbaru.
3. **phpMyAdmin** — Sudah termasuk di dalam paket XAMPP/Laragon.

> **Catatan**: Aplikasi ini **tidak memerlukan Composer** karena tidak menggunakan framework PHP eksternal.

---

## 🛠️ Langkah Instalasi

### 1. Clone atau Unduh Repository

```bash
# Opsi A: Via Git
git clone https://github.com/username/amanafy.git C:/xampp/htdocs/amanafy

# Opsi B: Unduh ZIP
# Ekstrak ke: C:\xampp\htdocs\amanafy
```

### 2. Konfigurasi Database

- Buka **phpMyAdmin** → `http://localhost/phpmyadmin`
- Buat database baru: **`amanafy`**
- Pilih tab **Import** → pilih file `amanafy.sql` (di root proyek)
- Klik **Go / Kirim**

### 3. Pengaturan Koneksi Database

Buka file `config/database.php` dan sesuaikan:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'amanafy');
define('DB_USER', 'root');
define('DB_PASS', '');          // Kosongkan jika tidak ada password (XAMPP default)
```

### 4. Pengaturan Folder Upload (Opsional)

Pastikan folder `uploads/` memiliki izin tulis (writable). Di Windows/XAMPP, ini biasanya sudah otomatis.

```bash
# Linux/Mac (jika diperlukan):
chmod -R 775 uploads/
```

### 5. Jalankan Aplikasi

Akses melalui browser:
```
http://localhost/amanafy
```

---

## 🗄️ Konfigurasi Database

File SQL utama tersedia di root proyek:

```
amanafy/
└── amanafy.sql       ← Import file ini ke phpMyAdmin
```

File ini sudah mencakup:
- Struktur tabel lengkap (users, pemasukan, pengeluaran, donatur, kegiatan, donasi, dll.)
- Data awal (seeder) termasuk akun admin default
- Konfigurasi hak akses role

### Tabel Utama

| Tabel | Fungsi |
|-------|--------|
| `users` | Data pengguna & role |
| `pemasukan` | Transaksi pemasukan |
| `pengeluaran` | Transaksi pengeluaran |
| `donatur` | Data donatur tetap/tidak tetap |
| `kegiatan` | Program kegiatan masjid |
| `donasi` | Program donasi publik |
| `audit_log` | Log aktivitas pengguna |
| `settings` | Konfigurasi profil masjid |
| `kas_bank` | Data rekening bank masjid |

---

## 🔑 Akun Default (Login)

Setelah instalasi, login dengan akun bawaan:

| Role | Username | Password |
|------|----------|----------|
| Administrator | `admin` | `admin123` |

> ⚠️ **Sangat disarankan** untuk segera mengganti password default setelah login pertama kali melalui menu **Profil → Ganti Password**.

---

## ✨ Fitur Utama

### 📊 Dashboard
- Kartu ringkasan saldo kas, total pemasukan & pengeluaran
- Grafik tren pemasukan & pengeluaran bulanan
- Distribusi kategori pemasukan (pie chart)
- Daftar program kegiatan aktif

### 👥 Manajemen Donatur
- CRUD data donatur (tetap & tidak tetap)
- Riwayat donasi per donatur

### 💰 Pemasukan & Pengeluaran
- Pencatatan transaksi dengan kategori kustom
- Filter & pencarian berdasarkan tanggal, kategori, kata kunci
- Tabel interaktif dengan DataTables

### 🎯 Program Kegiatan
- Pengelolaan anggaran per kegiatan masjid
- Pencatatan realisasi pemasukan & pengeluaran per kegiatan
- Laporan realisasi anggaran vs. aktual

### 🤝 Program Donasi
- Kampanye donasi publik dengan target & deadline
- Tracking progres pengumpulan dana
- Halaman publik untuk transparansi kepada jamaah

### 🏦 Kas & Bank
- Manajemen saldo kas tunai dan rekening bank
- Mutasi rekening terintegrasi
- Penjurnalan otomatis dari transaksi

### 📑 Laporan Keuangan (7 Jenis)
1. **Arus Kas** — Laporan cash flow masuk & keluar
2. **Kas Tunai** — Posisi kas fisik
3. **Kas Bank** — Mutasi rekening bank
4. **Posisi Dana** — Neraca dana masjid
5. **Realisasi Kegiatan** — Anggaran vs. realisasi per kegiatan
6. **Program Donasi** — Progres penghimpunan donasi
7. **Periodik** — Laporan harian / bulanan / tahunan

> Semua laporan mendukung: 🖨️ **Print** | 📊 **Export Excel (SheetJS)** | 📄 **Export PDF (jsPDF)**

### 👤 Manajemen Pengguna
- Tambah, edit, non-aktifkan akun pengguna
- Role-based access: **Administrator**, **Bendahara**, **Takmir**

### 🔍 Keamanan & Audit Log
- **Anti Brute-Force & Bot**: Proteksi login dengan *Rate Limiting / Throttling* dan *Honeypot*.
- **CSRF Protection**: Token keamanan pada setiap form input.
- **Pencatatan Otomatis**: Setiap aksi kritis dicatat di Audit Log.
- **Informasi**: Pengguna, waktu, aksi, rincian data yang diubah.

### ⚙️ Pengaturan
- Profil masjid (nama, alamat, logo) — digunakan di header laporan (kop surat)
- Konfigurasi tanda tangan laporan (Ketua & Bendahara)

### 💾 Backup Database
- Ekspor database langsung dari dashboard admin

---

## 📁 Struktur Folder

```
amanafy/
├── assets/
│   ├── css/            ← Stylesheet custom
│   ├── js/             ← Script JS (termasuk laporan.js untuk export)
│   └── img/            ← Gambar & logo
├── config/
│   └── database.php    ← Konfigurasi koneksi database
├── controllers/        ← Logika bisnis (15 controller)
├── core/               ← Inti sistem (Router, Session, Helper, BaseController)
├── models/             ← Query database (akses via PDO)
├── views/              ← Template antarmuka (18 modul)
│   ├── layouts/        ← Layout utama (sidebar, navbar, footer)
│   ├── dashboard/
│   ├── pemasukan/
│   ├── pengeluaran/
│   ├── donatur/
│   ├── kegiatan/
│   ├── donasi/
│   ├── kas-bank/
│   ├── laporan/
│   ├── users/
│   ├── settings/
│   ├── audit/
│   ├── backup/
│   ├── profil/
│   ├── auth/
│   └── public/         ← Halaman publik (tanpa login)
├── database/           ← File SQL inisialisasi
├── uploads/            ← Media yang diunggah pengguna
├── backups/            ← Hasil backup database
├── amanafy.sql         ← File SQL utama untuk import
├── index.php           ← Entry point aplikasi
└── .htaccess           ← URL rewriting (mod_rewrite)
```

---

## 🔧 Pengembangan

### Menjalankan di Environment Lokal

1. Pastikan XAMPP berjalan (Apache + MySQL aktif)
2. Buka `http://localhost/amanafy`
3. Tidak perlu build step — ini adalah PHP murni

### Menambahkan Modul Baru

Ikuti pola MVC yang sudah ada:

```
1. Buat controller baru di controllers/NamaController.php
2. Buat model baru di models/NamaModel.php (jika diperlukan)
3. Buat folder view di views/nama-modul/
4. Daftarkan route di core/Router.php
```

### Konvensi Kode

- **Penamaan file**: `PascalCase` untuk controller & model, `kebab-case` untuk view folder
- **Akses database**: Selalu gunakan **PDO Prepared Statements** (hindari raw query)
- **Validasi input**: Lakukan validasi di sisi server (controller), bukan hanya client-side
- **Keamanan**: Gunakan `htmlspecialchars()` untuk output data ke HTML

### Kontribusi

Pull request sangat disambut! Mohon ikuti langkah berikut:

```bash
1. Fork repository ini
2. Buat branch fitur: git checkout -b fitur/nama-fitur
3. Commit perubahan: git commit -m "feat: tambah nama fitur"
4. Push ke branch: git push origin fitur/nama-fitur
5. Buat Pull Request
```

### Rencana Pengembangan (Roadmap)

- [ ] REST API endpoint untuk integrasi mobile app
- [ ] Notifikasi email / WhatsApp otomatis
- [ ] Impor transaksi massal dari Excel
- [ ] Two-factor authentication (2FA)
- [ ] Laporan infografis publik (untuk papan pengumuman masjid)
- [ ] Mode multi-masjid (tenant)
- [ ] Integrasi Payment Gateway untuk donasi online

---

## ❓ FAQ (Pertanyaan yang Sering Diajukan)

**Q: Apakah Amanafy bisa digunakan di hosting (bukan lokal)?**
> A: Ya, asalkan hosting mendukung PHP 8.1+ dan MySQL. Upload semua file ke public_html, import database, dan sesuaikan `config/database.php`.

**Q: Apakah perlu Composer untuk instalasi?**
> A: Tidak. Amanafy tidak menggunakan framework PHP seperti Laravel atau CodeIgniter, sehingga tidak memerlukan Composer sama sekali.

**Q: Bagaimana cara mengganti nama / logo masjid di laporan?**
> A: Masuk ke menu **Pengaturan → Profil Masjid**, isi nama masjid, alamat, nomor telepon, dan unggah logo. Data ini akan otomatis muncul di header (kop) semua laporan.

**Q: Apakah ada fitur logout otomatis?**
> A: Ya! Amanafy dilengkapi fitur **Auto-Logout Sesi Idle**. Jika tidak ada aktivitas kursor/keyboard selama 15 menit, sistem akan otomatis mengeluarkan pengguna demi keamanan.

**Q: Bagaimana jika gambar flyer donasi tidak muncul?**
> A: Sistem secara cerdas akan memprioritaskan "Flyer Program" sebagai sampul utama. Jika flyer tidak diunggah, sistem akan mengambil gambar pertama dari Media Dokumentasi sebagai cadangan.

**Q: Bagaimana jika lupa password admin?**
> A: Buka phpMyAdmin → tabel `users` → edit kolom `password` dengan hash bcrypt dari password baru Anda. Atau gunakan akun admin lain untuk mereset password.

**Q: Bisakah satu akun memiliki lebih dari satu role?**
> A: Saat ini tidak. Setiap akun hanya memiliki satu role (Administrator, Bendahara, atau Takmir).

**Q: Format apa yang didukung untuk ekspor laporan?**
> A: Laporan dapat diekspor ke **Excel (.xlsx)** menggunakan SheetJS, **PDF** menggunakan jsPDF, dan **Print** langsung dari browser.

**Q: Apakah data aman jika server down?**
> A: Gunakan fitur **Backup Database** secara berkala dari menu Backup. File SQL hasil backup dapat disimpan di penyimpanan lokal Anda.

---

## 📋 Changelog

### v1.4.1 — April 2026 (Terkini)
- ✅ **Auto-Logout Interaktif**: Implementasi fitur keamanan baru yang otomatis mengeluarkan pengguna jika terdeteksi *idle* (tidak ada aktivitas kursor/keyboard) selama 15 menit. Dilengkapi fitur *keep-alive ping* untuk menjaga kenyamanan kerja yang intensif.
- ✅ **Sistem Sampul Donasi Cerdas**: Perbaikan logika penentuan gambar utama di Dashboard. Kini sistem secara otomatis memprioritaskan "Flyer Utama" sebagai sampul visual, dengan *fallback* cerdas ke dokumentasi kegiatan jika flyer kosong.
- ✅ **Konsistensi UI Publik**: Penyelarasan gambar flyer agar tampil konsisten di Dashboard Admin, Portal Publik, maupun halaman detail donasi.

### v1.4.0 — April 2026
- ✅ **Hardening Security**: Sistem login kini diproteksi dengan fitur Anti-Spam (Honeypot), pencegahan Brute-Force (Rate Limiting), dan form security (CSRF Token).
- ✅ **Dashboard Publik**: Redesign antarmuka dengan 5 metrik analitik real-time dan hero card progresif.
- ✅ **Dashboard Admin**: Penambahan 4 kartu informasi cepat (Saldo Kas Tunai, Bank, Pemasukan Bulanan, Pengeluaran Bulanan).
- ✅ **Ekspor Laporan Handal**: Update engine PDF dan Excel untuk menggunakan library lokal (`html2pdf` & `SheetJS`) sehingga 100% mendukung penggunaan offline (tanpa ketergantungan CDN).
- ✅ **Tanda Tangan Dinamis**: Kolom Pengurus (Ketua, Sekretaris, Bendahara) pada menu Pengaturan kini secara otomatis terintegrasi ke seluruh halaman cetak laporan.

### v1.3.0 — Maret 2026
- ✅ Laporan keuangan lengkap 7 jenis dengan ekspor Excel (SheetJS) & PDF (jsPDF)
- ✅ Header kop masjid & footer tanda tangan otomatis di semua laporan
- ✅ Perbaikan chart distribusi kategori pemasukan (data dinamis)
- ✅ Dashboard: tampilan program kegiatan aktif diperbaiki
- ✅ Peningkatan UI/UX keseluruhan dengan desain modern (gradient, card, micro-animation)

### v1.2.0 — Maret 2026
- ✅ Modul Kas & Bank (KasBankController) dengan mutasi rekening
- ✅ CRUD lengkap Pemasukan & Pengeluaran per Program Kegiatan dan Donasi
- ✅ Halaman publik program donasi aktif (tampil gambar & deskripsi)
- ✅ Refactor UI ke desain dashboard modern (Purple Admin inspired)

### v1.1.0 — Maret 2026
- ✅ Modul Program Kegiatan & Program Donasi
- ✅ Manajemen Pengguna dengan role-based access
- ✅ Audit Log aktivitas pengguna
- ✅ Fitur Backup Database

### v1.0.0 — Maret 2026
- 🎉 Rilis pertama aplikasi Amanafy
- ✅ Dashboard, Donatur, Pemasukan, Pengeluaran dasar
- ✅ Autentikasi login
- ✅ Pengaturan profil masjid

---

## ⚠️ PERINGATAN & KETENTUAN PENGGUNAAN

Aplikasi **Amanafy** disediakan untuk kepentingan edukasi, sosial, dan pengelolaan yang bersifat **non-komersial**. Dengan menggunakan aplikasi ini, Anda dianggap telah memahami dan menyetujui ketentuan berikut:

### 🚫 Larangan
* **Dilarang** dengan sengaja maupun tidak sengaja memperjualbelikan aplikasi ini dalam bentuk apa pun.
* **Dilarang** mengklaim aplikasi ini sebagai karya pribadi tanpa izin dari pengembang.
* **Dilarang** mengubah, menghapus, atau menyembunyikan atribusi/hak cipta yang terdapat dalam aplikasi.
* **Dilarang** menggunakan aplikasi untuk aktivitas ilegal, penipuan, atau penyalahgunaan data.
* **Dilarang** mendistribusikan ulang aplikasi dalam bentuk berbayar, termasuk bundling dengan layanan lain tanpa izin resmi.

### ⚖️ Konsekuensi Hukum
* Segala bentuk pelanggaran, khususnya terkait perdagangan ilegal aplikasi, akan diproses sesuai dengan hukum yang berlaku.
* Bukti transaksi, distribusi, atau aktivitas komersialisasi tanpa izin akan menjadi dasar tindakan hukum.

### 🔐 Tanggung Jawab Pengguna
* Pengguna bertanggung jawab penuh atas penggunaan, pengelolaan data, dan implementasi aplikasi.
* Pengembang tidak bertanggung jawab atas kerugian yang timbul akibat penyalahgunaan aplikasi.

### 🔄 Perubahan & Pembaruan
* Pengembang berhak memperbarui, mengubah, atau menghentikan sebagian/seluruh fitur tanpa pemberitahuan sebelumnya.
* Ketentuan ini dapat diperbarui sewaktu-waktu sesuai kebutuhan.

---

## 📸 Preview Aplikasi

<p align="center">
  <img src="assets/img/screenshot-1.png" width="250"/>
  <img src="assets/img/screenshot-2.png" width="250"/>
  <img src="assets/img/screenshot-3.png" width="250"/>
  <img src="assets/img/screenshot-4.png" width="250"/>
  <img src="assets/img/screenshot-5.png" width="250"/>
  <img src="assets/img/screenshot-6.png" width="250"/>
  <img src="assets/img/screenshot-7.png" width="250"/>
  <img src="assets/img/screenshot-8.png" width="250"/>
  <img src="assets/img/screenshot-9.png" width="250"/>
  <img src="assets/img/screenshot-10.png" width="250"/>
  <img src="assets/img/screenshot-11.png" width="250"/>
  <img src="assets/img/screenshot-12.png" width="250"/>
  <img src="assets/img/screenshot-13.png" width="250"/>
  <img src="assets/img/screenshot-14.png" width="250"/>
  <img src="assets/img/screenshot-15.png" width="250"/>
  <img src="assets/img/screenshot-16.png" width="250"/>
  <img src="assets/img/screenshot-17.png" width="250"/>
  <img src="assets/img/screenshot-18.png" width="250"/>
  <img src="assets/img/screenshot-19.png" width="250"/>
</p>

## 📜 Lisensi

Aplikasi **Amanafy** dilisensikan di bawah **MIT License**.

```
MIT License

Copyright (c) 2026 Amanafy

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

> **Ringkasan**: Anda bebas menggunakan, menyalin, memodifikasi, dan mendistribusikan aplikasi ini untuk keperluan apa pun (termasuk penggunaan pribadi dan komunitas), **selama** tetap menyertakan atribusi hak cipta asli dan lisensi ini di setiap salinan atau bagian substansial dari aplikasi.

---

<div align="center">

**Dikembangkan dengan ❤️ untuk Masjid yang lebih modern, transparan, dan amanah.**

*"Sesungguhnya Allah menyuruh kamu menyampaikan amanat kepada yang berhak menerimanya."*
*(QS. An-Nisa: 58)*

</div>
