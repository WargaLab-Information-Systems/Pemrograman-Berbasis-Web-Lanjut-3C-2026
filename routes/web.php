<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware(PermissionMiddleware::using('lihat profil sendiri'))
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware(PermissionMiddleware::using('ubah profil sendiri'))
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->middleware(PermissionMiddleware::using('ubah profil sendiri'))
        ->name('profile.destroy');
});


// RUTE BUKU (Implementasi Soal 3 - Gate kelola-buku)

// Rute Manajemen Buku (Tambah, Ubah, Hapus) dilindungi Gate kelola-buku
Route::middleware(['auth', 'can:kelola-buku'])->group(function () {
    Route::resource('buku', BukuController::class)->except(['index', 'show']);
});

// Rute Lihat Buku / Katalog / Pencarian (Dapat diakses user/siswa)
Route::middleware(['auth'])->group(function () {
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
});


// RUTE LAINNYA


Route::middleware(['auth', PermissionMiddleware::using('kelola data pengguna')])->group(function () {
    Route::resource('users', UserController::class)->except('show');
});

Route::middleware(['auth', PermissionMiddleware::using('kelola role pengguna')])->group(function () {
    Route::get('/users/roles', [UserController::class, 'roles'])->name('users.roles.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.roles.update');
});

Route::middleware(['auth', PermissionMiddleware::using('kelola pengembalian')])->group(function () {
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::put('/pengembalian/{peminjaman}', [PengembalianController::class, 'update'])
        ->middleware(PermissionMiddleware::using('mencatat pengembalian buku'))
        ->name('pengembalian.update');
});

Route::middleware(['auth', PermissionMiddleware::using('lihat laporan perpustakaan')])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

Route::middleware(['auth', PermissionMiddleware::using('lihat daftar buku')])->group(function () {
    Route::get('/katalog-buku', [BukuController::class, 'katalog'])->name('buku.katalog');
});

Route::middleware(['auth', PermissionMiddleware::using('mengajukan peminjaman buku')])->group(function () {
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])
        ->whereNumber('id')
        ->name('peminjaman.show');

    Route::get('/peminjaman/status', [PeminjamanController::class, 'status'])
        ->middleware(PermissionMiddleware::using('melihat status peminjaman sendiri'))
        ->name('peminjaman.status');
    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'index'])
        ->middleware(PermissionMiddleware::using('melihat riwayat peminjaman sendiri'))
        ->name('peminjaman.index');
    Route::get('/peminjaman-saya', [PeminjamanController::class, 'index'])
        ->middleware(PermissionMiddleware::using('melihat riwayat peminjaman sendiri'))
        ->name('peminjaman.saya');

    Route::put('/kelola-peminjaman/{id}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware(PermissionMiddleware::using('kelola peminjaman'))
        ->middleware(PermissionMiddleware::using('memproses persetujuan peminjaman'))
        ->name('peminjaman.setujui');
    Route::put('/kelola-peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware(PermissionMiddleware::using('kelola peminjaman'))
        ->middleware(PermissionMiddleware::using('memproses persetujuan peminjaman'))
        ->name('peminjaman.tolak');
    Route::get('/kelola-peminjaman', [PeminjamanController::class, 'kelola'])
        ->middleware(PermissionMiddleware::using('kelola peminjaman'))
        ->name('peminjaman.kelola');
});

Route::get('/uji-error', function () {     throw new RuntimeException('Pengujian halaman error Laravel'); }); 

require __DIR__.'/auth.php';
