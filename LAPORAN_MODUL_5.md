# Modul 5 — Autentikasi & RBAC (Sistem Perpustakaan Digital)

## 1. Cara menjalankan

```bash
composer update laravel/breeze spatie/laravel-permission   # memasang paket baru (composer.json sudah diperbarui)
php artisan migrate:fresh --seed                            # tabel baru + role/permission + akun demo
php artisan serve
```

> Tampilan memakai `public/css/app.css` milik proyek (tanpa Tailwind/Vite), jadi **tidak perlu** `npm install`.
> Bila memakai `migrate` biasa (bukan `fresh`) pada database lama, jalankan juga `php artisan db:seed --class=RolePermissionSeeder` dan `--class=UserSeeder`.

Akun demo (password semua: `password`):

| Role    | Email                       |
|---------|-----------------------------|
| Admin   | admin@perpustakaan.test     |
| Petugas | petugas@perpustakaan.test   |
| Siswa   | siswa1@perpustakaan.test    |
| Siswa   | siswa2@perpustakaan.test    |

Registrasi publik (`/register`) **selalu** menghasilkan role **siswa**. Admin/Petugas hanya dibuat lewat seeder atau menu *Kelola Pengguna* oleh Admin.

## 2. Daftar perubahan

### File yang DIUBAH
| File | Perubahan |
|------|-----------|
| `composer.json` | + `spatie/laravel-permission`, + `laravel/breeze` (dev) |
| `bootstrap/app.php` | alias middleware `role`, `permission`, `role_or_permission` |
| `routes/web.php` | seluruh route dilindungi `auth` + `permission:*`; route baru (dashboard, pengguna, peminjaman, riwayat, laporan, profil) |
| `routes/api.php` | API buku/rekap dilindungi `web` + `auth` + `permission` (sebelumnya terbuka untuk siapa saja) |
| `app/Models/User.php` | `HasRoles`, konstanta role, relasi `anggota()`, `ensureAnggota()`; `role` sengaja tidak di `$fillable` |
| `app/Models/Anggota.php` | + `user_id`, relasi `user()` & `peminjamans()` |
| `app/Models/Peminjaman.php` | + `status`, `diproses_oleh`, relasi `anggota/buku/pemroses`, cast tanggal, `dimilikiOleh()` |
| `app/Providers/AppServiceProvider.php` | view pagination sederhana (sesuai CSS proyek) |
| `database/factories/PeminjamanFactory.php` | mengisi `status` |
| `database/seeders/DatabaseSeeder.php` | memanggil `RolePermissionSeeder` & `UserSeeder` |
| `database/seeders/PeminjamanSeeder.php` | + riwayat contoh untuk siswa demo |
| `resources/views/layouts/app.blade.php` | menu dinamis (`@auth`, `@can`), nama+role, tombol logout, pesan sukses/error, meta CSRF |
| `resources/views/buku/index.blade.php` | form/tombol Ubah-Hapus hanya untuk yang berhak (`@can`); tombol Detail & Ajukan Pinjam; header CSRF pada fetch |
| `resources/views/buku/show.blade.php` | tombol *Ajukan Peminjaman* (`@can`) |
| `resources/views/home.blade.php` | tombol Login/Registrasi untuk tamu |
| `public/css/app.css` | style tambahan (navbar, form, tabel, badge, alert, kartu statistik) |
| `tests/TestCase.php` | helper `buatUser($role)` |

### File yang DITAMBAHKAN
**Konfigurasi & database**
- `config/permission.php` (publish Spatie)
- `database/migrations/2026_11_02_085707_create_permission_tables.php` (tabel Spatie)
- `database/migrations/2026_11_02_090000_add_user_id_to_anggotas_table.php`
- `database/migrations/2026_11_02_090100_add_status_to_peminjamans_table.php`
- `database/seeders/RolePermissionSeeder.php` (3 role, 16 permission)
- `database/seeders/UserSeeder.php`

