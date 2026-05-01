<div align="center">
  <img src="https://raw.githubusercontent.com/filamentphp/filament/refs/heads/4.x/art/banner.webp" width="400" alt="Filament">

  <h1>TIXEVENT - UKK 2026</h1>
  <p><strong>Platform E-Ticketing Modern dengan Arsitektur VILT Stack</strong></p>

  <!-- Badges -->
  <p>
    <img src="https://img.shields.io/badge/Laravel-13.6.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-8.5.5-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/Vue.js-3.5.33-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue">
    <img src="https://img.shields.io/badge/Filament-5.6.1-FBBF24?style=for-the-badge&logo=laravel&logoColor=black" alt="Filament">
    <img src="https://img.shields.io/badge/TailwindCSS-4.2.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind">
    <img src="https://img.shields.io/badge/Inertia.js-2.3.21-4FC08D?style=for-the-badge&logo=inertia.js&logoColor=white" alt="Inertia.js">
    <img src="https://img.shields.io/badge/Linux-2496ED?style=for-the-badge&logo=linux&logoColor=white" alt="Linux">
    <img src="https://img.shields.io/badge/Docker-Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
  </p>
</div>

---

TIXEVENT adalah website platform e-ticketing modern yang dirancang untuk manajemen dan penjualan tiket event. Project ini dibangun khusus untuk memenuhi standar **Ujian Kompetensi Keahlian (UKK) 2026**.

Sistem ini memfasilitasi dua sisi pengguna:
- **Frontend (Pembeli):** Katalog event dinamis, pembelian tiket, klaim voucher diskon, dan e-ticket digital (PDF & QR Code).
- **Backend (Panitia):** Dashboard analitik Filament yang responsif, manajemen event, verifikasi manual, scanner QR code langsung dari sistem, dan ekspor laporan.

---

## Cuplikan Layar (Screenshots)

<div align="center">
  <img src="docs/screenshots/landing.png" alt="Tampilan Landing Page" width="800">
  <p><em>Halaman Utama (Frontend)</em></p>

  <img src="docs/screenshots/admin.png" alt="Tampilan Admin Dashboard" width="800">
  <p><em>Halaman Admin Dashboard (Filament)</em></p>
</div>

---

## Arsitektur & Teknologi

Project ini menggunakan arsitektur **VILT Stack** modern dengan performa tinggi dan berbagai integrasi pihak ketiga:

### Core Stack
- **Frontend:** Vue.js 3.5.33 (Composition API), Inertia.js 2.3.21, Vite 6.0.6, Tailwind CSS 4.2.4.
- **Backend:** Laravel 13.6.0, PHP 8.5.5, Livewire 4.2.4.
- **Admin Panel:** Filament v5.6.1.
- **Database & Cache:** MySQL, Redis.

### Library Utama
- `barryvdh/laravel-dompdf` (Pembuatan e-ticket berformat PDF)
- `simplesoftwareio/simple-qrcode` (Pembuatan QR Code unik per tiket)
- Filament Exporter (Ekstrak laporan CSV & XLSX)
- **Environment:** Laravel Sail (Docker).

---

## Panduan Instalasi (Setup Guide)

Aplikasi ini menggunakan Docker (Laravel Sail) untuk standarisasi lingkungan kerja. Anda tidak perlu menginstal PHP, MySQL, atau Redis secara native di komputer Anda.

### Prasyarat Sistem
- **Pengguna Windows:** Sangat disarankan menggunakan **WSL2** (Windows Subsystem for Linux) dan Docker Desktop dengan integrasi WSL2 yang aktif. Semua perintah CLI di bawah ini harus dijalankan di terminal Linux (Ubuntu) melalui WSL.
- **Pengguna Linux / macOS:** Pastikan Docker Engine dan Docker Compose telah terinstal dan berjalan.

### Langkah-langkah Setup

**1. Clone Repositori**
```bash
git clone https://github.com/Suzuya4w/tixevent.git
cd tixevent
```

**2. Instalasi Dependensi PHP**
Instal seluruh package backend menggunakan image composer bawaan Docker:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

**3. Konfigurasi Environment**
Gandakan file environment:
```bash
cp .env.example .env
```
*(Catatan: Konfigurasi `.env.example` sudah disesuaikan secara bawaan untuk Laravel Sail).*

**4. Jalankan Kontainer Docker**
Nyalakan seluruh server (PHP, Nginx, MySQL, Redis) di latar belakang:
```bash
./vendor/bin/sail up -d
```

**5. Inisialisasi Database & Storage**
Lakukan generate key, jalankan migrasi tabel beserta *seeder* awal, dan hubungkan direktori storage:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan storage:link
```

**6. Compile Aset Frontend (Vue & Tailwind)**
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```
*(Gunakan `./vendor/bin/sail npm run dev` jika Anda ingin melakukan modifikasi secara live).*

---

## Menjalankan Background Task

Platform ini menggunakan **Redis** untuk antrean tugas asinkron (Queue) seperti pembatalan tiket otomatis jika batas waktu pembayaran habis. Anda harus menjalankan worker pada *tab terminal yang terpisah*:

**Worker untuk Eksekusi Antrean (Queue):**
```bash
./vendor/bin/sail artisan queue:work
```

**Worker untuk Penjadwalan Waktu (Scheduler):**
```bash
./vendor/bin/sail artisan schedule:work
```

---

## Akses Aplikasi

Aplikasi TIXEVENT Anda kini siap digunakan! Buka melalui browser:
- **Frontend (Pembeli):** `http://localhost`
- **Admin Panel (Panitia):** `http://localhost/admin`

Untuk masuk ke halaman admin, gunakan akun yang dibuat oleh *Seeder*, atau buat akun Super Admin baru dengan perintah:
```bash
./vendor/bin/sail artisan make:filament-user
```
