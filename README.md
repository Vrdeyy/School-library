# 📚 SMKYAJ-LIBRARY (Comic Style v2.0) 💥

[![Laravel Version](https://img.shields.io/badge/Laravel-v12-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-v3-f97316?style=for-the-badge&logo=filament)](https://filamentphp.com)
[![Tailwind Version](https://img.shields.io/badge/Tailwind-v3.4-38bdf8?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)

**SMKYAJ-LIBRARY** adalah sistem manajemen perpustakaan modern dengan estetika unik **Comic Book/Pop-Art**. Dibangun khusus untuk memberikan pengalaman visual yang berani, dinamis, dan premium layaknya membaca komik Marvel/DC.

---

## �️ Panduan Instalasi (Setup Project)

Ikuti langkah teknis berikut untuk menjalankan project di server lokal:

1.  **Clone Project**:

    ```bash
    git clone https://github.com/Vrdeyy/School-library.git
    cd School-library
    ```

2.  **Install Dependencies**:

    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**:
    - Duplikat `.env.example` menjadi `.env`.
    - Setup database di `.env` kamu (contoh: `DB_DATABASE=school_library`).

4.  **Generate Key & Link Storage**:

    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

5.  **Migrasi & Dummy Data**:

    ```bash
    # Menjalankan migrasi dan seeder default (Akun Admin & User demo)
    php artisan migrate --seed

    # OPTIONAL: Tambahkan data peminjaman dummy untuk test laporan & grafik
    php artisan db:seed --class=DebugBorrowSeeder
    ```

6.  **Build Assets & Run**:
    ```bash
    npm run build
    php artisan serve
    ```
    Akses di: `http://127.0.0.1:8000`

---

## � Akun Default (Login Info)

| Role            | Email              | Password   | PIN (Kiosk) |
| :-------------- | :----------------- | :--------- | :---------- |
| **Admin**       | `admin@perpus.com` | `password` | `123456`    |
| **User (Demo)** | `user@perpus.com`  | `password` | `123456`    |

---

## 🛡️ Panduan Admin (POV Staff)

Sebagai admin/petugas perpustakaan, fungsionalitas utama ada di dashboard `/admin`:

### 1. Sistem Approval (Persetujuan)

Semua transaksi dari Kiosk tidak langsung diproses, melainkan masuk ke antrean.

- Masuk ke menu **Management > Transaksi Peminjaman**.
- Cek tab **Pending**.
- Klik tombol **Approve (Ceklis)** untuk menyetujui peminjaman atau pengembalian.

### 2. Cetak Kartu Anggota (Bulk Print)

- Masuk ke menu **User Management > Users**.
- Pilih menu button **All** untuk membuka semua user
- Pilih/Centang banyak user sekaligus.
- Di menu "Bulk Actions" (pojok kiri bawah tabel), pilih **Cetak Kartu Massal**.
- Sistem akan membuka tab baru dengan layout kartu ID (85x54mm) yang siap cetak di kertas A4.

### 3. Cetak Label Buku (Bulk Print)

- Masuk ke menu **Inventory > Buku**.
- Klik tombol **List Item** pada salah satu buku atau pilih banyak item buku.
- Gunakan fitur **Cetak Massal Label** untuk mencetak stiker barcode/QR yang akan ditempel di fisik buku.
- Ukuran label default adalah `100mm x 40mm`, dioptimalkan untuk cetak 2 kolom di kertas A4.

### 4. Tool Import/Export

- Gunakan menu **System Tools > Import / Export** untuk memindahkan data dari Excel ke sistem secara instan.

---

## 📱 Mekanisme Peminjaman (POV User/Siswa)

Proses peminjaman kini dilakukan melalui petugas perpustakaan (Admin) untuk keamanan dan akurasi data:

1.  **Kunjungan**: Siswa datang ke perpustakaan membawa buku yang ingin dipinjam dan **Member Card**.
2.  **Identifikasi Siswa**: Siswa menyerahkan Kartu Anggota kepada Admin, lalu Admin melakukan scan **QR Code** siswa.
3.  **Identifikasi Buku**: Admin melakukan scan **QR/Barcode** yang tertempel pada fisik buku yang dipilih.
4.  **Input & Validasi**: Admin memproses data di sistem.
5.  **Selesai**: Data peminjaman otomatis aktif, dan status buku berubah menjadi **Dipinjam**.

---

## 🎨 Fitur Unggulan

- **Comic Aesthetic UI**: Efek Halftone, Chromatic Offset, dan Onomatopoeia di seluruh dashboard.
- **Kiosk Mode**: Terminal untuk siswa melakukan transaksi Peminjaman Di perpustakaan.
- **Auto QR Generation**: Setiap user dan item buku otomatis mendapatkan QR code unik.
- **Audit Logs**: Melacak setiap aktivitas admin (siapa mengubah apa dan kapan).
- **Maintenance Tools**: Fitur reset data massal untuk pembersihan database secara aman.

---

Created with ❤️ by **Kampunk Dev Team** • © 2026
