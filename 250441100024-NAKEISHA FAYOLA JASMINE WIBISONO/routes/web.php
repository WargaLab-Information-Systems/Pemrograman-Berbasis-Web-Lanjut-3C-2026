<?php

use App\Http\Controllers\Api\BukuController as DataBukuController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaBukuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanSayaController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
*/
Route::view('/', 'home')->name('home');

/*
|--------------------------------------------------------------------------
| Halaman yang butuh login (middleware auth) + permission (Spatie)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ---- Dashboard ----
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses-dashboard-admin')->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses-dashboard-petugas')->name('petugas.dashboard');

    Route::get('/siswa/dashboard', [DashboardController::class, 'siswa'])
        ->middleware('permission:lihat-status-peminjaman-sendiri')->name('siswa.dashboard');

    // ---- Profil sendiri (Breeze ProfileController selalu memakai $request->user()) ----
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat-profil-sendiri')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah-profil-sendiri')->name('profile.update');

    // ---- Daftar dan detail buku (Modul 1-2, semua role) ----
    Route::middleware('permission:lihat-daftar-buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    // ---- Endpoint data buku untuk Fetch API (Modul 4) lewat session login ----
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('buku', [DataBukuController::class, 'index'])
            ->middleware('permission:lihat-daftar-buku')->name('buku.index');

        Route::middleware('permission:kelola-data-buku')->group(function () {
            Route::post('buku', [DataBukuController::class, 'store'])->name('buku.store');
            Route::put('buku/{buku}', [DataBukuController::class, 'update'])->name('buku.update');
            Route::delete('buku/{buku}', [DataBukuController::class, 'destroy'])->name('buku.destroy');
        });
    });

    // ---- Kelola buku: admin dan petugas ----
    Route::get('/kelola/buku', [KelolaBukuController::class, 'index'])
        ->middleware('permission:kelola-data-buku')->name('kelola.buku');

    // ---- Peminjaman dan pengembalian: admin dan petugas ----
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola-peminjaman')->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])
        ->middleware('permission:proses-persetujuan-peminjaman')->name('peminjaman.setujui');
    Route::post('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])
        ->middleware('permission:proses-persetujuan-peminjaman')->name('peminjaman.tolak');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])
        ->middleware('permission:kelola-pengembalian')->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])
        ->middleware('permission:catat-pengembalian-buku')->name('pengembalian.store');

    // ---- Laporan: admin dan petugas ----
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat-laporan-perpustakaan')->name('laporan.index');

    // ---- Peminjaman milik sendiri: semua role ----
    Route::post('/buku/{buku}/ajukan', [PeminjamanSayaController::class, 'ajukan'])
        ->middleware('permission:ajukan-peminjaman-buku')->name('peminjaman.ajukan');
    Route::get('/peminjaman-saya', [PeminjamanSayaController::class, 'status'])
        ->middleware('permission:lihat-status-peminjaman-sendiri')->name('peminjaman.saya');
    Route::get('/riwayat-peminjaman', [PeminjamanSayaController::class, 'riwayatSaya'])
        ->middleware('permission:lihat-riwayat-peminjaman-sendiri')->name('riwayat.saya');
    Route::get('/anggota/{anggota}/riwayat', [PeminjamanSayaController::class, 'riwayat'])
        ->middleware('permission:lihat-riwayat-peminjaman-sendiri')->name('riwayat.show');

    // ---- Kelola pengguna dan role: khusus admin ----
    Route::prefix('admin')->group(function () {
        Route::middleware('permission:kelola-data-pengguna')->group(function () {
            Route::resource('pengguna', UserController::class)
                ->parameters(['pengguna' => 'user'])
                ->except('show');
        });

        Route::put('pengguna/{user}/role', [UserController::class, 'updateRole'])
            ->middleware('permission:kelola-role-pengguna')->name('pengguna.role');
    });
});

// Login, registrasi, logout (middleware guest dan auth sudah diatur Breeze di file ini)
require __DIR__ . '/auth.php';
