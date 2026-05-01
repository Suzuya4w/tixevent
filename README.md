# TIXEVENT

TIXEVENT adalah platform e-ticketing modern yang dirancang untuk manajemen dan penjualan tiket event. Project ini dibangun khusus untuk memenuhi standar Ujian Kompetensi Keahlian (UKK) 2026.

Sistem ini memfasilitasi dua sisi pengguna:
- **Frontend (Pembeli):** Katalog event, pembelian tiket, klaim voucher diskon, dan download e-ticket PDF yang terintegrasi dengan sistem QR Code.
- **Backend (Panitia/Admin):** Dashboard analitik, manajemen event dan kuota, verifikasi pembayaran manual, scanner QR code untuk check-in kehadiran, dan export laporan data dalam format CSV.

## Arsitektur & Teknologi

Project ini menggunakan arsitektur VILT Stack dengan berbagai integrasi pihak ketiga:
- **Frontend:** Vue.js 3 (Composition API), Inertia.js, Tailwind CSS.
- **Backend:** Laravel 11.
- **Admin Panel:** Filament v3.
- **Database & Cache:** MySQL, Redis.
- **Library Utama:**
  - `barryvdh/laravel-dompdf` (Pembuatan e-ticket berformat PDF)
  - `simplesoftwareio/simple-qrcode` (Pembuatan QR Code unik per tiket)
  - Filament Exporter (Ekstraksi laporan CSV)
- **Environment:** Laravel Sail (Docker).

## Panduan Instalasi

Aplikasi ini menggunakan Docker (Laravel Sail) untuk standarisasi lingkungan kerja. Anda tidak perlu menginstal PHP atau MySQL secara native di komputer Anda.

### Prasyarat Sistem (Penting)
- **Pengguna Windows:** Sangat disarankan/wajib menggunakan **WSL2** (Windows Subsystem for Linux) dan Docker Desktop dengan integrasi WSL2 yang aktif. Semua perintah CLI di bawah ini harus dijalankan di terminal Linux (Ubuntu) melalui WSL.
- **Pengguna Linux / macOS:** Cukup pastikan Docker Engine dan Docker Compose telah terinstal dan berjalan.

### Langkah-langkah Setup

**1. Clone Repositori**
```bash
git clone https://github.com/Suzuya4w/tixevent.git
cd tixevent
```

**2. Instalasi Dependensi PHP**
Instal seluruh package menggunakan image composer bawaan Docker:
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
*(Catatan: Konfigurasi `.env.example` sudah disesuaikan secara bawaan untuk Laravel Sail, termasuk kredensial host `mysql` dan koneksi `redis`).*

**4. Jalankan Kontainer Docker**
Nyalakan server di latar belakang:
```bash
./vendor/bin/sail up -d
```

**5. Setup Aplikasi**
Lakukan generate key, jalankan migrasi tabel beserta *seeder* awal, dan hubungkan direktori storage untuk akses gambar:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan storage:link
```

**6. Compile Aset Frontend**
Kompilasi library Vue dan file Tailwind CSS:
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```
*(Gunakan `./vendor/bin/sail npm run dev` apabila Anda sedang dalam proses pengembangan).*

---

## Menjalankan Background Task (Queue & Scheduler)

Platform ini menggunakan Redis untuk antrean tugas asinkron, seperti pembatalan tiket otomatis jika melewati batas waktu pembayaran. Anda harus menjalankan worker pada *tab terminal yang terpisah*:

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

Aplikasi siap digunakan melalui web browser pada alamat berikut:
- **Frontend (Pembeli):** `http://localhost`
- **Admin Panel (Panitia):** `http://localhost/admin`

Untuk masuk ke halaman admin, silakan gunakan akun yang dibuat secara otomatis oleh `DatabaseSeeder`, atau buat pengguna admin baru secara mandiri melalui terminal:
```bash
./vendor/bin/sail artisan make:filament-user
```
