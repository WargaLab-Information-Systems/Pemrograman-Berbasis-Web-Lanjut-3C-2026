<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PerpustakaanAccessController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Perpustakaan Digital
|--------------------------------------------------------------------------
*/

// Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Seluruh Route yang Membutuhkan Autentikasi (User Wajib Login)
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard Routing Otomatis Berdasarkan Role User
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('dashboard.siswa');
    })->name('dashboard');

    // Dashboard Spesifik Siswa
    Route::get('/siswa/dashboard', [PerpustakaanAccessController::class, 'dashboardSiswa'])->name('dashboard.siswa');

    // 2. Profil User (Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profil Mandiri (Versi PerpustakaanAccessController)
    Route::get('/profile/saya/{id?}', [PerpustakaanAccessController::class, 'profilSendiri'])->name('profile.saya');
    Route::put('/profile/saya', [PerpustakaanAccessController::class, 'updateProfilSendiri'])->name('profile.update_saya');

    // 3. Katalog & CRUD Buku (Soal 3: Proteksi Permission 'kelola-buku')
    Route::middleware(['can:kelola-buku'])->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::patch('/buku/{buku}', [BukuController::class, 'update']);
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');

    // 4. Pengajuan & Riwayat Peminjaman (Semua Role)
    Route::patch('/buku/{id}/pinjam', [PerpustakaanAccessController::class, 'ajuanPinjam'])->name('buku.pinjam');
    Route::get('/riwayat-peminjaman/{id?}', [PerpustakaanAccessController::class, 'riwayatSendiri'])->name('peminjaman.saya');

    // Perbaikan A2: SQL Injection menggunakan Parameter Binding
    Route::get('/buku-cari', function (Request $request) {
        $judul = $request->query('judul');
        $hasil = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);
        return response()->json($hasil);
    });


    // Melihat peminjaman yang diamankan dengan Policy
    Route::get('/peminjaman/{id}', [PerpustakaanAccessController::class, 'showPeminjaman'])->name('peminjaman.show');


    // 5. Khusus Role Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [PerpustakaanAccessController::class, 'dashboardAdmin'])->name('admin.dashboard');

        // Fitur Kelola Pengguna & Penetapan Role
        Route::get('/admin/pengguna', [PerpustakaanAccessController::class, 'indexUsers'])->name('admin.pengguna');
        Route::post('/admin/pengguna', [PerpustakaanAccessController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/admin/pengguna/{user}', [PerpustakaanAccessController::class, 'updateUserRole'])->name('admin.users.role');
        Route::delete('/admin/pengguna/{user}', [PerpustakaanAccessController::class, 'destroyUser'])->name('admin.users.destroy');
    });

    // 6. Khusus Role Petugas
    Route::middleware(['role:petugas'])->group(function () {
        Route::get('/petugas/dashboard', [PerpustakaanAccessController::class, 'dashboardPetugas'])->name('petugas.dashboard');
    });

    // 7. Kelola Peminjaman, Update Status, & Laporan (Admin ATAU Petugas)
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/kelola/peminjaman', [PerpustakaanAccessController::class, 'kelolaPeminjaman'])->name('peminjaman.manage');
        Route::patch('/kelola/peminjaman/{id}/status', [PerpustakaanAccessController::class, 'updateStatusPeminjaman'])->name('peminjaman.update_status');
        Route::get('/laporan', [PerpustakaanAccessController::class, 'laporanPerpustakaan'])->name('laporan.index');
    });


});

require __DIR__ . '/auth.php';
