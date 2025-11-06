# Peta Desa Digital

![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3-orange)
![LeafletJS](https://img.shields.io/badge/LeafletJS-1.9.4-green)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4-purple)

Aplikasi web pemetaan digital untuk desa berbasis **CodeIgniter 3** dan **LeafletJS**. Aplikasi ini dirancang untuk memudahkan visualisasi data geografis desa dan menyediakan platform untuk mengelola informasi lokasi penting.

## Pratinjau

![Pratinjau Peta Desa](https://via.placeholder.com/800x400.png?text=Pratinjau+Aplikasi+Peta+Desa)
*Gambar: Tampilan utama aplikasi peta desa.*

## Fitur Utama

-   🗺️ **Peta Interaktif**: Peta dinamis dengan LeafletJS dan OpenStreetMap.
-   📍 **Manajemen Lokasi (CRUD)**: Kelola data lokasi dengan mudah melalui panel admin.
-   🗂️ **Kategori & Filter**: Kelompokkan lokasi berdasarkan kategori dan filter untuk pencarian cepat.
-   📸 **Galeri Foto**: Unggah dan tampilkan foto untuk setiap lokasi.
-   👤 **Manajemen Pengguna**: Sistem otentikasi untuk admin dan pengguna.
-   🏘️ **Dukungan Multi-Desa**: Dapat dikonfigurasi untuk menampilkan data dari beberapa desa.
-   🚀 **Deployment Mudah**: Dilengkapi dengan skrip untuk pembaruan via `git pull`.

## Teknologi

-   **Framework**: CodeIgniter 3
-   **Pustaka Peta**: LeafletJS
-   **Antarmuka**: Bootstrap 4, jQuery
-   **Bahasa**: PHP, JavaScript
-   **Database**: MySQL/MariaDB

## Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/cedirusyaid/peta_desa.git
    cd peta_desa
    ```

2.  **Database**
    -   Buat database baru di MySQL/MariaDB.
    -   Impor file `peta_desa_db.zip` (atau file SQL yang telah diekstrak) ke database Anda.

3.  **Konfigurasi**
    -   Salin `application/config/config.example.php` menjadi `application/config/config.php`.
    -   Salin `application/config/database.example.php` menjadi `application/config/database.php`.
    -   Sesuaikan **base URL** di `application/config/config.php`.
    -   Sesuaikan **kredensial database** di `application/config/database.php`.

4.  **Jalankan Aplikasi**
    -   Arahkan browser Anda ke base URL yang telah dikonfigurasi.

## Struktur Proyek

```
.
├── 📂 application/    # Inti aplikasi (MVC)
├── 📂 assets/         # Aset frontend (CSS, JS, gambar)
├── 📂 system/         # File inti CodeIgniter
├── 📂 uploads/        # Direktori unggahan
└── 📜 README.md
```

## Kontribusi

Kami sangat terbuka untuk kontribusi! Jika Anda ingin membantu, silakan fork repositori ini dan buat pull request. Untuk bug atau saran fitur, silakan buka issue baru.