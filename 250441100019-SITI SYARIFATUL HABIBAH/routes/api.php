<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);

Route::apiResource('buku', BukuController::class)->names('api.buku');