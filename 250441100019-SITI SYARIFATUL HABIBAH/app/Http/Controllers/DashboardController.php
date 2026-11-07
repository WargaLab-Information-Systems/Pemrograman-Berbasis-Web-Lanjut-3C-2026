<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return redirect()->route('buku.index');
    }

    public function admin(): View
    {
        $kartu = [
            'Total Buku'              => Buku::count(),
            'Total Anggota'           => Anggota::count(),
            'Total Pengguna'          => User::count(),
            'Menunggu Persetujuan'    => Peminjaman::where('status', Peminjaman::DIAJUKAN)->count(),
            'Sedang Dipinjam'         => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
        ];

        $perRole = Role::withCount('users')->orderBy('name')->pluck('users_count', 'name');

        return view('dashboard.staff', [
            'judul'    => 'Dashboard Admin',
            'kartu'    => $kartu,
            'perRole'  => $perRole,
            'menunggu' => $this->pengajuanTerbaru(),
        ]);
    }

    public function petugas(): View
    {
        $kartu = [
            'Total Buku'           => Buku::count(),
            'Menunggu Persetujuan' => Peminjaman::where('status', Peminjaman::DIAJUKAN)->count(),
            'Sedang Dipinjam'      => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'Sudah Dikembalikan'   => Peminjaman::where('status', Peminjaman::DIKEMBALIKAN)->count(),
        ];

        return view('dashboard.staff', [
            'judul'    => 'Dashboard Petugas',
            'kartu'    => $kartu,
            'perRole'  => null,
            'menunggu' => $this->pengajuanTerbaru(),
        ]);
    }

    private function pengajuanTerbaru()
    {
        return Peminjaman::with(['anggota', 'buku'])
            ->where('status', Peminjaman::DIAJUKAN)
            ->latest('id')
            ->limit(5)
            ->get();
    }
}
