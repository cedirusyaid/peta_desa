# Peta Desa

Aplikasi web pemetaan digital untuk desa berbasis CodeIgniter 3 dan LeafletJS.

Aplikasi ini memungkinkan pengguna untuk melihat peta desa, lengkap dengan lokasi-lokasi penting seperti kantor desa, sekolah, tempat ibadah, dan fasilitas umum lainnya. Pengguna dapat memfilter lokasi berdasarkan kategori dan melihat detail setiap lokasi, termasuk foto.

## Fitur

*   **Peta Interaktif**: Menampilkan peta desa menggunakan LeafletJS dengan OpenStreetMap sebagai basemap.
*   **Manajemen Lokasi**: Administrator dapat dengan mudah menambah, mengubah, dan menghapus data lokasi melalui antarmuka admin.
*   **Kategori Lokasi**: Lokasi dikelompokkan berdasarkan kategori untuk memudahkan pemfilteran dan pencarian.
*   **Detail Lokasi**: Setiap lokasi dapat memiliki halaman detail dengan informasi lebih lanjut dan galeri foto.
*   **Manajemen Pengguna**: Sistem otentikasi dan manajemen pengguna untuk administrator dan pengguna terdaftar.
*   **Dukungan Multi-Desa**: Aplikasi ini dapat digunakan untuk mengelola peta beberapa desa.
*   **Deployment Mudah**: Termasuk skrip untuk melakukan `git pull` untuk pembaruan yang mudah.

## Teknologi yang Digunakan

*   **Back-end**: PHP, CodeIgniter 3
*   **Front-end**: JavaScript, jQuery, Bootstrap, LeafletJS
*   **Database**: MySQL/MariaDB

## Instalasi

1.  **Clone repository:**
    ```bash
    git clone https://github.com/nama_pengguna/nama_repo.git
    ```
2.  **Impor database:**
    *   Buat database baru di MySQL/MariaDB Anda.
    *   Impor file `peta_desa_db.zip` (atau file SQL yang diekstrak) ke dalam database yang baru Anda buat.
3.  **Konfigurasi database:**
    *   Buka file `application/config/database.php`.
    *   Sesuaikan pengaturan `hostname`, `username`, `password`, dan `database` dengan konfigurasi server database Anda.
4.  **Konfigurasi base URL:**
    *   Buka file `application/config/config.php`.
    *   Sesuaikan `base_url` dengan URL proyek Anda.
5.  **Jalankan aplikasi:**
    *   Arahkan browser Anda ke `base_url` yang telah Anda konfigurasikan.

## Struktur Proyek

```
peta_desa/
├── application/
│   ├── controllers/  # Logika bisnis aplikasi
│   ├── models/       # Interaksi database
│   ├── views/        # Tampilan antarmuka pengguna
│   └── config/       # File konfigurasi
├── assets/           # File CSS, JavaScript, dan gambar
├── system/           # File inti CodeIgniter
└── uploads/          # Direktori untuk unggahan file (misalnya, foto lokasi)
```

## Kontribusi

Kontribusi dalam bentuk apa pun sangat kami harapkan. Silakan buat *pull request* atau buka *issue* jika Anda menemukan masalah atau memiliki saran.
