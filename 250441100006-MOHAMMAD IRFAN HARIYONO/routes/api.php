<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

// Route API Buku
Route::apiResource('buku', BukuController::class)->names('api.buku');

// Route API Rekap
Route::get('/rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
Route::get('/rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);