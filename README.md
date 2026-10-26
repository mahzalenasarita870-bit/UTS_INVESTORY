# 🌸 GlowDesk - Skincare POS & Inventory Management System

> **Integrated Laravel Project** — Sistem Pengelolaan Inventaris, Transaksi Penjualan POS (Kasir), dan Barang Masuk Terintegrasi.

---

### 📌 1. Project Overview

| Parameter | Deskripsi |
| :--- | :--- |
| **Nama Aplikasi** | **GlowDesk** (Skincare Inventory & POS System) |
| **Deskripsi Utama** | Sistem manajemen inventaris dan Point of Sales (POS) berbasis web yang dirancang khusus untuk operasional toko *skincare* dan produk kecantikan. Sistem ini membantu mencatat transaksi secara real-time, mengotomatisasi pembaruan stok produk, mendukung pencetakan struk kasir thermal, serta menyajikan analisis performa toko melalui dashboard interaktif. |
| **Konteks Proyek** | Dikembangkan untuk memenuhi kriteria **UTS Integrated Laravel Project (Web Programming 2)** dengan fokus utama pada **konsistensi stok**, **transaksi atomik database**, **role-based access control (RBAC)**, dan **validasi data server-side**. |

---

### ⚙️ 2. Tech Stack & Requirements

| Komponen | Spesifikasi / Technology |
| :--- | :--- |
| **Framework Backend** | Laravel 10.x / 11.x |
| **Bahasa Pemrograman** | PHP >= 8.1 |
| **Database Engine** | MySQL 8.0+ |
| **Frontend Framework** | Blade Template Engine, Bootstrap 5.3, Bootstrap Icons |
| **Chart & Analytics** | Chart.js |
| **Package Manager** | Composer, Node.js & NPM |

---

### 🔐 3. Role & Access Control (RBAC)

Sistem membedakan hak akses secara mutlak di tingkat server (*Middleware & Policy Enforcement*):

| Fitur / Modul | Admin / Owner | Staff / Kasir |
| :--- | :---: | :---: |
| Authentication (Login/Logout) | ✅ | ✅ |
| Dashboard Analytics & Stok Menipis | ✅ | ✅ |
| Master Data Categories (CRUD) | ✅ | ❌ |
| Master Data Products Inventory (CRUD) | ✅ | ❌ |
| Master Data Suppliers (CRUD) | ✅ | ❌ |
| User Management (CRUD Staff/Admin) | ✅ | ❌ |
| Input Transaksi Barang Masuk (Purchases) | ✅ | ✅ |
| POS Kasir / Transaksi Keluar (Sales) | ✅ | ✅ |
| Auto-Scan Barcode / SKU Reader | ✅ | ✅ |
| Pembatalan Item Transaksi & Rollback Stok | ✅ | ✅ |
| Laporan Penjualan (Reports & Filter) | ✅ | ✅ |

---

### 🗄️ 4. Architecture & Data Model (ERD)

Aplikasi dibangun menggunakan pola arsitektur standar **Laravel MVC (Model-View-Controller)**.

#### A. Relasi Database (Eloquent Relationships)

| Model Asal | Tipe Relasi | Model Tujuan | Keterangan |
| :--- | :--- | :--- | :--- |
| `User` | **hasMany** | `Sale` | Satu user/kasir dapat melayani banyak transaksi penjualan |
| `User` | **hasMany** | `Purchase` | Satu user/admin dapat membuat banyak transaksi barang masuk |
| `Category` | **hasMany** | `Product` | Satu kategori menampung banyak produk skincare |
| `Product` | **belongsTo** | `Category` | Setiap produk terikat pada satu kategori |
| `Supplier` | **hasMany** | `Purchase` | Pemasok dapat menyuplai banyak transaksi pembelian |
| `Purchase` | **belongsTo** | `Supplier` | Pembelian terhubung ke satu pemasok |
| `Purchase` | **hasMany** | `PurchaseItem` | Satu header pembelian memiliki banyak detail item |
| `PurchaseItem` | **belongsTo** | `Purchase` | Detail barang masuk terikat pada header pembelian |
| `PurchaseItem` | **belongsTo** | `Product` | Detail barang masuk merujuk pada produk spesifik |
| `Sale` | **belongsTo** | `User` | Transaksi penjualan dicatat oleh satu user/kasir |
| `Sale` | **hasMany** | `SaleItem` | Satu header transaksi memiliki banyak item barang |
| `SaleItem` | **belongsTo** | `Sale` | Detail barang keluar terikat pada header transaksi |
| `SaleItem` | **belongsTo** | `Product` | Detail barang keluar merujuk pada produk spesifik |

#### B. Skema Alur Diagram ERD

