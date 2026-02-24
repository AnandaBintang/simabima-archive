<h1 align="center">
  <br>
  📂 SIMABIMA
  <br>
</h1>

<p align="center">
  <strong>Sistem Informasi Manajemen Arsip</strong><br>
  Aplikasi pengelolaan arsip digital berbasis web
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Filament-v5-FDAE4B?style=flat&logo=filament&logoColor=white" alt="Filament v5">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-v4-06B6D4?style=flat&logo=tailwindcss&logoColor=white" alt="Tailwind CSS v4">
</p>

---

## 📋 Tentang Aplikasi

SIMABIMA adalah sistem manajemen arsip digital yang dirancang untuk memudahkan pengelolaan, pencarian, dan eksplorasi arsip berdasarkan hierarki unit organisasi. Dibangun di atas **Laravel 12** dan **Filament v5**, aplikasi ini menyediakan antarmuka admin yang modern dan responsif.

### ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🗂️ **Eksplorasi Arsip** | Navigasi arsip melalui hierarki organisasi (Grup → Unit → Sub Unit → Tabel Arsip) |
| 📁 **Manajemen Arsip** | Upload, edit, hapus, dan unduh dokumen arsip dengan tracking jumlah unduhan |
| 🏢 **Unit Organisasi** | Manajemen struktur hierarki: Sekretariat, Bidang, dan UPT beserta sub-bagiannya |
| 🏷️ **Kategori Arsip** | Pengelompokan arsip berdasarkan kategori |
| 👥 **Manajemen Pengguna** | CRUD pengguna dengan kontrol peran (Administrator / Staff) |
| 📊 **Dasbor Statistik** | Statistik arsip, grafik unggahan per bulan, dokumen terpopuler, arsip terbaru |
| 👤 **Profil Pengguna** | Setiap pengguna dapat mengelola profil dan mengubah password sendiri |
| 🔐 **Lupa Password** | Reset password melalui email |

### 🏢 Hierarki Unit Organisasi

```
├── Sekretariat
│   ├── Sub Bagian Umum dan Kepegawaian
│   ├── Sub Bagian Perencanaan
│   └── ... (7 unit)
├── Bidang
│   ├── Bidang 1
│   └── ... (4 bidang)
└── UPT
    ├── UPT Kota
    │   ├── Sub Bagian TU
    │   └── ...
    └── ... (4 UPT × 3 sub bagian)
```

### 👤 Peran Pengguna

| Peran | Akses |
|---|---|
| **Administrator** | Akses penuh: CRUD arsip, pengguna, unit organisasi, kategori |
| **Staff** | Lihat & unduh arsip, upload arsip sendiri, edit profil |

---

## 🛠️ Teknologi

- **Backend:** Laravel 12, PHP 8.2+
- **Admin Panel:** Filament v5 (Livewire 3)
- **Frontend:** Tailwind CSS v4, Vite 7
- **Database:** MySQL 8.0
- **Storage:** Laravel Local Disk (dapat dikonfigurasi ke S3)

---

## ⚙️ Instalasi

### Prasyarat

Pastikan environment Anda memiliki:

