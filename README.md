# 📚 Perpus Idham - Modern Library Management System

Aplikasi manajemen perpustakaan modern berbasis **Laravel & Filament** dengan fitur unggulan **Self-Service Kiosk** menggunakan teknologi QR Code yang aman (HMAC Signed).

## 🚀 Fitur Utama

### 🖥️ 1. Self-Service Kiosk (Frontend)

Mode mandiri untuk anggota perpustakaan yang berjalan di tablet/monitor sentuh.

- **Login QR Code / Manual:** Anggota bisa masuk scan kartu member atau input NIS & PIN.
- **Pinjam Mandiri:** Scan buku untuk meminjam (membutuhkan approval admin untuk keamanan).
- **Kembali Mandiri:** Scan buku untuk mengembalikan (status 'Returning' sampai dicek admin).
- **Anti-Fraud:** QR Code buku dan kartu member dilindungi enkripsi (HMAC Signature) agar tidak bisa dipalsukan.

### 👑 2. Admin Panel (Backend)

Dashboard admin yang powerfull menggunakan **Filament V3**.

- **Dashboard Statistik:** Ringkasan jumlah buku, peminjam aktif, dan grafik tren.
- **Manajemen Buku:** CRUD Buku, Stok Opname, dan Cetak Label QR Massal.
- **Manajemen User:** CRUD Anggota, Pembuatan Akun, dan Cetak Kartu Anggota Massal.
- **Pusat Cetak (Bulk Print):** Fitur khusus cetak banyak kartu/label dalam format Grid siap potong.
- **Sirkulasi:** Approval peminjaman/pengembalian dari Kiosk.
- **Audit Logs:** Rekam jejak aktivitas sensitif (Login Kiosk, Export Data, dll).
- **Laporan & Export:** Download data ke Excel.

## 🛠️ Tech Stack & Tools

Aplikasi ini dibangun menggunakan teknologi modern:

- **Framework:** Laravel 11
- **Admin Panel:** FilamentPHP v3
- **Frontend / Interactivity:** Livewire 3 & Alpine.js
- **Styling:** Tailwind CSS
- **Database:** MySQL
- **QR Code Engine:** `simplesoftwareio/simple-qrcode` (Backend) & `html5-qrcode` (Frontend Scanner)
- **PDF/Print:** CSS Grid Print Layout (Native browser print optimization)

## 📥 Cara Setup & Install

Ikuti langkah ini untuk menjalankan aplikasi di local computer:

### Prerequisite

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL/MariaDB)

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone https://github.com/username/perpus-idham.git
    cd perpus-idham
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install && npm run build
    ```

3. **Setup Environment**
    - Copy file `.env.example` menjadi `.env`.
    - Setup konfigurasi database di `.env`.
    - Setup `APP_url` agar QR Code generate link yang benar.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Generate App Key & QR Secret**
    - Pastikan `APP_KEY` terisi.
    - Tambahkan `QR_SECRET` di `.env` (bebas string acak) untuk keamanan tanda tangan QR.

    ```env
    QR_SECRET=rahasia-dapur-perpus-123
    ```

5. **Migrate & Seed (Dummy Data)**
   Script ini akan membuat tabel dan mengisi data dummy (25 user, 100 buku, 50 history).

    ```bash
    php artisan migrate:fresh --seed
    ```

6. **Jalankan Aplikasi**
    ```bash
    php artisan serve
    ```
    Akses di: `http://localhost:8000/admin`

## 🔑 Default Credentials (Dummy)

Gunakan akun ini untuk login setelah seeding:

| Role            | Email              | Password   |
| --------------- | ------------------ | ---------- |
| **Admin**       | `admin@perpus.com` | `password` |
| **User (Demo)** | `user@perpus.com`  | `password` |

## 📖 Alur Sistem (System Flow)

1. **Admin** input data buku & cetak Label QR Buku (tempel di fisik buku).
2. **Admin** input anggota & cetak Kartu Anggota (berisi QR Login).
3. **Anggota** datang ke Kiosk -> Scan Kartu -> Masuk Menu.
4. **Anggota** scan buku yang mau dipinjam -> Status **Pending**.
5. **Admin** mengecek menu Borrow -> Klik **Approve** -> Status **Active/Borrowed**.
6. **Anggota** mengembalikan -> Scan buku di Kiosk -> Status **Returning**.
7. **Admin** mengecek fisik buku -> Klik **Approve Return** -> Stok kembali.
