## MoonShine - Simple for beginners and powerful for experts:
### Administration panel for Laravel framework
#### Starter kit

#### [MoonShine repository](https://github.com/moonshine-software/moonshine)
#### [Documentation](https://getmoonshine.app)

#### Hello Laravel user!

You are on an open source project dedicated to the "MoonShine" admin panel.

I've been working on it for several years, and have used it on dozens of small projects, constantly tweaking it.

What does moonshine mean? It's not exactly "moonlight" - my idea is the name "moonshine".
The term means the independent production of a drink in illegal conditions under the cover of night :).
So I developed this admin panel at night in my free time under the moonlight;)

Everything is already ready for use in your projects, documentation has been created describing the installation, configuration and features.

I invite interested users to use and develop Moonshine together.

<p align="center">
<img src="https://getmoonshine.app/images/main.png?v=3.0" alt="Main">
</p>

<p align="center">
<img src="https://getmoonshine.app/images/login.png?v=3.0" alt="Login">
</p>


# Panduan Instalasi

Dokumen ini menjelaskan cara menginstal dan menjalankan proyek Manajemen Barang RSUD pada lingkungan pengembangan lokal.

## Prasyarat

Pastikan sistem Anda telah terinstal perangkat lunak berikut:
- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- Database (misalnya MySQL, MariaDB, atau PostgreSQL)

## Langkah-langkah Instalasi

1.  **Clone Repositori**
    Clone repositori ini ke mesin lokal Anda menggunakan Git.
    ```bash
    git clone https://github.com/ahmadsyaifuddin-dins/manajemen-barang-rsud.git
    ```

2.  **Masuk ke Direktori Proyek**
    ```bash
    cd manajemen-barang-rsud
    ```

3.  **Install Dependensi PHP**
    Install semua paket PHP yang dibutuhkan menggunakan Composer.
    ```bash
    composer install
    ```

4.  **Install Dependensi JavaScript**
    Install semua paket JavaScript yang dibutuhkan menggunakan NPM.
    ```bash
    npm install
    ```

5.  **Konfigurasi Lingkungan**
    Salin file `.env.example` menjadi `.env`. File ini akan digunakan untuk menyimpan konfigurasi lingkungan proyek Anda.
    ```bash
    copy .env.example .env
    ```
    Kemudian, generate kunci aplikasi Laravel.
    ```bash
    php artisan key:generate
    ```

6.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan konfigurasi database sesuai dengan pengaturan lokal Anda.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=manajemen_barang_rsud
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7.  **Jalankan Migrasi Database**
    Buat semua tabel yang diperlukan dalam database Anda dengan menjalankan migrasi. Opsi `--seed` akan mengisi database dengan data awal (jika ada seeder yang telah didefinisikan).
    ```bash
    php artisan migrate --seed
    ```

8.  **Buat User Admin**
    Proyek ini menggunakan MoonShine sebagai panel admin. Buat user admin pertama Anda dengan menjalankan perintah berikut. Anda akan diminta untuk memasukkan nama, email, dan password. 
    ```bash
    php artisan moonshine:user
    ```
    buat aja nanti
    email nya `admin@gmail.com`, nama `Admin`, passwordnya `password`

9.  **Jalankan Server Pengembangan**
    Untuk menjalankan server pengembangan PHP, gunakan perintah berikut:
    ```bash
    php artisan serve
    ```
    Server akan berjalan di `http://127.0.0.1:8000`.

10. **Jalankan Vite**
    Buka terminal baru dan jalankan Vite untuk kompilasi aset frontend.
    ```bash
    npm run dev
    ```

## Mengakses Aplikasi

-   **Halaman Utama**: Aplikasi dapat diakses melalui `http://127.0.0.1:8000`.
-   **Panel Admin**: Panel admin MoonShine dapat diakses melalui `http://127.0.0.1:8000/admin`. Gunakan kredensial yang Anda buat pada langkah 8 untuk login.

