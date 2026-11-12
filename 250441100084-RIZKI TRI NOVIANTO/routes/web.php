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

    // 3. CRUD Katalog Buku (Menggantikan route manual index & show agar /buku/create tidak bentrok dengan /buku/{id})
    Route::resource('buku', BukuController::class);

    // 4. Pengajuan & Riwayat Peminjaman (Semua Role)
    Route::patch('/buku/{id}/pinjam', [PerpustakaanAccessController::class, 'ajuanPinjam'])->name('buku.pinjam');
    Route::get('/riwayat-peminjaman/{id?}', [PerpustakaanAccessController::class, 'riwayatSendiri'])->name('peminjaman.saya');

    // =========================================================================
    // MODUL 6 - SOAL 1: CELAH KEAMANAN (A2, A3, A4)

    // CELAH A2: SQL Injection (Input judul langsung ke query SQL)
    Route::get('/buku-cari', function (Request $request) {
        $judul = $request->query('judul');
        $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
        return response()->json($hasil);
    });

    // CELAH A3: CSRF via GET untuk penghapusan buku
    Route::get('/buku/{buku}/hapus', [BukuController::class, 'destroy']);

    // CELAH A4: IDOR pada halaman detail peminjaman (tanpa mengecek kepemilikannya )
    Route::get('/peminjaman/{id}', [PerpustakaanAccessController::class, 'showPeminjaman'])->name('peminjaman.show');

    // =========================================================================

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