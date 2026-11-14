<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }
        return redirect()->route('siswa.dashboard');
    })->name('dashboard');

    Route::middleware(['permission:akses dashboard admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard', ['roleName' => 'Admin']);
        })->name('dashboard');

        Route::get('/users', [PenggunaController::class, 'index'])->name('users');
        Route::patch('/users/{id}/role', [PenggunaController::class, 'updateRole'])->name('users.updateRole');
        Route::delete('/users/{id}', [PenggunaController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware(['permission:akses dashboard petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard', ['roleName' => 'Petugas']);
        })->name('dashboard');
    });

    Route::get('/siswa/dashboard', function () {
        return view('dashboard');
    })->middleware(['role:siswa'])->name('siswa.dashboard');

    Route::middleware(['permission:lihat daftar buku'])->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'detail'])->name('buku.detail');
    });

    Route::middleware(['permission:kelola peminjaman'])->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.index');
    });

    Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])->name('riwayat.index');
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/riwayat-saya', [PeminjamanController::class, 'riwayat'])->name('siswa.riwayat');
    });

    Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
    Route::patch('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatus'])->name('peminjaman.updateStatus');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';