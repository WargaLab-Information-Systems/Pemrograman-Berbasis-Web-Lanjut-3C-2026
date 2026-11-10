<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Beranda: tamu melihat halaman beranda (tautan daftar buku, login, registrasi),
// pengguna yang sudah login diarahkan ke dashboard.
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('home');
})->name('home');

// Daftar & detail buku: PUBLIK, tamu (belum login) boleh melihat.
// Tamu tidak dapat meminjam; tombol pinjam hanya muncul untuk yang berhak,
// dan route peminjaman tetap dilindungi "auth" + permission.
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

// Halaman yang membutuhkan login (middleware "auth").
// Hak akses tiap route diperiksa lagi dengan middleware "permission" (Spatie).
Route::middleware('auth')->group(function () {

    // Dashboard: diarahkan sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses dashboard admin')->name('admin.dashboard');
    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses dashboard petugas')->name('petugas.dashboard');

    // Pengelolaan data buku (tambah/ubah/hapus) lewat /api/buku
    // dan dilindungi permission "kelola data buku".

    // [A2 - RENTAN] Pencarian buku dengan query SQL mentah
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');

    // [A3 - RENTAN] Hapus buku lewat GET (tanpa token CSRF)
    Route::get('/buku/{buku}/hapus', [\App\Http\Controllers\Api\BukuController::class, 'destroy'])
        ->name('buku.hapus');

    // Profil sendiri
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')->name('profile.update');

    // Kelola pengguna (admin saja)
    Route::prefix('admin/users')->name('users.')->group(function () {
        Route::middleware('permission:kelola data pengguna')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::patch('/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('permission:kelola role pengguna')->name('role');
    });

    // Peminjaman & pengembalian
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku')->name('peminjaman.store');
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')->name('peminjaman.index');
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->middleware('permission:kelola pengembalian')->name('pengembalian.index');
    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')->name('peminjaman.setujui');
    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')->name('peminjaman.tolak');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])
        ->middleware('permission:mencatat pengembalian buku')->name('peminjaman.kembali');
    // [A4 - RENTAN] Detail peminjaman tanpa pemeriksaan pemilik (IDOR)
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // Status & riwayat peminjaman sendiri
    Route::get('/status-peminjaman', [RiwayatController::class, 'status'])
        ->middleware('permission:melihat status peminjaman sendiri')->name('peminjaman.status');
    Route::get('/riwayat', [RiwayatController::class, 'saya'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')->name('riwayat.saya');
    Route::get('/riwayat/{user}', [RiwayatController::class, 'user'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')->name('riwayat.user');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')->name('laporan.index');
});

require __DIR__.'/auth.php';