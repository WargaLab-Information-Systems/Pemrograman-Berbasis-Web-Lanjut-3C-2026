<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Redirect Halaman Utama ke Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});


// SEMUA ROUTE UTAMA (Membutuhkan Login)
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard');
    })->name('dashboard.admin');

    // 2. Koleksi Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    // ROUTE CELAH A3: Menghapus buku via GET
    Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy']);

    // 3. Kelola & Pengajuan Peminjaman (Admin, Petugas, & Siswa)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'pengembalian'])->name('peminjaman.kembali');
    // ROUTE CELAH A1: Mengubah status peminjaman via GET
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    
    // RUTE RIWAYAT SAYA (Yang dipanggil oleh Navbar Blade)
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])->name('peminjaman.riwayat');

    // RUTE RIWAYAT BERDASARKAN ID (Untuk pengujian 403)
    Route::get('/riwayat-peminjaman/{id}', [PeminjamanController::class, 'riwayatBerdasarkanId'])->name('peminjaman.riwayat.id');

    // 4. Laporan & Persetujuan (Khusus Admin & Petugas)
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.index');
        Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        // Route celah A4 Soal 1
        Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        
        // CRUD Buku
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    // 5. Kelola Data Pengguna (Khusus Admin)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 6. Profile Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // Penutup Route::middleware(['auth'])

// Route Celah A2: Menggabungkan variabel $judul langsung ke string SQL (WHERE judul = '$judul')
Route::middleware(['auth'])->get('/buku-cari', function (Request $request) {
    $judul = $request->query('judul');
    
    // Celah SQLi: Input $judul ditempel langsung tanpa parameter binding
    $buku = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
    
    return response()->json($buku);
});


require __DIR__.'/auth.php';
