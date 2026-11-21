<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Redirect Halaman Utama
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// SEMUA ROUTE TERPROTEKSI AUTENTIKASI (WAJIB LOGIN)
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard');
    })->middleware('can:akses dashboard admin')->name('dashboard.admin');

    Route::get('/dashboard-guru', function () {
        return view('dashboard');
    })->middleware('can:akses dashboard petugas')->name('dashboard.guru');

    // 2. Modul Koleksi Buku (Menggunakan role:admin|guru|petugas|siswa agar lolos proteksi)
    Route::middleware(['role:admin|guru|petugas|siswa'])->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    // 3. Modul Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 4. Modul Transaksi Peminjaman & Pengembalian
    Route::middleware(['role:admin|guru|petugas'])->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.kembali');
    });

    // Riwayat Peminjaman Mandiri
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])->name('peminjaman.riwayat');

    // 5. Modul Laporan Perpustakaan (Memperbaiki Error 404)
    Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan-perpustakaan', [PeminjamanController::class, 'laporan']);

    // 6. Modul Kelola Akun & Role (Khusus Admin & Staff)
    Route::middleware(['role:admin|guru|petugas'])->group(function () {
        Route::get('/admin/users', function () {
            $users = User::with('roles')->get();
            return view('admin.users', compact('users'));
        })->name('admin.users.index');

        Route::patch('/admin/users/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('role:admin')
            ->name('admin.users.update-role');

        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('admin.users.destroy');
    });

});

require __DIR__.'/auth.php';