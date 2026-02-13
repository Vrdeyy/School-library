# 📚 SMKYAJ-LIBRARY (Comic Style v2.0) 💥

[![Laravel Version](https://img.shields.io/badge/Laravel-v11-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-v3-f97316?style=for-the-badge&logo=filament)](https://filamentphp.com)
[![Tailwind Version](https://img.shields.io/badge/Tailwind-v3.4-38bdf8?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)

**SMKYAJ-LIBRARY** adalah sistem manajemen perpustakaan modern dengan estetika unik **Comic Book/Pop-Art**. Dibangun khusus untuk memberikan pengalaman visual yang berani, dinamis, dan premium layaknya membaca komik Marvel/DC, sistem ini menggabungkan kecepatan Laravel 11 dengan kekuatan Filament v3.

---

## 🎨 Aesthetic & Themes

Sistem ini menggunakan kustomisasi UI yang sangat spesifik, meliputi:

- **Comic Halftone & Screentone**: Tekstur titik-titik retro pada background.
- **Onomatopoeia**: Efek suara visual seperti _SMASH!_, _CRUSH!_, dan _YES!_ yang tersebar di UI.
- **Chromatic Offset**: Efek bayangan teks berwarna merah/cyan untuk memberikan kesan kedalaman 3D retro.
- **Speed Lines & Ink Splats**: Elemen dekoratif dinamis yang membuat dashboard tidak terasa kaku.
- **Sticker Effects**: Ikon-ikon dengan outline putih tebal bergaya stiker.

---

## 🔄 Flow & Alur Kerja (Workflow)

### 1. Katalog Publik (`/`)

User bisa mencari buku tanpa perlu login. Pencarian menggunakan real-time filter untuk judul, penulis, maupun kategori.

### 2. Kiosk Mandiri (`/kiosk`)

Halaman khusus untuk siswa melakukan transaksi mandiri:

1. **Login Mode**: Scan **Badge QR** atau masukkan **ID & PIN**.
2. **Action Mode**: Pilih **Pinjam** atau **Kembali**.
3. **Identification**: Scan kode buku (QR) atau input manual.
4. **Queue**: Data masuk ke antrian (Pending) untuk menunggu persetujuan admin.

### 3. Dashboard Admin (`/admin`)

Pusat kontrol bagi petugas perpustakaan:

- **Approval System**: Menyetujui peminjaman (Borrow) atau pengembalian (Return) dari Kiosk.
- **User Management**: Mengelola data siswa dan cetak kartu anggota.
- **Inventory Control**: Mengelola koleksi buku dan cetak label/stiker buku.
- **Report Center**: Laporan bulanan transaksional yang bisa diekspor ke Excel.

---

## 🛠️ Tech Stack

- **Backend & Core**: Laravel 11 & PHP 8.2+
- **Admin Dashboard**: Filament v3 (TALL Stack)
- **Frontend Interactivity**: Alpine.js & Tailwind CSS
- **Assets Bundler**: Vite
- **Utilities**:
    - `Maatwebsite/Laravel-Excel` (Otomasi Data)
    - `Simple-QRCode` (Generasi Kode Batang)
    - `Carbon` (Manajemen Waktu)

---

## 📦 Panduan Instalasi Lengkap

Ikuti langkah teknis berikut untuk menjalankan project di server lokal:

1. **Persiapan Project**:

    ```bash
    git clone https://github.com/Vrdeyy/School-library.git
    cd School-library
    ```

2. **Backend Dependencies**:

    ```bash
    composer install
    ```

3. **Frontend Assets**:

    ```bash
    npm install
    npm run build
    ```

4. **Koneksi Database**:
    - Duplikat `.env.example` menjadi `.env`.
    - Atur `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai database lokal kamu.

5. **Generate Security Key & Link Storage**:

    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

6. **Migrasi & Seeder (Data Awal)**:

    ```bash
    php artisan migrate --seed
    ```

7. **Jalankan Aplikasi**:
    ```bash
    php artisan serve
    ```
    Akses di `http://127.0.0.1:8000`.

---

## 📑 Panduan Lengkap Admin

### 🚀 Import Data dari Excel

Proses ini sangat vital untuk efisiensi input data massal. Masuk ke menu **System Tools > Import / Export**:

| Fitur            | Header Kolom Wajib (Case Sensitive) | Kolom Opsional                                                |
| :--------------- | :---------------------------------- | :------------------------------------------------------------ |
| **USER / SISWA** | `name`, `email`, `password`         | `phone`, `role`, `id_pengenal_siswa`, `kelas`, `pin`, `limit` |
| **BUKU / BUKU**  | `title`                             | `author`, `publisher`, `year`, `isbn`, `stock`                |

**Catatan Penting:**

- Gunakan format **.XLSX** untuk hasil terbaik.
- Jika kolom `password` dikosongkan pada User, sistem akan menggunakan default (biasanya `12345678`).
- Pastikan tidak ada karakter aneh pada field `email` dan `id_pengenal_siswa`.

### 💾 Export Data & Laporan

- **Users/Books**: Kamu bisa mengekspor database saat ini ke file Excel sebagai backup atau audit.
- **Borrow Data**: Kamu bisa memfilter catatan peminjaman berdasarkan **Bulan** dan **Tahun** untuk laporan periodik.

### 🧹 Pemeliharaan Sistem (Maintenance)

Tersedia menu **Settings > Pemeliharaan Sistem** untuk menghapus data lama:

- **Reset Users**: Menghapus semua user kecuali akun admin yang sedang login.
- **Reset Books**: Menghapus semua metadata buku dan item fisik.
- **Clear Audit Logs**: Membersihkan catatan aktivitas sistem agar database tetap ringan.

### 🖨️ Printing & QR Code

- Admin bisa mencetak **User Card** secara massal dengan kode QR unik.
- Admin bisa mencetak **Book Label** yang berisi kode unik barcode/QR buku untuk ditempel di sampul fisik.

---

## 🔑 Akun Default (Demo)

- **Admin**: `admin@admin.com` / `password`
- **User**: `user@user.com` / `password`

---

Created with ❤️ by **Kampunk Dev Team** • © {{ date('Y') }}
