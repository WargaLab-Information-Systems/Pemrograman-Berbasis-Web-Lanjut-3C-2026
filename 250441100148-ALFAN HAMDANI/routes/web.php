<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

// 1. Route Beranda
Route::get('/', function () {
    return view('home');
})->name('home');

// 2. Route Halaman Buku
Route::get('/buku', function () {
    return view('buku.index');
})->name('buku.index');

// 2 & 3. Route Web Lama (BukuController)
// Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
// Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');