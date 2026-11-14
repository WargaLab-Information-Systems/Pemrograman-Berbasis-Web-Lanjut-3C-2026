<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PerpustakaanAccessController extends Controller
{
    // 1. Dashboard Admin
    public function dashboardAdmin()
    {
        $totalBuku = Buku::count();
        $totalUser = User::count();
        $users = User::with('roles')->latest()->take(5)->get();

        return view('dashboard.admin', compact('totalBuku', 'totalUser', 'users'));
    }

    // 2. Dashboard Petugas
    public function dashboardPetugas()
    {
        $totalBuku = Buku::count();
        $bukuTerbaru = Buku::with('kategori')->latest()->take(5)->get();

        return view('dashboard.petugas', compact('totalBuku', 'bukuTerbaru'));
    }

    // 3. Dashboard Siswa
    public function dashboardSiswa()
    {
        $totalBuku = Buku::count();
        $user = Auth::user();

        return view('dashboard.siswa', compact('totalBuku', 'user'));
    }

    // 4. Halaman Kelola Pengguna & Role (Admin)
    public function indexUsers()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    // 5. Tambah Pengguna Baru
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return back()->with('success', "Pengguna {$user->name} dengan role {$request->role} berhasil ditambahkan.");
    }

    // 6. Update Role Pengguna
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        // Clear cache izin Spatie agar hak akses baru langsung aktif seketika
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return back()->with('success', "Role untuk {$user->name} berhasil diperbarui menjadi {$request->role}.");
    }

    // 7. Hapus Akun Pengguna
    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Data pengguna berhasil dihapus.');
    }

    // 8. Pengajuan Peminjaman Buku (Otomatis Sync ke Tabel Anggota)
    public function ajuanPinjam($bukuId)
    {
        try {
            $user = Auth::user();

            // Otomatis buatkan record di tabel anggotas jika user belum terdaftar di tabel anggotas
            $anggota = Anggota::firstOrCreate(
                ['id' => $user->id],
                ['nama' => $user->name]
            );

            $adaPinjamanAktif = Peminjaman::where('anggota_id', $anggota->id)
                ->where('buku_id', $bukuId)
                ->whereIn('status', ['Pengajuan', 'Dipinjam'])
                ->first();

            if ($adaPinjamanAktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengajukan / meminjam buku ini.'
                ], 400);
            }

            Peminjaman::create([
                'anggota_id'     => $anggota->id,
                'buku_id'        => $bukuId,
                'tanggal_pinjam' => now(),
                'status'         => 'Pengajuan',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan peminjaman berhasil dikirim!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // 9. Halaman Kelola Peminjaman (Admin & Petugas)
    public function kelolaPeminjaman()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku.kategori'])->latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    // 10. Update Status Peminjaman
    public function updateStatusPeminjaman(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|string'
            ]);

            $peminjaman = Peminjaman::findOrFail($id);
            $peminjaman->status = $request->status;

            if ($request->status === 'Dipinjam') {
                $peminjaman->tanggal_pinjam = now();
            } elseif ($request->status === 'Selesai') {
                $peminjaman->tanggal_kembali = now();
            }

            $peminjaman->save();

            return response()->json([
                'success' => true,
                'message' => 'Status peminjaman berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // 11. Halaman Laporan Rekapitulasi Perpustakaan
    public function laporanPerpustakaan()
    {
        $bukus = Buku::with(['kategori', 'peminjamans' => function ($query) {
            $query->whereIn('status', ['Dipinjam', 'Pengajuan']);
        }])->get();

        $totalUser = User::count();
        $totalBuku = $bukus->count();

        return view('peminjaman.laporan', compact('bukus', 'totalUser', 'totalBuku'));
    }

    // 12. Halaman Riwayat Peminjaman Mandiri
    public function riwayatSendiri($userId = null)
    {
        if ($userId !== null && (int) $userId !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda tidak diizinkan melihat riwayat peminjaman milik pengguna lain.');
        }

        $user = Auth::user();
        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $user->id)
            ->latest()
            ->get();

        return view('peminjaman.riwayat-sendiri', compact('user', 'peminjamans'));
    }

    // 13. Halaman Profil Saya
    public function profilSendiri($userId = null)
    {
        if ($userId !== null && (int) $userId !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda hanya dapat melihat dan mengubah profil milik Anda sendiri.');
        }

        $user = Auth::user();
        return view('profile.saya', compact('user'));
    }

    // 14. Update Profil Saya
    public function updateProfilSendiri(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}