# 🎟️ TIXEVENT

TIXEVENT adalah sebuah platform E-Ticketing modern yang dirancang untuk memudahkan proses manajemen dan penjualan tiket event. Project ini dibangun khusus untuk memenuhi standar **Ujian Kompetensi Keahlian (UKK) 2026**.

Menawarkan pengalaman pengguna yang luar biasa dari sisi pembeli (Frontend) maupun penyelenggara/panitia (Admin Panel), dengan fitur utama berupa _QR Code digital_, _E-Ticket PDF_, dan _Voucher Diskon_.

## 🚀 Teknologi yang Digunakan (Tech Stack)

Project ini dibangun di atas arsitektur **VILT Stack** modern:
- **[Vue.js 3](https://vuejs.org/)** (Composition API) & **[Inertia.js](https://inertiajs.com/)** – Untuk Frontend SPA (*Single Page Application*) yang sangat cepat.
- **[Laravel 11](https://laravel.com/)** – Sebagai pondasi Backend (*Framework* PHP paling mutakhir).
- **[Tailwind CSS](https://tailwindcss.com/)** – Untuk desain antarmuka yang responsif dan estetik.
- **[Filament v3](https://filamentphp.com/)** – Sebagai Panel Admin yang *powerful* dan elegan untuk mengelola data event, tiket, transaksi, dan *scan* QR Code.
- **[Laravel Sail (Docker)](https://laravel.com/docs/sail)** – Untuk lingkungan pengembangan lokal yang konsisten dan anti-ribet.

## 🌟 Fitur Utama

### 👩‍💻 Untuk Pengunjung / Pembeli (Frontend)
- **Katalog Event Dinamis**: Pencarian event berdasarkan nama, tanggal, dan kategori.
- **Desain FOMO & Premium**: Tampilan memancing psikologis dengan animasi dan *badge* urgensi.
- **Checkout Mudah**: Pembelian tiket dengan bukti transfer manual yang diawasi penuh oleh admin.
- **Klaim Voucher Diskon**: Pengguna bisa memasukkan kode promosi unik.
- **E-Ticket Digital**: Tiket dikirim langsung ke dashboard pengguna, dilengkapi dengan QR Code unik.
- **Download PDF**: Pengguna dapat menyimpan/mencetak tiket dalam format PDF kapan saja.

### 🕵️‍♂️ Untuk Panitia / Admin (Filament Panel)
- **Dashboard Analitik**: Melihat ringkasan penjualan, tiket yang laku, dan total pengguna.
- **Manajemen Event & Tiket**: Menambahkan acara, mengatur kapasitas/kuota, dan harga per kategori tiket.
- **Verifikasi Pesanan (Order)**: Memverifikasi bukti transfer dan otomatis menerbitkan tiket (Attendee) saat disetujui.
- **Manajemen Voucher**: Membuat kode diskon dengan batas kuota dan masa berlaku.
- **Scanner QR Code terintegrasi**: Admin bisa langsung *scan* QR Code dari HP di hari-H untuk melakukan proses *Check-in* kehadiran secara *real-time*.
- **Export Laporan (CSV)**: Mengekspor data Order, Detail Order, dan Kehadiran untuk arsip panitia.

---

## 🛠️ Panduan Instalasi (Setup Guide)

Karena project ini menggunakan **Laravel Sail (Docker)**, pastikan Anda telah menginstal [Docker Desktop](https://www.docker.com/products/docker-desktop/) di komputer Anda dan menjalankannya.

### Langkah 1: Clone Repositori
Clone project ini ke komputer lokal Anda:
```bash
git clone https://github.com/Suzuya4w/tixevent.git
cd tixevent
```

### Langkah 2: Instalasi Dependensi PHP
Gunakan _image_ Composer kecil dari Docker untuk menginstal vendor (tanpa perlu menginstal PHP di komputer host):
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### Langkah 3: Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`.
```bash
cp .env.example .env
```
Secara default, konfigurasi `.env` sudah sesuai untuk Sail (database menggunakan host `mysql`, redis menggunakan host `redis`).

### Langkah 4: Jalankan Laravel Sail
Mulai kontainer Docker melalui Sail:
```bash
./vendor/bin/sail up -d
```
*(Proses ini akan mengunduh image Docker seperti MySQL, Redis, dan Meilisearch jika belum ada).*

### Langkah 5: Generate Key & Migrasi Database
Setelah kontainer menyala, generate APP_KEY dan lakukan migrasi (beserta *seeding* jika diperlukan):
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
```
*(Opsional: Jalankan `./vendor/bin/sail artisan storage:link` agar gambar bisa diakses).*

### Langkah 6: Install Node.js & Compile Aset Frontend
Buka tab terminal baru (atau jalankan di dalam sail shell):
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```
*(Atau gunakan `npm run dev` jika Anda sedang mengembangkan fitur baru).*

---

## 🌍 Cara Mengakses Aplikasi

Aplikasi TIXEVENT Anda kini siap digunakan! Buka browser Anda:

- **Frontend (Web Pembeli):** [http://localhost](http://localhost)
- **Admin Panel (Filament):** [http://localhost/admin](http://localhost/admin)

> **Login Admin:**  
> Jika Anda menggunakan *Seeder* standar bawaan Laravel/Filament, gunakan kredensial yang dibuat pada seeder, atau buat admin baru dengan perintah:
> ```bash
> ./vendor/bin/sail artisan make:filament-user
> ```

---
*Dibuat dengan ❤️ untuk Ujian Kompetensi Keahlian 2026.*
