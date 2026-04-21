# MyComList

MyComList adalah sebuah platform web berbasis Laravel yang memungkinkan pengguna untuk melacak, menilai, dan mengelola daftar komik atau manga yang mereka baca (terinspirasi dari platform seperti MyAnimeList).

## Fitur Utama

-   **Manajemen Profil Pengguna**: Pengguna dapat mengubah nama pengguna, nama lengkap, dan mengunggah foto profil (avatar).
-   **Pelacakan Komik**: Pengguna dapat memberikan status bacaan pada komik (contoh: _reading_, _completed_, _dropped_, _plan_to_read_), memberikan rating, dan menandai bab terakhir yang dibaca.
-   **Interaksi Sosial**: Fitur "Suka" (Like) pada komik.
-   **Data Dummy / Seeder**: Dilengkapi dengan custom command untuk mengacak perilaku (_behavior_) dari user dummy agar data terlihat lebih realistis saat pengembangan.

## Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan minimum berikut sebelum menginstal aplikasi ini:

-   PHP >= 8.1
-   Composer
-   Database MySQL / MariaDB (via XAMPP, Laragon, dll)
-   Node.js & NPM (untuk kompilasi aset frontend)

## Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek secara lokal:

1.  **Kloning Repositori** (atau ekstrak folder proyek Anda):
    ```bash
    git clone <url-repositori-anda> My-comlist
    cd My-comlist
    ```

2.  **Instalasi Dependensi PHP**:
    ```bash
    composer install
    ```

3.  **Instalasi Dependensi Node & Kompilasi Aset**:
    ```bash
    npm install
    npm run build
    ```
    *(Gunakan `npm run dev` jika sedang dalam tahap pengembangan aktif)*

4.  **Konfigurasi Environment**:
    Duplikat file `.env.example` dan ubah namanya menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan kredensial database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=MyComList
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```

6.  **Migrasi Database dan Seeding**:
    ```bash
    php artisan migrate --seed
    ```

7.  **Jalankan Server Lokal**:
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang dapat diakses melalui `http://localhost:8000`.

## Custom Artisan Commands

Aplikasi ini memiliki command kustom bawaan untuk membantu pengembangan, terutama dalam menguji interaksi user:

-   `php artisan fetch:manga {halaman_awal?} {halaman_akhir?}`
    Command ini berfungsi untuk mengambil data komik (manga, manhwa, manhua) dari Jikan API, sebuah API publik untuk MyAnimeList. Command ini akan menyimpan data seperti judul, sinopsis, genre, dan gambar sampul ke dalam database lokal.
    Contoh penggunaan:
    - `php artisan fetch:manga` (Mengambil 1 halaman)
    - `php artisan fetch:manga 1 5` (Mengambil dari halaman 1 sampai 5)

-   `php artisan user:randomize-behavior`
    Command ini akan mengacak ulang interaksi (seperti memberikan "like", status membaca, dan rating) untuk semua akun pengguna _dummy_ (pengguna dengan email `@example.*`) secara realistis tanpa menghapus akun mereka.

> **Catatan**: Pastikan Anda memiliki koneksi internet saat menjalankan `fetch:manga` karena command ini bergantung pada API eksternal.