```text
[User] ───< [Sale] ───< [SaleItem] >─── [Product] >─── [Category]
  │                                         │
  └───< [Purchase] ───< [PurchaseItem] >────┘
            │
            └───> [Supplier]
```

### 💡 5. Business Rules & Transaction Logic

| Poin Business Rule | Deskripsi Implementasi Logic Server |
| :--- | :--- |
| **Konsistensi Stok** | Transaksi penjualan ditolak secara tegas oleh server jika jumlah beli (`qty`) melebihi sisa stok yang ada di database. Kuantitas input harus numerik bernilai $> 0$. |
| **Database Transaction** | Penyimpanan transaksi (`sales`/`purchases`) dan detail item dibungkus dalam `DB::transaction()`. Jika ada satu query yang gagal, seluruh proses di-*rollback* otomatis. |
| **Server-side Calculation** | Harga satuan dan total transaksi dihitung murni dari database di sisi server, bukan mempercayai input variabel harga dari browser/frontend. |
| **Rollback & Pembatalan** | Pembatalan transaksi atau penghapusan item transaksi akan secara otomatis mengembalikan stok produk secara akurat. |

---

### 🚀 6. Installation & Environment Setup

| Langkah | Perintah CLI / Command | Keterangan |
| :--- | :--- | :--- |
| **1. Clone Repo** | `git clone https://github.com/mahzalenasarita870-bit/UTS_INVESTORY.git` | Unduh repositori project |
| **2. Masuk Folder** | `cd glowdesk-pos` | Pindah ke direktori utama project |
| **3. Install PHP Deps** | `composer install` | Mengunduh dependensi Laravel via Composer |
| **4. Copy .env** | `cp .env.example .env` | Menyiapkan berkas lingkungan aplikasi |
| **5. Generate Key** | `php artisan key:generate` | Membuat encryption key aplikasi |
| **6. Konfigurasi DB** | *Buka file `.env`* | Sesuaikan nama `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` |
| **7. Migrate & Seed** | `php artisan migrate:fresh --seed` | Jalankan struktur tabel dan data demo |
| **8. Install JS Deps** | `npm install && npm run build` | Compile aset visual frontend |
| **9. Serve App** | `php artisan serve` | Jalankan lokal server di `http://127.0.0.1:8000` |

---

### 🔑 7. Demo Accounts

Gunakan akun dummy bawaan seeder untuk pengujian aplikasi (Password terenkripsi via `Bcrypt`):

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin / Owner** | `admin@glowdesk.com` | `password` | Full Control (Master Data, User Management, Reports, Transactions) |
| **Staff / Kasir** | `staff@glowdesk.com` | `password` | Limited Access (POS Kasir, Barang Masuk, Laporan Penjualan) |

---

### 🧪 8. Minimum Test Scenarios

| Modul | Skenario Uji | Ekspektasi Hasil | Pass/Fail |
| :--- | :--- | :--- | :---: |
| **Auth** | Login menggunakan password salah | Sistem menolak dan menampilkan pesan error | ✅ Pass |
| **Auth** | User belum login mengakses `/dashboard` | Otomatis di-redirect kembali ke halaman Login | ✅ Pass |
| **RBAC** | Staff/Kasir mengetik URL `/users` secara manual | Ditolak oleh server dengan HTTP status 403 Forbidden | ✅ Pass |
| **Product** | Menginput SKU yang sudah pernah terdaftar | Ditolak oleh validasi server (`unique:products,sku`) | ✅ Pass |
| **POS Kasir** | Input transaksi dengan Qty > Sisa Stok | Transaksi dibatalkan server, muncul alert stok tidak cukup | ✅ Pass |
| **POS Kasir** | Scan barcode / SKU yang valid | Produk otomatis masuk ke daftar keranjang kasir | ✅ Pass |
| **POS Kasir** | Konfirmasi simpan transaksi penjualan | Stok produk berkurang otomatis & cetak struk siap dijalankan | ✅ Pass |
| **Purchase** | Simpan transaksi barang masuk | Stok produk bertambah secara otomatis | ✅ Pass |
| **Rollback** | Menghapus item pada transaksi | Item terhapus dan stok dikembalikan sebesar kuantitas item | ✅ Pass |

---

### 📝 9. Known Limitations

| Keterbatasan | Deskripsi |
| :--- | :--- |
| **Single-Store Scope** | Sistem saat ini dirancang untuk operasional satu toko (belum mendukung multi-cabang/multi-gudang). |
| **Thermal Printing** | Modul cetak struk memanfaatkan perintah `window.print()` browser yang disesuaikan untuk printer thermal ukuran 58mm/80mm. |

---
**GlowDesk POS & Inventory System** — Built for Web Programming 2 Project Assessment.