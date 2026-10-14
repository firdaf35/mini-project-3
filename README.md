# Bloom & Co. Florist - Inventory Management System

Sistem Informasi Manajemen Inventaris Toko Bunga Berbasis Web  
Dikembangkan menggunakan Native PHP (PDO), MySQL, dan Tailwind CSS.

---

## Pendahuluan

Bloom & Co. Florist Inventory Management System adalah aplikasi pengelolaan stok dan inventaris produk toko bunga berbasis web. Aplikasi ini dirancang untuk memudahkan pencatatan data master bunga, pemantauan transaksi barang masuk dan barang keluar secara real-time, serta memastikan tingkat keamanan data yang terstruktur.

Aplikasi ini dibangun menggunakan arsitektur relasional database, transaksi database otomatis (commit/rollback), serta antarmuka UI/UX yang modern dan responsif.

---

## Teknologi dan Tools yang Digunakan

Aplikasi ini dikembangkan menggunakan kombinasi teknologi backend, database, dan frontend berikut:

* Bahasa Pemrograman (Backend): PHP 8.x (Native PHP dengan ekstensi PDO / PHP Data Objects)
* Database Management System: MySQL / MariaDB (Dikelola melalui XAMPP / phpMyAdmin)
* Styling & User Interface (Frontend): Tailwind CSS (Utility-first CSS Framework via CDN)
* Web Server: Apache (XAMPP Environment)
* Text Editor / IDE: Visual Studio Code

---

## Fitur - Fitur Utama Aplikasi

### 1. Autentikasi dan Otorisasi Pengguna
* Sistem Login & Logout: Pengamanan halaman internal menggunakan sesi PHP (`session_start()`).
* Manajemen Peran (Role-Based Access Control): Pemisahan hak akses antara akun Admin dan Staff.
* Keamanan Akses Langsung: Mencegah pengguna tanpa login untuk mengakses halaman internal aplikasi melalui URL.

### 2. Manajemen Data Master (Produk & Kategori)
* CRUD Data Bunga / Produk: Menambahkan, melihat, mengubah (edit), dan menghapus data stok bunga.
* Validasi SKU Unik: Setiap produk bunga wajib memiliki Stock Keeping Unit (SKU) yang unik sebagai identifikasi unik di sistem.
* Pengelompokan Kategori: Produk terhubung secara relasional dengan tabel kategori bunga (Mawar, Lily, Anggrek, Tulip, dan Matahari).
* Manajemen Supplier: Integrasi relasi data pemasok produk.

### 3. Manajemen Transaksi Stok (Stock In & Stock Out)
* Barang Masuk (Stok Masuk): Mencatat penambahan jumlah stok bunga yang diterima dari supplier.
* Barang Keluar (Stok Keluar): Mencatat pengurangan jumlah stok bunga akibat penjualan atau kerusakan.
* Validasi Stok Otomatis: Memiliki sistem proteksi untuk mencegah stok menjadi minus. Transaksi barang keluar akan ditolak jika jumlah yang diminta melebihi stok yang tersedia.
* Database Transaction Safety: Menggunakan mekanisme `$pdo->beginTransaction()`, `$pdo->commit()`, dan `$pdo->rollBack()` untuk memastikan pencatatan riwayat transaksi dan pembaharuan stok di tabel produk berjalan secara konsisten.

### 4. Histori Transaksi dan Laporan
* Pencatatan Audit Trail: Setiap aktivitas barang masuk dan keluar dicatat secara otomatis lengkap dengan stempel waktu (timestamp) dan nama petugas yang melakukan pencatatan.
* Tabel Histori Transaksi: Menampilkan seluruh riwayat alur keluar-masuk stok secara rapi dan kronologis.

### 5. Dashboard Analitik
* Ringkasan Agregat (Live Stats): Menampilkan total variasi item bunga, total akumulasi stok (tangkai/unit), dan jumlah kategori yang aktif secara real-time.
* Tabel Transaksi Terbaru: Menampilkan 5 transaksi stok paling akhir langsung di halaman utama.

---

## Aturan Bisnis dan Keamanan (Business Rules & Security)

Aplikasi ini menerapkan standar aturan bisnis dan keamanan data sebagai berikut:

1. Pencegahan SQL Injection: Seluruh query database menggunakan PDO Prepared Statements (`prepare` dan `execute`) tanpa menggunakan manipulasi string langsung (query concatenation).
2. Pencegahan XSS (Cross-Site Scripting): Setiap output variabel yang ditampilkan ke layar HTML dienkapsulasi menggunakan fungsi `htmlspecialchars()`.
3. Pencegahan Stok Negatif: Angka stok dan kuantitas transaksi divalidasi tidak boleh bernilai negatif.
4. Integritas Relasional Data: Menggunakan Foreign Key Constraints untuk menjaga keterkaitan data antar tabel.

---

## Struktur Folder Proyek

```text
inventory-app/
├── config/
│   └── database.php          # File konfigurasi koneksi database PDO
├── database/
│   └── schema.sql            # File SQL struktur tabel, relasi, dan data awal
├── public/
│   ├── login.php             # Halaman utama autentikasi login
│   ├── logout.php            # Handler untuk menghancurkan sesi login
│   ├── dashboard.php         # Dashboard analitik utama dan statistik
│   ├── products/
│   │   ├── index.php         # Halaman daftar tabel seluruh produk bunga
│   │   ├── create.php        # Form tambah produk bunga baru
│   │   ├── edit.php          # Form edit data produk bunga
│   │   └── delete.php        # Script pemroses hapus produk
│   └── stock/
│       ├── in.php            # Form pencatatan barang masuk
│       ├── out.php           # Form pencatatan barang keluar
│       └── history.php       # Halaman riwayat lengkap transaksi stok
└── README.md                 # Dokumentasi resmi proyek



## Panduan Cara Menjalankan Aplikasi

Berikut adalah petunjuk langkah demi langkah untuk menjalankan aplikasi ini di lingkungan lokal:

### Prasyarat Sistem
* XAMPP (dengan PHP versi 8.0 ke atas).
* Web Browser (Google Chrome, Microsoft Edge, atau Mozilla Firefox).

---

### Langkah 1: Penyiapan Folder Proyek
1. Pindahkan seluruh folder `inventory-app` ke dalam direktori `htdocs` tempat XAMPP terinstal.
   * Lokasi standar di Windows: `C:\xampp\htdocs\inventory-app\`

---

### Langkah 2: Impor Database di phpMyAdmin
1. Buka XAMPP Control Panel, lalu jalankan modul **Apache** dan **MySQL** (klik tombol **Start** pada keduanya).
2. Buka web browser, lalu akses: `http://localhost/phpmyadmin`
3. Buat database baru:
   * Klik menu **New** di panel sebelah kiri.
   * Masukkan nama database: `inventory_app`
   * Klik tombol **Create**.
4. Impor Struktur Tabel:
   * Klik nama database `inventory_app` yang baru saja dibuat.
   * Masuk ke tab **Import** di bagian menu atas.
   * Klik tombol **Choose File** / **Browse**, lalu pilih file `schema.sql` yang berada di direktori proyek (`inventory-app/database/schema.sql`).
   * Gulir ke bawah dan klik tombol **Import** / **Go**.
   * Pastikan seluruh tabel (`users`, `categories`, `products`, `suppliers`, `stock_transactions`) berhasil diimpor.

---

### Langkah 3: Menjalankan Aplikasi di Browser
1. Buka web browser.
2. Masukkan URL berikut pada address bar:
   ```text
   http://localhost/inventory-app/public/login.php