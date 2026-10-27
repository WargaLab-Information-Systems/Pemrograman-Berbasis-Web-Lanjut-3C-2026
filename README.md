<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# 🚀 Repositori Praktikum Pemrograman Berbasis Web Lanjut - 3C - 2026

Selamat datang di repositori resmi praktikum **Pemrograman Berbasis Web Lanjut (PBWL) Kelas 3C**. Repositori ini digunakan oleh seluruh mahasiswa untuk mengumpulkan penugasan praktikum berbasis framework **Laravel 13** melalui sistem **Fork & Pull Request (PR)**.

---

## 🛠️ Prasyarat Sistem (Laravel 13 Requirements)

Sebelum menjalankan proyek, pastikan perangkat lokal Anda telah memenuhi prasyarat **Laravel 13**:
- **PHP**: `>= 8.3` (Disarankan PHP 8.3 atau 8.4)
- **Composer**: `>= 2.2.0`
- **Node.js & NPM**: `>= 18.x` / `>= 20.x`
- **Database**: MySQL / MariaDB / SQLite

---

## 📌 Aturan Praktikum & Larangan

1. **Satu Proyek Per Mahasiswa**: Setiap folder mahasiswa (berformat `{NIM}-{NAMA LENGKAP}`) merupakan **satu proyek Laravel**. Tidak perlu membuat subfolder per modul di dalamnya.
2. **Format Commit Message (WAJIB)**:
   Pengerjaan setiap modul ditandai melalui format pesan commit sebagai berikut:
   ```text
   NIM_NamaLengkap_MODUL<X>_NamaAsprak
   ```
   **Contoh Commit:**
   `240441100057_OktaviaPutriRoichatulJannah_MODUL1_KakSalman`

3. **File yang Dilarang Diumpan ke Git (Git Ignore)**:
   - ❌ **DILARANG** meng-push folder `vendor/`
   - ❌ **DILARANG** meng-push folder `node_modules/`
   - ❌ **DILARANG** meng-push file `.env`
   - ✅ Root `.gitignore` dan `.gitignore` lokal akan otomatis mencegah file ini ter-push.

4. **Integritas Repositori**:
   - Mahasiswa **HANYA BOLEH** mengedit dan meng-push file di dalam folder milik sendiri.
   - **Dilarang** mengedit folder mahasiswa lain atau file root (`README.md`, `.github/`, dll). PR yang melanggar akan otomatis ditolak oleh sistem CI.

---

## 📂 Struktur Direktori Repositori

```text
Pemrograman-Berbasis-Web-Lanjut-3C-2026/
├── 250441100001-SULTAN/                <-- Proyek Laravel Utama Mahasiswa
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── tests/
│   ├── .gitignore
│   ├── composer.json
│   └── ...
├── 250441100006-MOHAMMAD IRFAN HARIYONO/
└── ... (folder mahasiswa lainnya)
```

---

## 💻 Panduan Git Workflow (Fork & Pull Request)

Seluruh pengumpulan tugas praktikum dilakukan menggunakan skema **Fork & Pull Request**:

### 1. Fork Repositori
- Buka repositori utama ini di GitHub.
- Klik tombol **Fork** di pojok kanan atas untuk membuat salinan repositori di akun GitHub Anda masing-masing.

### 2. Clone Repositori Hasil Fork
Clone repositori dari akun GitHub Anda sendiri:
```bash
git clone https://github.com/[USERNAME_GITHUB_ANDA]/Pemrograman-Berbasis-Web-Lanjut-3C-2026.git
cd Pemrograman-Berbasis-Web-Lanjut-3C-2026
```

### 3. Masuk ke Folder Anda & Kerjakan Tugas
```bash
cd 2504411000XX-NAMA MAHASISWA
```
Kerjakan tugas modul terkait di dalam folder proyek Laravel Anda.

### 4. Commit dengan Format Resmi & Push ke Fork
Setelah selesai mengerjakan modul:
```bash
# 1. Tambahkan perubahan di folder Anda
git add .

# 2. Commit dengan format WAJIB (NIM_NamaLengkap_MODUL<X>_NamaAsprak)
git commit -m "240441100057_OktaviaPutriRoichatulJannah_MODUL1_KakSalman"

# 3. Push ke repositori Fork milik Anda
git push origin main
```

### 5. Buat Pull Request (PR) ke Repositori Utama
1. Buka repositori hasil Fork Anda di GitHub.
2. Klik tombol **Contribute** -> **Open Pull Request**.
3. Pastikan **Base Repository** mengarah ke `main` di repositori utama praktikum.
4. Berikan Judul PR yang jelas (contoh: `NIM_NamaLengkap_MODUL<X>_NamaAsprak`).
5. Klik **Create Pull Request**.

> 🤖 **Sistem GitHub Actions (CI) akan otomatis mengecek PR Anda:**
> - ✅ Memeriksa apakah Anda hanya mengubah folder milik Anda sendiri.
> - ✅ Memeriksa apakah format pesan commit Anda sudah benar.
> - ✅ Memeriksa apakah ada sintaks PHP yang error.

---

## ⚡ Cara Menjalankan Proyek Laravel 13 (Untuk Asprak)

Jika Asisten Praktikum ingin mengecek atau menguji proyek milik mahasiswa:

1. Masuk ke direktori mahasiswa yang dituju:
   ```bash
   cd 2504411000XX-NAMA MAHASISWA
   ```
2. Install dependensi PHP (Composer) & Frontend (NPM):
   ```bash
   composer install
   npm install && npm run build
   ```
3. Salin file `.env` & Generate Application Key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Jalankan Migrasi Database & Seeder:
   ```bash
   php artisan migrate --seed
   ```
5. Menjalankan Server Development:
   ```bash
   php artisan serve
   ```

---

## 🙋‍♂️ Daftar Asisten Praktikum

Gunakan nama panggilan Asprak di bawah ini saat melakukan commit:

| No | Nama Lengkap Asprak | Nama Panggilan Commit (`NamaAsprak`) |
|:--:|:--------------------|:------------------------------------|
| 1  | Oktavia Putri Roichatul Jannah          | `KakOkta`                         |
| 2  | Destya Nurfaiza Muslim          | `KakDestya`                     |
| 3  | Edi Putra          | `KakEdi`                     |

---

*Jika mengalami kendala teknis atau konflik Git saat melakukan PR, segera hubungi Asisten Praktikum.*
>>>>>>> dc3473b24dacbb16cdad8615f3c4d8694c001f0d
