<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RekapController;
use App\Http\Controllers\Api\BukuController;

Route::apiResource('buku', BukuController::class);

Route::get(
    '/rekap/buku-per-kategori',
    [RekapController::class, 'bukuPerKategori']
);

Route::get(
    '/rekap/pemminjaman-per-buku',
    [RekapController::class, 'peminjamanPerBuku']
);