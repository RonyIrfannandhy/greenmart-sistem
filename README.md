# 🛒 GreenMart - Dynamic Product Input Web App

GreenMart adalah aplikasi web yang memungkinkan pengguna untuk menambahkan produk dinamis secara fleksibel, termasuk nama produk, deskripsi kategori, serta unggahan gambar.

## ✨ Fitur Utama

-   Tambah hingga 5 produk
-   Setiap produk dapat memiliki hingga 3 kategori
-   Upload gambar untuk setiap kategori (hanya JPG, JPEG, PNG)
-   Ikon aksi: tambah ➕, hapus ❌, unggah ⬆️, dan hapus gambar 🗑️

## ✨ Fitur Tambahan

-   Gambar yang diunggah dapat diklik untuk dilihat secara penuh
-   Tabel dinamis dengan penomoran otomatis rekap data yang terintegrasi
-   Serta sistem informasi POP UP validasi
-   Fitur tambahan lainnya seperti reset dan simpan data

## 🚀 Cara Menjalankan

1. Clone repo ini atau unduh sebagai ZIP.
2. composer install
3. npm install
4. cp .env.example .env
5. DB_DATABASE= | DB_USERNAME=root | DB_PASSWORD=
6. php artisan key:generate
7. php artisan migrate
8. php artisan db:seed
9. php artisan serve
10. starter : http://127.0.0.1:8000/produk/buat-dinamis

## 🧪 Validasi Gambar

-   Format gambar diperbolehkan: `.jpg`, `.jpeg`, `.png`
-   Ukuran file maksimum dapat ditentukan di dalam logika JavaScript saat ini 2MB (Max)

## 📸 Demo

![Tampilan1](./assets/gambar-1.png)
![Tampilan2](./assets/gambar-2.png)
![Tampilan3](./assets/gambar-3.png)

## 📃 Owner

DB : admin_greenmart

by Rony Irfannandhy
