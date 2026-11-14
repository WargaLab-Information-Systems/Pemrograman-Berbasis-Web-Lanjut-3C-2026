<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

/*
|--------------------------------------------------------------------------
| API Buku - Akses Membaca
|--------------------------------------------------------------------------
|
| Endpoint GET tetap dipertahankan untuk mengambil daftar dan detail buku.
| Endpoint ini digunakan oleh halaman Daftar Buku dan Kelola Buku melalui
| Fetch API seperti pada implementasi modul sebelumnya.
|
*/

// Menampilkan daftar buku
Route::get('/buku', [BukuController::class, 'index'])
    ->name('api.buku.index');

// Menampilkan detail satu buku
Route::get('/buku/{buku}', [BukuController::class, 'show'])
    ->name('api.buku.show');


/*
|--------------------------------------------------------------------------
| API Buku - Kelola Data Buku
|--------------------------------------------------------------------------
|
| Tambah, ubah, dan hapus buku hanya dapat dilakukan oleh pengguna
| yang sudah login dan memiliki permission "kelola data buku".
| Permission ini dimiliki oleh Admin dan Petugas.
|
*/

Route::middleware([
    'web',
    'auth',
    'can:kelola-buku',
])->group(function () {

    // Menambahkan buku
    Route::post('/buku', [BukuController::class, 'store'])
        ->name('api.buku.store');

    // Memperbarui buku
    Route::put('/buku/{buku}', [BukuController::class, 'update'])
        ->name('api.buku.update');

    Route::patch('/buku/{buku}', [BukuController::class, 'update']);

    // Menghapus buku
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])
        ->name('api.buku.destroy');

});


/*
|--------------------------------------------------------------------------
| API Rekap
|--------------------------------------------------------------------------
|
| Route rekap dari modul sebelumnya tetap dipertahankan.
|
*/

Route::get(
    '/rekap/buku-per-kategori',
    [RekapController::class, 'bukuPerKategori']
);

Route::get(
    '/rekap/pemminjaman-per-buku',
    [RekapController::class, 'peminjamanPerBuku']
);