- PHP **8.2** atau lebih tinggi (dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`)
- **Composer** 2.x
- **Node.js** 20+ dan **npm**
- **MySQL** 8.0+

### Langkah Instalasi

**1. Clone repository**

```bash
git clone https://github.com/AnandaBintang/simabima-archive.git
cd simabima-archive
```

**2. Install dependensi PHP**

```bash
composer install
```

**3. Install dependensi Node.js & build assets**

```bash
npm install
npm run build
```

**4. Salin dan konfigurasi file environment**

```bash
cp .env.example .env
php artisan key:generate
```

**5. Konfigurasi database di `.env`**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simabima_archive
DB_USERNAME=root
DB_PASSWORD=
```

**6. Buat database, jalankan migrasi, dan isi data awal**

```bash
php artisan migrate --seed
```

**7. Buat symlink storage**

```bash
php artisan storage:link
```

**8. Jalankan server lokal**

```bash
php artisan serve
```

Akses aplikasi di: **http://127.0.0.1:8000/admin**

---

## 🔑 Akun Default

Setelah menjalankan `php artisan migrate --seed`, akun berikut tersedia:

| Peran | Username | Email | Password |
|---|---|---|---|
| Administrator | `admin` | `admin@simabima.com` | `password` |
| Staff | `staff` | `staff@simabima.com` | `password` |

> ⚠️ **Segera ganti password** setelah login pertama di lingkungan produksi.

---

## 📧 Konfigurasi Email (Lupa Password)

Aplikasi menggunakan Gmail SMTP. Konfigurasi di `.env`:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-google-app-password
MAIL_FROM_ADDRESS="your-gmail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Cara Mendapatkan Google App Password

1. Aktifkan **2-Step Verification** di [myaccount.google.com/security](https://myaccount.google.com/security)
2. Buka **App passwords** → buat baru → pilih "Other" → beri nama "SIMABIMA"
3. Masukkan 16-karakter password yang dihasilkan ke `MAIL_PASSWORD`

---

## 🚀 Perintah Berguna

```bash
# Jalankan server development (Laravel + Vite bersamaan)
composer run dev

# Build assets untuk produksi
npm run build

# Jalankan migrasi ulang + seed
php artisan migrate:fresh --seed

# Bersihkan semua cache
php artisan optimize:clear

# Lihat log secara real-time
php artisan pail

# Jalankan queue worker (untuk job email, dsb.)
php artisan queue:work
```

---

## 📁 Struktur Direktori Penting

```
app/
├── Enums/
│   └── UserRole.php              # Enum peran: Administrator, Staff
├── Filament/
│   ├── Pages/
│   │   ├── ArchiveExplorer.php   # Halaman eksplorasi arsip hierarkis
│   │   └── EditProfile.php       # Halaman profil pengguna
│   ├── Resources/
│   │   ├── ArchiveResource.php   # CRUD arsip
│   │   ├── ArchiveCategoryResource.php
│   │   ├── OrganizationUnitResource.php
│   │   └── UserResource.php
│   └── Widgets/
│       ├── StatsOverviewWidget.php
│       ├── ArchivesByMonthChart.php
│       ├── LatestArchivesWidget.php
│       └── PopularDownloadsWidget.php
├── Models/
│   ├── Archive.php
│   ├── ArchiveCategory.php
│   ├── OrganizationUnit.php      # Hierarki: group → unit → sub_unit
│   └── User.php
└── Policies/                     # Otorisasi berbasis peran
database/
├── migrations/
└── seeders/
    ├── DatabaseSeeder.php
    ├── OrganizationUnitSeeder.php # 30 unit organisasi
    ├── ArchiveCategorySeeder.php
    └── UserSeeder.php
```

---

## 🔒 Hak Akses Detail

| Fitur | Administrator | Staff |
|---|---|---|
| Lihat arsip | ✅ | ✅ |
| Upload arsip | ✅ | ✅ |
| Edit arsip | ✅ | Milik sendiri |
| Hapus arsip | ✅ | Milik sendiri |
| Unduh arsip | ✅ | ✅ |
| Kelola pengguna | ✅ | ❌ |
| Hapus pengguna (bulk) | ✅ | ❌ |
| Kelola unit organisasi | ✅ | ❌ |
| Kelola kategori arsip | ✅ | ❌ |
| Edit profil sendiri | ✅ | ✅ |

---

## 🐛 Troubleshooting

**Halaman kosong / error 500**
```bash
php artisan optimize:clear
# Cek storage/logs/laravel.log untuk detail error
```

**Upload file tidak berfungsi**
```bash
php artisan storage:link
# Pastikan folder storage/app/public writable
```

**Email tidak terkirim**
```bash
# Test kirim email via tinker
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('anda@email.com')->subject('Test SIMABIMA'));
```

**Cache/tampilan tidak update**
```bash
php artisan view:clear
php artisan cache:clear
```

---

## 📄 Lisensi

Aplikasi ini dikembangkan untuk keperluan internal instansi. Seluruh hak cipta dilindungi.
