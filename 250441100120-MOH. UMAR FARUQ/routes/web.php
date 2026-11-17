<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController; // Sesuaikan namespace jika ada di folder Api

// Halaman utama (Home)
Route::get('/', function () {
    return view('home');
})->name('home');

// Rute untuk menampilkan TAMPILAN HALAMAN WEB (Blade view)
Route::get('/buku', function () {
    return view('buku.index'); // Menunjuk ke resources/views/buku/index.blade.php
})->name('buku.index');

// Jika ingin menggunakan Controller untuk web view detail (opsional)
// Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');