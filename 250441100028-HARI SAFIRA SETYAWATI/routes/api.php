<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use App\Models\Kategori;

Route::get('/kategori', function () {
    return response()->json(
        Kategori::query()
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get()
    );
});


Route::apiResource('buku', BukuController::class);

Route::name('api.')->group(function () {
    Route::apiResource('buku', BukuController::class);
});

Route::get(
    '/rekap/buku-per-kategori',
    [RekapController::class, 'bukuPerKategori']
);

Route::get(
    '/rekap/pemminjaman-per-buku',
    [RekapController::class, 'peminjamanPerBuku']
);