<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Pengelolaan peminjaman oleh admin dan petugas. */
class PeminjamanController extends Controller
{
    public function index(): View
    {
        $daftar = Peminjaman::with(['anggota', 'buku'])
            ->latest('id')
            ->paginate(15);

        return view('peminjaman.index', compact('daftar'));
    }

    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang menunggu yang bisa disetujui.');
        }

        $peminjaman->update([
            'status' => Peminjaman::DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('sukses', 'Pengajuan peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang menunggu yang bisa ditolak.');
        }

        $peminjaman->update(['status' => Peminjaman::DITOLAK]);

        return back()->with('sukses', 'Pengajuan peminjaman ditolak.');
    }
}
