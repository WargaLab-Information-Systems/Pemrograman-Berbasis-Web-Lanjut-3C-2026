<?php

use App\Http\Controllers\ProfileController;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Middleware Auth (Seluruh route di dalam grup ini wajib login)
Route::middleware(['auth'])->group(function () {

    // -------------------------------------------------------------------
    // RUTE BARU MODUL 6 - SOAL 1 (4 CELAH KEAMANAN)
    // -------------------------------------------------------------------
    
    // Celah A2: SQL Injection (Penggabungan String SQL Langsung)
    Route::get('/buku-cari', function (Request $request) {
        $judul = $request->query('judul');
        $buku = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
        return response()->json($buku);
    })->name('buku.cari.vulnerable');

    // Celah A3: CSRF via HTTP GET (Menghapus Data Tanpa Token Validasi)
    Route::get('/buku/{id}/hapus', function ($id) {
        DB::table('bukus')->where('id', $id)->delete();
        return back()->with('status', 'Buku berhasil dihapus!');
    })->name('buku.hapus.unsafe');

    // Celah A4: IDOR (Detail Peminjaman Tanpa Cek Kepemilikan User)
    Route::get('/peminjaman/{id}', function ($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    })->name('peminjaman.detail.unsafe');

    // -------------------------------------------------------------------
    // RUTE BAWAAN MODUL 5 (UTUH TANPA DIUBAH)
    // -------------------------------------------------------------------

    // 1. Dashboard Sesuai Role
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user && method_exists($user, 'hasRole')) {
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('petugas')) {
                return redirect()->route('petugas.dashboard');
            } elseif ($user->hasRole('siswa')) {
                return redirect()->route('siswa.dashboard');
            }
        }

        return view('dashboard');
    })->name('dashboard');

    // 2. Dashboard Khusus Siswa (/dashboard/siswa)
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/dashboard/siswa', function () {
            return view('dashboard');
        })->name('siswa.dashboard');
    });

    // 3. Halaman Daftar Buku (Permission Protection)
    Route::get('/buku', function () {
        return view('buku.index');
    })->middleware('permission:Lihat daftar buku')->name('buku.index');

    // 4. Simpan Pengajuan Peminjaman Buku (Global Cache Storage)
    Route::post('/peminjaman/store', function (Request $request) {
        $peminjamans = Cache::get('peminjamans', []);
        
        $newItem = [
            'id' => time() . rand(10, 99),
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'judul' => $request->judul ?? 'Buku Tanpa Judul',
            'tanggal_pinjam' => now()->format('d M Y'),
            'tanggal_kembali' => now()->addDays(7)->format('d M Y'),
            'status' => 'Menunggu Persetujuan',
        ];

        $peminjamans[] = $newItem;
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));

        return response()->json(['message' => 'Peminjaman berhasil diajukan!']);
    })->middleware('permission:Mengajukan peminjaman buku')->name('peminjaman.store');

    // 5. Proses Persetujuan Peminjaman (Petugas / Admin)
    Route::post('/peminjaman/setujui/{id}', function ($id) {
        $peminjamans = Cache::get('peminjamans', []);
        foreach ($peminjamans as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'Sedang Dipinjam';
            }
        }
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));
        return back()->with('status', 'Peminjaman buku berhasil disetujui!');
    })->middleware('permission:Memproses persetujuan peminjaman')->name('peminjaman.setujui');

    // 6. Proses Catat Pengembalian Buku (Petugas / Admin)
    Route::post('/peminjaman/kembali/{id}', function ($id) {
        $peminjamans = Cache::get('peminjamans', []);
        foreach ($peminjamans as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'Sudah Dikembalikan';
            }
        }
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));
        return back()->with('status', 'Pengembalian buku berhasil dicatat!');
    })->middleware('permission:Mencatat pengembalian buku')->name('peminjaman.kembali');

    // 7. Pengembalian Mandiri dari Halaman Riwayat Saya (Siswa)
    Route::post('/peminjaman/kembalikan-sendiri/{id}', function ($id) {
        $peminjamans = Cache::get('peminjamans', []);
        foreach ($peminjamans as &$item) {
            if ($item['id'] == $id && $item['user_id'] == Auth::id()) {
                $item['status'] = 'Sudah Dikembalikan';
            }
        }
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));
        return back()->with('status', 'Buku berhasil dikembalikan!');
    })->middleware('permission:Melihat riwayat peminjaman sendiri')->name('peminjaman.kembalikan.sendiri');

    // 8. Riwayat Peminjaman Saya (Ownership Check Protection)
    Route::get('/peminjaman/riwayat', function () {
        $all = Cache::get('peminjamans', []);
        
        $peminjamans = array_filter($all, function ($item) {
            return $item['user_id'] == Auth::id();
        });

        return view('peminjaman.riwayat', compact('peminjamans'));
    })->middleware('permission:Melihat riwayat peminjaman sendiri')->name('peminjaman.riwayat');

    // 9. Kelola Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->middleware('permission:Lihat profil sendiri')
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->middleware('permission:Ubah profil sendiri')
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // 10. Akses Khusus Admin (/dashboard/admin & User Management)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard/admin', function () {
            return view('dashboard');
        })->name('admin.dashboard');

        Route::get('/admin/users', function () {
            if (!Cache::has('app_users')) {
                Cache::put('app_users', [
                    ['id' => 1, 'name' => 'Admin Perpustakaan', 'email' => 'admin@test.com', 'role' => 'Admin'],
                    ['id' => 2, 'name' => 'Petugas Perpustakaan', 'email' => 'petugas@test.com', 'role' => 'Petugas'],
                    ['id' => 3, 'name' => 'Siswa Perpustakaan', 'email' => 'siswa@test.com', 'role' => 'Siswa'],
                ], now()->addDays(7));
            }
            $users = Cache::get('app_users', []);
            return view('admin.users', compact('users'));
        })->name('admin.users');

        // Action: Tambah User (Tersimpan ke DB & Hashing Password)
        Route::post('/admin/users/store', function (Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
                'role' => 'required|string',
            ]);

            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                if (method_exists($user, 'assignRole')) {
                    $user->assignRole(strtolower($request->role));
                }
            } catch (\Exception $e) {}

            $users = Cache::get('app_users', []);
            $users[] = [
                'id' => time(),
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];
            Cache::put('app_users', $users, now()->addDays(7));

            return back()->with('status', 'Pengguna baru berhasil ditambahkan dan siap digunakan untuk login!');
        })->middleware('permission:Kelola data pengguna')->name('admin.users.store');

        // Action: Ubah Role User
        Route::post('/admin/users/update-role/{id}', function (Request $request, $id) {
            $users = Cache::get('app_users', []);
            foreach ($users as &$u) {
                if ($u['id'] == $id) {
                    $u['role'] = $request->role;
                }
            }
            Cache::put('app_users', $users, now()->addDays(7));

            $userDb = User::where('email', $request->email ?? '')->first();
            if ($userDb && method_exists($userDb, 'syncRoles')) {
                $userDb->syncRoles([strtolower($request->role)]);
            }

            return back()->with('status', 'Role pengguna berhasil diperbarui!');
        })->middleware('permission:Kelola role pengguna')->name('admin.users.update_role');

        // Action: Hapus User
        Route::post('/admin/users/delete/{id}', function ($id) {
            $users = Cache::get('app_users', []);
            $users = array_values(array_filter($users, fn($u) => $u['id'] != $id));
            Cache::put('app_users', $users, now()->addDays(7));
            return back()->with('status', 'Pengguna berhasil dihapus!');
        })->middleware('permission:Kelola data pengguna')->name('admin.users.delete');
    });

    // 11. Akses Khusus Petugas (/dashboard/petugas)
    Route::middleware(['role:petugas'])->group(function () {
        Route::get('/dashboard/petugas', function () {
            return view('dashboard');
        })->name('petugas.dashboard');
    });

    // 12. Akses Laporan (Admin & Petugas)
    Route::middleware(['role:admin|petugas'])->group(function () {
        Route::get('/laporan', function () {
            $peminjamans = Cache::get('peminjamans', []);
            return view('laporan.index', compact('peminjamans'));
        })->name('laporan.index');
    });
});

require __DIR__.'/auth.php';