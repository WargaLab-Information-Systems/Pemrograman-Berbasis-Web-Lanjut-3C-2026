<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

// Redirect Halaman Utama ke Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// SEMUA ROUTE UTAMA (Membutuhkan Login)
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard');
    })->name('dashboard.admin');

    // 2. Koleksi Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    // 3. Kelola & Pengajuan Peminjaman (Admin, Petugas, & Siswa)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.kembali');
    
    // RUTE RIWAYAT SAYA (Yang dipanggil oleh Navbar Blade)
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])->name('peminjaman.riwayat');

    // RUTE RIWAYAT BERDASARKAN ID (Untuk pengujian 403)
    Route::get('/riwayat-peminjaman/{id}', [PeminjamanController::class, 'riwayatBerdasarkanId'])->name('peminjaman.riwayat.id');

    // 4. Laporan & Persetujuan (Khusus Admin & Petugas)
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.index');
        Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        
        // CRUD Buku
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    // 5. Kelola Data Pengguna (Khusus Admin)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 6. Profile Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // Penutup Route::middleware(['auth'])

require __DIR__.'/auth.php';