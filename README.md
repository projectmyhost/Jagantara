# Jagantara V2

<div align="center">

![Jagantara Version](https://img.shields.io/badge/version-2.0.0-emerald?style=for-the-badge)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.1-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white)
![Architecture](https://img.shields.io/badge/Architecture-Custom%20MVC-blue?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-brightgreen?style=for-the-badge)

**Platform Pelaporan dan Pengorganisasian Aksi Lingkungan Berbasis Masyarakat di Wilayah JABODETABEK.**

[Fitur Utama](#fitur-utama) | [Teknologi](#teknologi-yang-digunakan) | [Instalasi](#panduan-instalasi--setup) | [Struktur Proyek](#struktur-proyek) | [Lisensi](#lisensi--kontribusi)

</div>

---

## Tentang Jagantara

**Jagantara** adalah platform digital berbasis komunitas yang dirancang untuk menjembatani partisipasi publik, komunitas penggiat lingkungan (organizer), dan pemangku kepentingan dalam menjaga kebersihan serta kelestarian alam di kawasan Jabodetabek (Jakarta, Bogor, Depok, Tangerang, Bekasi).

Masyarakat dapat melaporkan titik tumpukan sampah, kerusakan lingkungan, maupun kebutuhan aksi pembersihan secara real-time disertai bukti foto dan titik koordinat akurat. Komunitas lingkungan dapat mengorganisasi kegiatan gotong royong dan cleanup, sementara tim pengelola memiliki panel terpadu untuk memantau, memverifikasi, dan mengelola seluruh ekosistem aplikasi.

---

## Fitur Utama

### 1. Pelaporan Masalah Lingkungan Interaktif
- **Multi-Photo Upload**: Unggah hingga 10 dokumentasi foto kondisi lapangan dengan validasi format dan ukuran ketat.
- **Peta dan Geolocation Interaktif**: Penentuan titik koordinat otomatis via GPS browser atau penandaan titik langsung di peta Leaflet.
- **Pelacakan Status Real-Time**: Status laporan mulai dari Menunggu Verifikasi, Diverifikasi, Dalam Perencanaan, Dijadwalkan, Sedang Ditangani, hingga Selesai.
- **Riwayat Status dan Audit Trail**: Catatan transparan setiap perubahan status laporan oleh pengelola atau organizer.

### 2. Peta Sebaran dan Lokasi Aksi (Interactive GIS Map)
- Visualisasi laporan dan titik aksi pembersihan (cleanup locations) di seluruh kawasan Jabodetabek.
- Filter berdasarkan wilayah (Jakarta, Bogor, Depok, Tangerang, Bekasi) dan kategori sampah atau permasalahan lingkungan.
- Pop-up detail informasi laporan dan rute aksi langsung di peta interaktif.

### 3. Aksi Bersih dan Pengorganisasian Relawan
- Pendaftaran kegiatan aksi bersih (cleanup events) oleh komunitas resmi.
- Sistem pendaftaran relawan dan partisipan aksi.
- Pemantauan progres dan kuota relawan per kegiatan.

### 4. Forum Diskusi dan Edukasi Komunitas
- Ruang diskusi terbuka bagi anggota untuk berbagi solusi dan inovasi lingkungan.
- Sistem komentar bertingkat dan moderasi konten.

### 5. Keamanan Tingkat Tinggi (Enterprise-Grade Security)
- **CSRF Token Protection**: Proteksi formulir dari serangan pemalsuan permintaan lintas situs (Cross-Site Request Forgery).
- **Session dan Device Fingerprinting**: Validasi sidik jari perangkat (User-Agent dan Accept-Language) untuk mencegah pembajakan sesi.
- **Rate Limiting Cerdas**: Pembatasan laju percobaan login (anti brute-force), pendaftaran akun baru, pengiriman laporan, dan komentar forum.
- **Keamanan Unggahan Berkas**: Isolasi direktori unggahan dengan proteksi `.htaccess` yang melarang eksekusi skrip berbahaya.
- **Prepared Statements PDO**: Menjamin seluruh query database terlindungi dari serangan SQL Injection.

### 6. Dashboard Manajemen Admin Lengkap
- **Statistik dan Ringkasan Metrik**: Monitoring jumlah laporan, verifikasi tertunda, kegiatan aktif, dan pengguna baru.
- **Manajemen Wilayah dan Kategori**: Tambah dan atur master data wilayah serta kategori permasalahan lingkungan.
- **Manajemen Pengguna dan Organizer**: Kontrol peran (Admin, Organizer Member, User) dan persetujuan pengajuan (application) komunitas baru.
- **Sistem Pengaturan Tema dan Tampilan**: Konfigurasi tema, tipografi, dan banner carousel secara langsung melalui panel admin.
- **Audit Logs dan Security Logs**: Rekam jejak seluruh aktivitas administratif serta insiden keamanan sistem.

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| **Backend** | PHP 8.1+ Native (Arsitektur Model-View-Controller murni) |
| **Database** | MySQL / MariaDB (Driver PDO + Transaksi Database) |
| **Frontend** | HTML5 Semantik, Vanilla CSS (Modern Design System, Glassmorphism, Micro-animations), Vanilla JavaScript (ES6+) |
| **Peta Digital** | Leaflet.js + OpenStreetMap Tile Server |
| **Server Engine** | Apache HTTP Server (dengan ekstensi `mod_rewrite`) |

---

## Panduan Instalasi & Setup

### Prasyarat Sistem
- **Web Server**: Apache dengan modul `mod_rewrite` aktif (Rekomendasi: Laragon atau XAMPP)
- **PHP**: Versi 8.1 atau yang lebih baru (Ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `openssl` aktif)
- **Database**: MySQL 5.7+ atau MariaDB 10.4+

### Langkah-langkah Pemasangan:

1. **Clone Repository**
   ```bash
   git clone https://github.com/projectmyhost/Jagantara.git
   cd Jagantara
   ```

2. **Konfigurasi Database**
   Salin berkas `config/database.example.php` menjadi `config/database.php` dan sesuaikan kredensial MySQL lokal Anda:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_PORT', '3306');
   define('DB_NAME', 'jagantara_v2');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Jalankan Migrasi & Database Seeder**
   Buka terminal di direktori proyek dan jalankan perintah migrasi:
   ```bash
   php migrate.php
   ```
   Perintah ini akan secara otomatis:
   - Membuat basis data `jagantara_v2` (jika belum dibuat).
   - Menjalankan skema tabel migrations (`001` sampai `007`).
   - Melakukan seeding data kategori, wilayah Jabodetabek, tema bawaan, dan banner.
   - Menginisialisasi akun administrator awal.
   - Menyiapkan folder penyimpanan `public/uploads/` beserta proteksi `.htaccess`.

4. **Akses Aplikasi**
   Buka browser dan navigasikan ke:
   ```
   http://localhost/JagantaraV2/
   ```
   *(Atau sesuai domain Virtual Host Laragon Anda, misalnya `http://jagantarav2.test/`)*

---

## Struktur Proyek

```plaintext
JagantaraV2/
|-- config/                  # Konfigurasi aplikasi, basis data, dan modul keamanan
|   |-- app.php              # Definisi konstanta global, status, peran, dan rate limits
|   |-- database.php         # Koneksi PDO singleton dan error handling
|   |-- database.example.php # Contoh template konfigurasi database
|   `-- security.php         # CSRF, XSS filter, fingerprinting, dan sanitasi
|-- controllers/             # Pengendali logika aplikasi (Controller)
|   |-- admin/               # Sub-controller khusus modul administrasi
|   |-- ActivityController.php
|   |-- AuthController.php
|   |-- HomeController.php
|   |-- MapController.php
|   |-- OrganizerController.php
|   |-- ReportController.php
|   `-- ProfileController.php
|-- core/                    # Engine inti sistem (Router, Controller base, Model base, FileUpload, RateLimit)
|   |-- Controller.php
|   |-- FileUpload.php
|   |-- Model.php
|   |-- RateLimit.php
|   |-- Router.php
|   |-- SecurityLog.php
|   `-- Validator.php
|-- helpers/                 # Fungsi helper utilitas global (format tanggal, rupiah, alert, dll)
|-- migrations/              # Berkas migrasi DDL SQL dan seeder data
|-- models/                  # Representasi entitas data dan query database (Model)
|-- public/                  # Aset statis yang dapat diakses publik
|   |-- css/                 # Berkas stylesheet CSS (app.css)
|   |-- js/                  # Berkas JavaScript client (app.js, geolocation.js)
|   `-- uploads/             # Direktori berkas unggahan terproteksi .htaccess
|-- views/                   # Template antarmuka pengguna (Views)
|   |-- admin/               # Tampilan panel admin
|   |-- auth/                # Halaman login, register, dan login admin
|   |-- layouts/             # Master layout, navbar, footer, dan modal
|   |-- reports/             # Halaman buat, lihat, dan tracking laporan
|   `-- home/                # Halaman beranda dan peta interaktif
|-- .gitignore               # Konfigurasi pengabaian berkas Git
|-- .htaccess                # URL rewrite engine dan proteksi rute Apache
|-- index.php                # Front Controller (Entry point utama)
|-- migrate.php              # Script CLI eksekusi migrasi dan database seeder
`-- README.md                # Dokumentasi proyek
```

---

## Lisensi & Kontribusi

Proyek ini dikembangkan untuk kepentingan aksi sosial dan pelestarian lingkungan. Kontribusi berupa saran, pelaporan bug, ataupun perbaikan fitur melalui Pull Request sangat diterima.

<div align="center">

Dibuat dengan dedikasi untuk menjaga lingkungan Indonesia yang lebih bersih dan asri.

</div>
