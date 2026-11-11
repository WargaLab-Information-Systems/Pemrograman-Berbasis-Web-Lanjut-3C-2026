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

    // 8. Riwayat Peminjaman (Admin lihat semua, Petugas & Siswa hanya miliknya sendiri)
    Route::get('/peminjaman/riwayat', function () {
        $all = Cache::get('peminjamans', []);
        $user = Auth::user();

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            $peminjamans = $all;
        } else {
            $peminjamans = array_filter($all, function ($item) {
                return isset($item['user_id']) && $item['user_id'] == Auth::id();
            });
        }

        return view('peminjaman.riwayat', compact('peminjamans'));
    })->name('peminjaman.riwayat');

    // -------------------------------------------------------------------
    // RUTE MODUL 6 - 4 CELAH KEAMANAN
    // -------------------------------------------------------------------

    Route::get('/buku-cari', function (Request $request) {
        $judul = $request->query('judul');
        $buku = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");
        return response()->json($buku);
    })->name('buku.cari.vulnerable');

    Route::get('/buku/{id}/hapus', function ($id) {
        DB::table('bukus')->where('id', $id)->delete();
        return back()->with('status', 'Buku berhasil dihapus!');
    })->name('buku.hapus.unsafe');

    Route::get('/peminjaman/{id}', function ($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    })->name('peminjaman.detail.unsafe');

    // -------------------------------------------------------------------
    // RUTE UTAMA PERPUSTAKAAN
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

    Route::get('/dashboard/siswa', function () {
        return view('dashboard');
    })->name('siswa.dashboard');

    Route::get('/buku', function () {
        return view('buku.index');
    })->name('buku.index');

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
    })->name('peminjaman.store');

    Route::post('/peminjaman/setujui/{id}', function ($id) {
        $peminjamans = Cache::get('peminjamans', []);
        foreach ($peminjamans as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'Sedang Dipinjam';
            }
        }
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));
        return back()->with('status', 'Peminjaman buku berhasil disetujui!');
    })->name('peminjaman.setujui');

    Route::post('/peminjaman/kembali/{id}', function ($id) {
        $peminjamans = Cache::get('peminjamans', []);
        foreach ($peminjamans as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'Sudah Dikembalikan';
            }
        }
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));
        return back()->with('status', 'Pengembalian buku berhasil dicatat!');
    })->name('peminjaman.kembali');

    Route::post('/peminjaman/kembalikan-sendiri/{id}', function ($id) {
        $peminjamans = Cache::get('peminjamans', []);
        foreach ($peminjamans as &$item) {
            if ($item['id'] == $id && $item['user_id'] == Auth::id()) {
                $item['status'] = 'Sudah Dikembalikan';
            }
        }
        Cache::put('peminjamans', $peminjamans, now()->addDays(7));
        return back()->with('status', 'Buku berhasil dikembalikan!');
    })->name('peminjaman.kembalikan.sendiri');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 10. Akses Khusus Admin (DIKUNCI KETAT HANYA UNTUK ADMIN)
    Route::get('/dashboard/admin', function () {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        return view('dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/users', function () {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action. Petugas dilarang mengakses kelola akun.');
        }

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

    Route::post('/admin/users/store', function (Request $request) {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        try {
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if (method_exists($newUser, 'assignRole')) {
                $newUser->assignRole(strtolower($request->role));
            }
        } catch (\Exception $e) {
        }

        $users = Cache::get('app_users', []);
        $users[] = [
            'id' => time(),
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        Cache::put('app_users', $users, now()->addDays(7));

        return back()->with('status', 'Pengguna baru berhasil ditambahkan!');
    })->name('admin.users.store');

    Route::post('/admin/users/update-role/{id}', function (Request $request, $id) {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

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
    })->name('admin.users.update_role');

    Route::post('/admin/users/delete/{id}', function ($id) {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $users = Cache::get('app_users', []);
        $users = array_values(array_filter($users, fn($u) => $u['id'] != $id));
        Cache::put('app_users', $users, now()->addDays(7));
        return back()->with('status', 'Pengguna berhasil dihapus!');
    })->name('admin.users.delete');

    // 11. Akses Khusus Petugas
    Route::get('/dashboard/petugas', function () {
        return view('dashboard');
    })->name('petugas.dashboard');

    // 12. Akses Laporan (Admin & Petugas)
    Route::get('/laporan', function () {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'hasRole') || (!$user->hasRole('admin') && !$user->hasRole('petugas'))) {
            abort(403, 'Unauthorized action. Siswa dilarang mengakses halaman laporan.');
        }

        $peminjamans = Cache::get('peminjamans', []);
        return view('laporan.index', compact('peminjamans'));
    })->name('laporan.index');

    // Rute Form Tambah Buku (Dikunci dengan Gate kelola-buku agar Siswa mendapat 403 Forbidden)
    Route::get('/buku/create', function () {
        return view('buku.index');
    })->middleware(['auth', 'can:kelola-buku']);
});

require __DIR__ . '/auth.php';
