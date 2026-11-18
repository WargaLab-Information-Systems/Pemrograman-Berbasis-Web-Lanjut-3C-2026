<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/rekap/buku-per-kategori', [BukuController::class, 'bukuPerKategori']);
Route::get('/rekap/peminjaman-per-buku', [BukuController::class, 'peminjamanPerBuku']);

Route::apiResource('buku', BukuController::class);