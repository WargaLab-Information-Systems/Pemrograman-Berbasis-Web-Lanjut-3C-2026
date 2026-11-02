<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

Route::get('rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);

Route::apiResource('buku', BukuController::class)
    ->names('api.buku');
