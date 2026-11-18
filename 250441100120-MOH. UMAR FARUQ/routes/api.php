<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route API Buku (Soal 1)
Route::name('api.')->group(function () {
    Route::apiResource('buku', BukuController::class);
});

// Route API Rekap (Soal 7)
Route::get('/rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
Route::get('/rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
