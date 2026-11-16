<?php

use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

/*
| Modul 5: seluruh API dilindungi.
|  - 'web'  : mengaktifkan session dan CSRF, agar login dari browser dikenali
|  - 'auth' : harus login (belum login = 401 JSON)
|  - 'permission:...' : harus punya hak akses (tidak berhak = 403 JSON)
*/
Route::middleware(['web', 'auth'])->group(function () {

    // Rekap (Modul 3, Soal 7): khusus yang boleh melihat laporan
    Route::middleware('permission:lihat laporan perpustakaan')->group(function () {
        Route::get('rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
        Route::get('rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    });

    // CRUD Buku (Modul 3): baca = lihat daftar buku, tulis = Gate 'kelola-buku' (Modul 6 Soal 3)
    Route::apiResource('buku', BukuController::class)
        ->names('api.buku')
        ->middlewareFor(['index', 'show'], 'permission:lihat daftar buku')
        // Soal 3: operasi tulis (tambah/ubah/hapus) dipusatkan pada Gate 'kelola-buku'
        ->middlewareFor(['store', 'update', 'destroy'], 'can:kelola-buku');
});
