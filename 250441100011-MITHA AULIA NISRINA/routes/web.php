<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\BukuCrudController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatPeminjamanController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');


/*
|--------------------------------------------------------------------------
| Daftar Buku
|--------------------------------------------------------------------------
*/

Route::get('/buku', [BukuController::class, 'index'])
    ->middleware([
        'auth',
        'permission:lihat daftar buku',
    ])
    ->name('buku.index');

Route::get('/buku/{id}', [BukuController::class, 'show'])
    ->middleware([
        'auth',
        'permission:lihat daftar buku',
    ])
    ->name('buku.show');


/*
|--------------------------------------------------------------------------
| Pengajuan Peminjaman Buku
|--------------------------------------------------------------------------
*/

Route::post('/buku/{buku}/pinjam', [PeminjamanController::class, 'store'])
    ->middleware([
        'auth',
        'permission:mengajukan peminjaman buku',
    ])
    ->name('peminjaman.store');


/*
|--------------------------------------------------------------------------
| Riwayat Peminjaman Sendiri
|--------------------------------------------------------------------------
|
| Riwayat yang ditampilkan ditentukan dari pengguna yang sedang login,
| bukan dari ID pengguna yang dikirim melalui URL.
|
*/

Route::get(
    '/riwayat-peminjaman',
    [RiwayatPeminjamanController::class, 'index']
)
    ->middleware([
        'auth',
        'permission:melihat riwayat peminjaman sendiri',
    ])
    ->name('riwayat-peminjaman.index');


/*
|--------------------------------------------------------------------------
| Pengelolaan Peminjaman, Pengembalian, dan Laporan
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin|petugas',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Peminjaman
    |--------------------------------------------------------------------------
    */

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])
        ->middleware('permission:kelola peminjaman')
        ->name('peminjaman.index');

    Route::patch(
        '/peminjaman/{peminjaman}/setujui',
        [PeminjamanController::class, 'approve']
    )
        ->middleware('permission:memproses persetujuan peminjaman')
        ->name('peminjaman.approve');


    /*
    |--------------------------------------------------------------------------
    | Pengembalian
    |--------------------------------------------------------------------------
    */

    Route::get('/pengembalian', [PeminjamanController::class, 'returns'])
        ->middleware('permission:kelola pengembalian')
        ->name('pengembalian.index');

    Route::patch(
        '/pengembalian/{peminjaman}/kembalikan',
        [PeminjamanController::class, 'returnBook']
    )
        ->middleware('permission:mencatat pengembalian buku')
        ->name('pengembalian.return');


    /*
    |--------------------------------------------------------------------------
    | Laporan Perpustakaan
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->middleware('permission:lihat laporan perpustakaan')
        ->name('laporan.index');

});


/*
|--------------------------------------------------------------------------
| Kelola Buku
|--------------------------------------------------------------------------
*/

Route::get('/kelola-buku', [BukuCrudController::class, 'index'])
    ->middleware([
        'auth',
        'permission:kelola data buku',
    ])
    ->name('kelola-buku.index');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin',
    'permission:akses dashboard admin',
])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});


/*
|--------------------------------------------------------------------------
| Manajemen Pengguna dan Role
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin',
])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/users', [UserManagementController::class, 'index'])
        ->middleware('permission:kelola data pengguna')
        ->name('users.index');

    Route::patch(
        '/users/{user}/role',
        [UserManagementController::class, 'updateRole']
    )
        ->middleware('permission:kelola role pengguna')
        ->name('users.update-role');

});


/*
|--------------------------------------------------------------------------
| Dashboard Petugas
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:petugas',
    'permission:akses dashboard petugas',
])->group(function () {

    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');

});


/*
|--------------------------------------------------------------------------
| Profil Pengguna
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:ubah profil sendiri')
        ->name('profile.update');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';