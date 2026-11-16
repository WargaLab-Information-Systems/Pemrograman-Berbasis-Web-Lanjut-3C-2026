<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

Route::middleware('web')->group(function () {
    Route::get('buku', [BukuController::class, 'index']);
    Route::get('buku/{buku}', [BukuController::class, 'show']);
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::middleware('can:kelola-buku')->group(function () {
        Route::post('buku', [BukuController::class, 'store']);
        Route::match(['put', 'patch'], 'buku/{buku}', [BukuController::class, 'update']);
        Route::delete('buku/{buku}', [BukuController::class, 'destroy']);
    });

    Route::middleware('permission:lihat laporan perpustakaan')->group(function () {
        Route::get('rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
        Route::get('rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    });
});
