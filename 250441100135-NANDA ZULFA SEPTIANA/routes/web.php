<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BukuCrudController;

// Halaman Beranda
Route::view('/', 'home')->name('home');

// Halaman Daftar Buku
// Route::get('/buku', [BukuController::class, 'index'])
//     ->name('buku.index');
// Route::get('/buku', function () {
//     return view('buku'); // Memanggil file resources/views/buku.blade.php
// });

// Halaman Detail Buku
Route::get('/buku/{buku}', [BukuController::class, 'show'])
    ->name('buku.show');

Route::get('/kelola-buku', [BukuCrudController::class, 'index'])->name('kelola-buku.index');

Route::view('/bukuku', 'buku');

// Route::view('/bukuku', 'index')->name('bukuku.index');
Route::get('/buku', function () {
    return view('buku'); // Sesuaikan dengan nama file view/blade Anda
});