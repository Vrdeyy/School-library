# 📚 Perpus-Idham: Smart Library System

Sistem Manajemen Perpustakaan Modern berbasis QR Code dengan Dashboard Admin yang interaktif dan Kiosk Mandiri yang user-friendly.

---

## 🚀 Fitur Utama

- **Dashboard Real-Time**: Statistik live, grafik tren peminjaman, dan status koleksi.
- **Kiosk Mandiri (Self-Service)**: Sistem scan QR Code untuk pinjam dan balikkan buku tanpa antri.
- **Import/Export Cerdas**: Import ribuan data buku/user dari Excel dengan schema yang simple dan auto-mapping.
- **Keamanan Data**: Login PIN 6-digit, proteksi transaksi, dan log audit otomatis.
- **Pemeliharaan Sistem**: Fitur reset data selektif untuk maintain server agar tetap ringan.
- **Manajemen User**: Support nomor telepon (WhatsApp link) memudahkan admin menghubungi peminjam.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11
- **Admin Panel**: Filament v3 (TALL Stack)
- **Database**: MySQL / MariaDB
- **Frontend Kiosk**: Alpine.js + Tailwind CSS
- **Library Utama**:
    - `Maatwebsite Excel` (Import/Export)
    - `Simple QR Code` (Generate QR)
    - `Heroicons` (Icons)

---

## 🔄 Alur Transaksi (Flow)

### 1. Peminjaman Buku (Kiosk)

1. **Login**: User scan kartu anggota (QR) atau masukkan NIS & PIN di halaman `/kiosk`.
2. **Scan Buku**: Masukkan/Scan kode unik buku (ID Item).
3. **Konfirmasi**: Klik "Pinjam". Status di Admin akan berubah jadi **Pending**.
4. **Approval**: Admin meninjau permintaan di Dashboard Admin, lalu klik "Approve".
5. **Selesai**: Buku resmi dipinjam, stok berkurang, dan masa pinjam (7 hari) dimulai.

### 2. Pengembalian Buku (Kiosk)

1. **Scan Balik**: User scan kode buku di Kiosk pada mode "Pengembalian".
2. **Konfirmasi**: Status berubah jadi **Returning (Kiosk)**.
3. **Final Check**: Admin menerima buku fisik, lalu klik "Approve Return" di Dashboard.
4. **Selesai**: Buku kembali tersedia, riwayat tersimpan permanen.

---

## 📂 Struktur Alamat (URL)

| Fitur               | Alamat URL     | Akses                  |
| ------------------- | -------------- | ---------------------- |
| **Katalog Publik**  | `/`            | Umum (Cek Koleksi)     |
| **Kiosk Mandiri**   | `/kiosk`       | User / Member (Pinjam) |
| **Dashboard Admin** | `/admin`       | Petugas (Admin)        |
| **Login Admin**     | `/admin/login` | Login Petugas          |

---

## 📦 Panduan Instalasi (Setup Detail)

Ikuti langkah ini satu per satu (untuk pemula):

1. **Clone & Masuk Folder**:

    ```bash
    git clone [url-repo]
    cd perpus-idham
    ```

2. **Install Library (Composer)**:

    ```bash
    composer install
    ```

3. **Install Asset (NPM)**:

    ```bash
    npm install && npm run build
    ```

4. **Setup Environment**:
    - Copy file `.env.example` menjadi `.env`.
    - Setup database di `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5. **Generate Kunci Aplikasi**:

    ```bash
    php artisan key:generate
    ```

6. **Migrasi Database & Seeder**:

    ```bash
    php artisan migrate --seed
    ```

    _Catatan: Akun admin default biasanya admin@perpus.com / password_

7. **Jalankan Server**:
    ```bash
    php artisan serve
    ```
    Buka `http://127.0.0.1:8000` di browser.

---

## 📋 Struktur Data Import (Excel)

Jika ingin import data massal di menu **System Tools > Import / Export**:

### **User Import**

- **Kolom Wajib**: `name`, `email`
- **Kolom Opsional**: `phone`, `password` (default: 12345678), `nis`, `kelas`, `pin`.

### **Book Import**

- **Kolom Wajib**: `title`
- **Kolom Opsional**: `author`, `publisher`, `year`, `isbn`, `stock`.

---

## 🛡️ Tips Keamanan & Maintenance

- Gunakan menu **System Maintenance** secara berkala untuk membersihkan Audit Log lama.
- Selalu backup data sebelum melakukan **Reset Data** total.
- Pastikan printer siap untuk mencetak Kartu Anggota (QR Code) setiap ada member baru.

---