**Controller / Request / Policy**
- `app/Http/Controllers/Auth/*` (Breeze: login, register, logout, reset password, verifikasi email, konfirmasi password)
- `app/Http/Requests/Auth/LoginRequest.php` (Breeze)
- `app/Http/Controllers/ProfileController.php`, `app/Http/Requests/ProfileUpdateRequest.php` (Breeze, disesuaikan; hapus-akun dihilangkan)
- `app/Http/Controllers/DashboardController.php`, `UserController.php`, `PeminjamanController.php`, `RiwayatController.php`, `LaporanController.php`
- `app/Policies/PeminjamanPolicy.php`
- `routes/auth.php` (Breeze)

**View**
- `resources/views/auth/` — login, register, forgot-password, reset-password, confirm-password, verify-email
- `resources/views/dashboard.blade.php` (siswa), `admin/dashboard.blade.php`, `admin/petugas-dashboard.blade.php`
- `resources/views/users/` — index, create, edit
- `resources/views/peminjaman/` — index, pengembalian, show, `_aksi`
- `resources/views/riwayat/index.blade.php`, `laporan/index.blade.php`
- `resources/views/profile/` — edit + partials (profil, password)
- `resources/views/errors/403.blade.php`, `pagination/simple.blade.php`, `components/status-badge.blade.php`

**Test**
- `tests/Feature/RbacTest.php` (skenario Modul 5), `tests/Feature/ProfileTest.php`, `tests/Feature/Auth/*` (Breeze, disesuaikan)

### Yang tidak berubah
Migrasi/tabel awal, model `Buku` & `Kategori`, `Api/BukuController`, `Api/RekapController` (logika), `BukuResource`, request buku, seeder kategori/buku/anggota, `.env`, `database.sqlite`.

## 3. Matriks role & permission (RolePermissionSeeder)

| # | Permission | Admin | Petugas | Siswa |
|---|------------|:-:|:-:|:-:|
| 1 | akses dashboard admin | ✓ | — | — |
| 2 | akses dashboard petugas | — | ✓ | — |
| 3 | lihat daftar buku | ✓ | ✓ | ✓ |
| 4 | kelola data buku | ✓ | ✓ | — |
| 5 | kelola data pengguna | ✓ | — | — |
| 6 | kelola role pengguna | ✓ | — | — |
| 7 | kelola peminjaman | ✓ | ✓ | — |
| 8 | kelola pengembalian | ✓ | ✓ | — |
| 9 | mengajukan peminjaman buku | ✓ | ✓ | ✓ |
| 10 | memproses persetujuan peminjaman | ✓ | ✓ | — |
| 11 | mencatat pengembalian buku | ✓ | ✓ | — |
| 12 | melihat status peminjaman sendiri | ✓ | ✓ | ✓ |
| 13 | melihat riwayat peminjaman sendiri | ✓ | ✓ | ✓ |
| 14 | lihat laporan perpustakaan | ✓ | ✓ | — |
| 15 | lihat profil sendiri | ✓ | ✓ | ✓ |
| 16 | ubah profil sendiri | ✓ | ✓ | ✓ |

## 4. Keputusan desain (penting dipahami)

- **Breeze:** controller, request, route, dan test dari Breeze dipakai, tetapi *view* ditulis ulang agar memakai layout & CSS proyek (stack Blade bawaan Breeze butuh Tailwind + `npm`).
- **Akun ↔ anggota:** `anggotas.user_id` menghubungkan akun login dengan data anggota, sehingga `peminjamans.anggota_id` bisa ditelusuri ke pemiliknya. Dibuat otomatis saat registrasi / dibuat admin.
- **Alur peminjaman:** `diajukan → dipinjam → dikembalikan` (atau `ditolak`), kolom `status` + `diproses_oleh` pada `peminjamans`. Satu buku tidak bisa disetujui bila sedang dipinjam orang lain; petugas tidak bisa memproses peminjamannya sendiri.
- **Data pribadi:** profil tidak punya ID di URL (selalu `auth()->user()`); `/riwayat/{user}` dan `/peminjaman/{id}` memeriksa **identitas** (pemilik) selain permission → siswa yang mengganti ID mendapat 403.
- **API buku:** sebelumnya terbuka tanpa login. Kini `GET` butuh `lihat daftar buku`, tulis (POST/PUT/DELETE) butuh `kelola data buku`. Halaman `/buku` mengirim token CSRF pada fetch.
- **Proteksi admin:** admin tidak bisa mengubah role / menghapus dirinya sendiri atau admin terakhir.
- **Daftar buku kini wajib login** (permission `lihat daftar buku` dimiliki ketiga role, tidak untuk tamu). Beranda tetap publik.

