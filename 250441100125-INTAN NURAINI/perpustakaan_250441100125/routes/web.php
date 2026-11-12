<?php

use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SiswaPeminjamanController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard umum
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:Admin'])
    ->name('admin.dashboard');

// Dashboard Petugas
Route::get('/petugas/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:Petugas'])
    ->name('petugas.dashboard');

// Dashboard Siswa
Route::get('/siswa/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:Siswa'])
    ->name('siswa.dashboard');

// Buku: Admin dan Petugas
Route::middleware(['auth', 'role:Admin|Petugas'])->group(function () {
    Route::get('/kelola-buku', [BukuController::class, 'index'])
        ->name('kelola-buku');
});

// Katalog Buku: Siswa
Route::get('/katalog-buku', [BukuController::class, 'katalog'])
    ->middleware(['auth', 'role:Siswa'])
    ->name('katalog-buku');

// Detail buku: semua pengguna yang sudah login
Route::get('/buku/{id}', [BukuController::class, 'detail'])
    ->middleware('auth')
    ->name('buku.detail');

// Peminjaman: Admin dan Petugas
Route::middleware(['auth', 'role:Admin|Petugas'])->group(function () {
    // Menampilkan data peminjaman
    Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])
        ->name('admin.peminjaman');

    // Konfirmasi pengajuan peminjaman
    Route::patch('/admin/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus'])
        ->name('admin.peminjaman.status');
});

// Peminjaman: khusus Siswa
Route::middleware(['auth', 'role:Siswa'])->group(function () {
    // Form pengajuan peminjaman
    Route::get('/siswa/peminjaman/ajukan/{buku_id}', [SiswaPeminjamanController::class, 'create'])
        ->name('siswa.peminjaman.create');

    // Menyimpan pengajuan peminjaman
    Route::post('/siswa/peminjaman/ajukan/{buku_id}', [SiswaPeminjamanController::class, 'store'])
        ->name('siswa.peminjaman.store');

    // Melihat status pengajuan
    Route::get('/siswa/peminjaman/status', [SiswaPeminjamanController::class, 'status'])
        ->name('siswa.peminjaman.status');

    // Melihat riwayat peminjaman
    Route::get('/siswa/peminjaman/riwayat', [SiswaPeminjamanController::class, 'riwayat'])
        ->name('siswa.peminjaman.riwayat');
});

// Kelola Anggota: khusus Admin
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('anggota', AnggotaController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});

// Pengembalian: Admin dan Petugas
Route::middleware(['auth', 'role:Admin|Petugas'])->group(function () {
    Route::get('/admin/pengembalian', [PengembalianController::class, 'index'])
        ->name('admin.pengembalian');

    Route::patch('/admin/pengembalian/{peminjaman}', [PengembalianController::class, 'kembalikan'])
        ->name('admin.pengembalian.kembalikan');
});

// Laporan Perpustakaan: Admin dan Petugas
Route::middleware(['auth', 'role:Admin|Petugas'])->group(function () {
    Route::get('/admin/laporan', [LaporanController::class, 'index'])
        ->name('admin.laporan');
});

// Profil pengguna yang sedang login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Autentikasi Laravel Breeze
require __DIR__.'/auth.php';


