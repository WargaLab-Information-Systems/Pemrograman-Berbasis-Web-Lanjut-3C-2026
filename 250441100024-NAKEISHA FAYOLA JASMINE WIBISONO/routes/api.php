<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

// Semua endpoint API wajib token (Sanctum), lalu dicek permission-nya.
Route::middleware('auth:sanctum')->group(function () {

    // Lihat daftar dan detail buku: semua role
    Route::apiResource('buku', BukuController::class)
        ->only(['index', 'show'])
        ->middleware('permission:lihat-daftar-buku')
        ->names('api.buku');

    // Tambah, ubah, hapus buku: admin dan petugas
    Route::apiResource('buku', BukuController::class)
        ->only(['store', 'update', 'destroy'])
        ->middleware('permission:kelola-data-buku')
        ->names('api.buku');

    // Rekap: admin dan petugas
    Route::middleware('permission:lihat-laporan-perpustakaan')->group(function () {
        Route::get('rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
        Route::get('rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    });
});