## 5. Pengujian skenario (Hasil Aktual)

Diuji dengan `php artisan test` (58 test lolos) dan dengan menjalankan aplikasi di browser.

| No | Role | Skenario | Hasil diharapkan | Hasil aktual |
|----|------|----------|------------------|--------------|
| 1 | Belum login | Membuka halaman dashboard admin | Dialihkan ke halaman login | Dialihkan (302) ke `/login`; berlaku juga untuk `/buku`, `/profile`, `/riwayat`, `/laporan`, dll. |
| 2 | Admin | Membuka dashboard admin | Dashboard admin dapat diakses | `/dashboard` → `/admin/dashboard`, halaman tampil (200) |
| 3 | Admin | Menambah, mengubah, menghapus buku | Operasi berhasil | Tambah (201), ubah (200), hapus (200); data di DB sesuai |
| 4 | Admin | Mengelola akun dan role pengguna | Operasi berhasil | Tambah, ubah, ganti role, hapus pengguna berhasil; role berubah di DB |
| 5 | Admin | Mengakses peminjaman, pengembalian, laporan | Data dapat diakses | `/peminjaman`, `/pengembalian`, `/laporan` → 200 |
| 6 | Petugas | Membuka dashboard petugas | Dashboard dapat diakses | `/petugas/dashboard` → 200; `/admin/dashboard` → 403 |
| 7 | Petugas | Mengelola data buku | Operasi berhasil | Tambah (201), ubah (200), hapus (200) |
| 8 | Petugas | Memproses peminjaman dan pengembalian | Operasi berhasil | Setujui → `dipinjam`; catat pengembalian → `dikembalikan` (+ tanggal kembali); tolak → `ditolak` |
| 9 | Petugas | Membuka pengelolaan akun/role | Akses ditolak | Semua URL `/admin/users*` dan ubah role → 403 |
| 10 | Siswa | Melihat daftar & detail buku | Data buku dapat dilihat | `/buku`, `/buku/{id}`, `/api/buku` → 200 |
| 11 | Siswa | Melihat & mengubah profil sendiri | Profil dapat diakses & diperbarui | `/profile` 200, ubah nama/email/telepon berhasil; data user lain tidak berubah; role tidak bisa dinaikkan |
| 12 | Siswa | Melihat riwayat peminjaman sendiri | Riwayat milik sendiri dapat dilihat | `/riwayat`, `/status-peminjaman` → 200, hanya memuat data miliknya |
| 13 | Siswa | Membuka dashboard admin / pengelolaan buku | Akses ditolak | `/admin/dashboard`, `/admin/users`, `/laporan` → 403; POST/PUT/DELETE `/api/buku` → 403, data buku tidak berubah; form kelola & tombol Ubah/Hapus tidak tampil |
| 14 | Siswa | Melihat riwayat siswa lain | Akses ditolak | `/riwayat/{id lain}` dan `/peminjaman/{id milik lain}` → 403 |
| 15 | Semua role | Logout lalu buka halaman terlindungi | Harus login kembali | Setelah logout, halaman terlindungi dialihkan ke `/login` (diuji untuk admin, petugas, siswa) |

Tambahan yang juga diuji: login salah ditolak, password tersimpan ter-hash, registrasi tidak bisa memilih role (`role=admin` diabaikan), pengguna yang sudah login dialihkan dari `/login` & `/register` (middleware `guest`), akses API tanpa login → 401.
