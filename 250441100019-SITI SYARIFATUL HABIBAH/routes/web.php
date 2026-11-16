<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuCariController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BukuCrudController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kelola\PeminjamanController as KelolaPeminjamanController;
use App\Http\Controllers\Kelola\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanSayaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
})->name('home');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  
    Route::get('/buku-cari', [BukuCariController::class, 'index'])->name('buku.cari.vuln');

    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.vuln');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('permission:akses dashboard admin')
        ->name('admin.dashboard');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->middleware('permission:akses dashboard petugas')
        ->name('petugas.dashboard');

    
    Route::get('/buku/create', fn () => redirect()->route('buku.index'))
        ->middleware('can:kelola-buku')
        ->name('buku.create');

    Route::middleware('permission:lihat daftar buku')->group(function () {
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    });

    Route::post('/buku/{buku}/pinjam', [PeminjamanSayaController::class, 'store'])
        ->middleware('permission:mengajukan peminjaman buku')
        ->name('peminjaman.ajukan');

    
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
        ->middleware('can:kelola-buku')
        ->name('buku.destroy');

    Route::get('/kelola-buku', [BukuCrudController::class, 'index'])
        ->middleware('can:kelola-buku')
        ->name('kelola-buku.index');

    Route::resource('admin/pengguna', UserController::class)
        ->parameters(['pengguna' => 'user'])
        ->except('show')
        ->names('admin.pengguna')
        ->middleware('permission:kelola data pengguna');

    Route::get('/kelola/peminjaman', [KelolaPeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('kelola.peminjaman');

    Route::post('/kelola/peminjaman/{peminjaman}/setujui', [KelolaPeminjamanController::class, 'setujui'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('kelola.peminjaman.setujui');

    Route::post('/kelola/peminjaman/{peminjaman}/tolak', [KelolaPeminjamanController::class, 'tolak'])
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('kelola.peminjaman.tolak');

    Route::get('/kelola/pengembalian', [PengembalianController::class, 'index'])
        ->middleware('permission:kelola pengembalian')
        ->name('kelola.pengembalian');

    Route::post('/kelola/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])
        ->middleware('permission:mencatat pengembalian buku')
        ->name('kelola.pengembalian.store');

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

    Route::get('/peminjaman-saya', [PeminjamanSayaController::class, 'status'])
        ->middleware('permission:melihat status peminjaman sendiri')
        ->name('peminjaman.status');

    Route::get('/riwayat-peminjaman', [PeminjamanSayaController::class, 'riwayat'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('peminjaman.riwayat');

    Route::get('/riwayat-peminjaman/{peminjaman}', [PeminjamanSayaController::class, 'show'])
        ->middleware('permission:melihat riwayat peminjaman sendiri')
        ->name('peminjaman.show');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');
        
    Route::get('/uji-error', function () {
        abort(500);
    })->name('uji.error');

});

require __DIR__.'/auth.php';
