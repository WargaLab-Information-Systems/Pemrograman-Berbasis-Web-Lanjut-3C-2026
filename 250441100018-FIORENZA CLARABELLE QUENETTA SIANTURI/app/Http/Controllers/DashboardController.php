<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        $anggota = $user->anggota;
        $pinjaman = $anggota ? $anggota->peminjamans() : Peminjaman::whereRaw('1 = 0');

        return view('dashboard', [
            'diajukan' => (clone $pinjaman)->where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'dipinjam' => (clone $pinjaman)->where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'selesai' => (clone $pinjaman)->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
        ]);
    }

    public function admin()
    {
        return view('admin.dashboard', [
            'totalBuku' => Buku::count(),
            'totalUser' => User::count(),
            'perRole' => Role::withCount('users')->orderBy('name')->get(),
            'menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'aktif' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'totalPeminjaman' => Peminjaman::count(),
        ]);
    }

    public function petugas()
    {
        return view('admin.petugas-dashboard', [
            'totalBuku' => Buku::count(),
            'totalAnggota' => Anggota::count(),
            'menunggu' => Peminjaman::where('status', Peminjaman::STATUS_DIAJUKAN)->count(),
            'aktif' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
            'terbaru' => Peminjaman::with(['anggota', 'buku'])
                ->where('status', Peminjaman::STATUS_DIAJUKAN)
                ->latest()->limit(5)->get(),
        ]);
    }
}
