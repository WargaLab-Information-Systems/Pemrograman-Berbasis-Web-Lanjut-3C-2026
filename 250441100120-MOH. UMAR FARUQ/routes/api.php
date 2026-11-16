<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;
Route::apiResource('buku', BukuController::class);

// Tambahan untuk Soal 7 (Endpoint Rekap)
Route::get('rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);

