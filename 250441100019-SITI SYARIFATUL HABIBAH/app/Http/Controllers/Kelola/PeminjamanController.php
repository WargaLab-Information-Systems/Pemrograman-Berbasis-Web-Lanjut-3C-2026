<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Kelola peminjaman (permission: kelola peminjaman).
 * Menyetujui / menolak pengajuan butuh permission: memproses persetujuan peminjaman.
 */
class PeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('kelola.peminjaman', [
            'judul'       => 'Kelola Peminjaman',
            'mode'        => 'peminjaman',
            'peminjamans' => $peminjamans,
        ]);
    }

    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang berstatus "diajukan" yang dapat disetujui.');
        }

        $peminjaman->status = Peminjaman::DIPINJAM;
        $peminjaman->tanggal_pinjam = now()->toDateString();
        $peminjaman->save();

        return back()->with('sukses', 'Pengajuan peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang berstatus "diajukan" yang dapat ditolak.');
        }

        $peminjaman->status = Peminjaman::DITOLAK;
        $peminjaman->save();

        return back()->with('sukses', 'Pengajuan peminjaman ditolak.');
    }
}
