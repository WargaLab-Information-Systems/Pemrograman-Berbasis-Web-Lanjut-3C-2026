<?php

use App\Models\User;
use App\Models\Peminjaman;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // Tambahan untuk DB::select (A2)
use Illuminate\Http\Request;       // Tambahan untuk Request (A2)

Route::redirect('/', '/login');

// ==========================================
// 1. RUTE UMUM & PENGATUR TRAFFIC LOGIN
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Traffic Controller
    Route::get('/dashboard', function () {
        $user = request()->user();
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        elseif ($user->hasRole('petugas')) return redirect()->route('petugas.dashboard');
        else return redirect()->route('siswa.dashboard');
    })->name('dashboard');

    // Rute Riwayat Pinjaman 
    Route::get('/riwayat', function () {
        $user = request()->user();
        $anggota = \App\Models\Anggota::where('email', $user->email)->first();
        $riwayats = collect();
        if ($anggota) {
            $riwayats = \App\Models\Peminjaman::with('buku')
                            ->where('anggota_id', $anggota->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }
        return view('siswa.riwayat', compact('riwayats'));
    })->name('riwayat');

    // Pengaturan Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Menu Koleksi Buku 
    Route::get('/buku', function () {
        $bukus = \App\Models\Buku::all();
        return view('buku.index', compact('bukus'));
    })->name('buku.index')->middleware('permission:lihat daftar buku');

    // ====================================================================
    // Menambal A2: SQL INJECTION (Menggunakan Query Binding)
    // ====================================================================
    Route::get('/buku-cari', function (Request $request) {
        $judul = $request->query('judul');
        
        $buku = \Illuminate\Support\Facades\DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);
        return response()->json($buku);
    });

    Route::get('/buku/create', function () {
        return view('buku.create');
    })->middleware('can:kelola-buku');

    // ====================================================================
    // Menambal A3: Route DELETE (Menghapus metode GET yang rentan)
    // ====================================================================
    Route::delete('/buku/{id}', function ($id) {
        \App\Models\Buku::destroy($id);
        return redirect()->back();
    })->middleware('can:kelola-buku');

    // Rute Detail Buku
    Route::get('/buku/{id}', function ($id) {
        $buku = \App\Models\Buku::findOrFail($id);
        return view('buku.show', compact('buku'));
    })->name('buku.show');

    // Rute Pengajuan Pinjam 
    Route::post('/ajukan-pinjam', function (\Illuminate\Http\Request $request) {
        $request->validate(['buku_id' => 'required|exists:bukus,id']);

        $bukuTidakTersedia = \App\Models\Peminjaman::where('buku_id', $request->buku_id)
            ->whereIn('status', ['Menunggu Persetujuan', 'Sedang Dipinjam'])
            ->exists();

        if ($bukuTidakTersedia) {
            return response()->json(['message' => 'Maaf, buku ini sedang dipinjam dan belum dikembalikan.'], 400);
        }

        $user = request()->user();
        $anggota = \App\Models\Anggota::where('email', $user->email)->first();

        if (!$anggota) {
            $anggota = \App\Models\Anggota::create([
                'nama' => $user->name,
                'email' => $user->email,
                'no_telepon' => '-' 
            ]);
        }

        \App\Models\Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => now()->format('Y-m-d'),
            'status' => 'Menunggu Persetujuan'
        ]);

        return response()->json(['message' => 'Berhasil diajukan! Menunggu persetujuan petugas.']);
    })->name('ajukan-pinjam');

    // Menu Peminjaman & Laporan 
    Route::get('/peminjaman', function () {
        $peminjamans = Peminjaman::with(['buku', 'anggota'])->orderBy('created_at', 'desc')->get();
        $totalDipinjam = Peminjaman::where('status', 'Sedang Dipinjam')->count();
        $pengajuanBaru = Peminjaman::where('status', 'Menunggu Persetujuan')->count();
        $pengembalianBulanIni = Peminjaman::where('status', 'Dikembalikan')
                                          ->whereMonth('updated_at', date('m'))
                                          ->count();

        return view('peminjaman.index', compact('peminjamans', 'totalDipinjam', 'pengajuanBaru', 'pengembalianBulanIni'));
    })->name('peminjaman.index')->middleware('permission:kelola peminjaman|memproses persetujuan peminjaman');

    // ====================================================================
    // Menambal A4: Menambal IDOR (Menggunakan POlicy PeminjamanPolicy)
    // ====================================================================
    Route::get('/peminjaman/{id}', function ($id) {
        // VULNERABLE: Memanggil findOrFail tanpa memvalidasi kepemilikan siswa
        $peminjaman = Peminjaman::findOrFail($id);

        \Illuminate\Support\Facades\Gate::authorize('view', $peminjaman);        
        
        // Menampilkan JSON agar celah langsung terlihat dengan mudah di browser
        return response()->json($peminjaman);
    });

    Route::patch('/peminjaman/{id}/setujui', function ($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Sedang Dipinjam', 'tanggal_pinjam' => now()->format('Y-m-d')]);
        return back();
    })->name('peminjaman.setujui')->middleware('role:petugas|admin');

    Route::patch('/peminjaman/{id}/kembali', function ($id) {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Dikembalikan', 'tanggal_kembali' => now()->format('Y-m-d')]);
        return back();
    })->name('peminjaman.kembali')->middleware('role:petugas|admin');
});

// ==========================================
// 2. RUTE KHUSUS ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('akun', AkunController::class);
});

// ==========================================
// 3. RUTE KHUSUS PETUGAS
// ==========================================
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});

// ==========================================
// 4. RUTE KHUSUS SISWA
// ==========================================
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/dashboard', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');
});

Route::get('/error-test', function () {
    throw new \RuntimeException('Ini test error');
});

require __DIR__.'/auth.php';