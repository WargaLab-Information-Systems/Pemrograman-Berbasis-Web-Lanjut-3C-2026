<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Kelola pengembalian (permission: kelola pengembalian).
 * Mencatat pengembalian butuh permission: mencatat pengembalian buku.
 */
class PengembalianController extends Controller
{
    public function index(): View
    {
        // Yang masih dipinjam tampil di atas (perlu dicatat pengembaliannya)
        $peminjamans = Peminjaman::with(['anggota', 'buku'])
            ->whereIn('status', [Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN])
            ->orderByRaw("CASE WHEN status = 'dipinjam' THEN 0 ELSE 1 END")
            ->latest('id')
            ->paginate(15);

        return view('kelola.peminjaman', [
            'judul'       => 'Kelola Pengembalian',
            'mode'        => 'pengembalian',
            'peminjamans' => $peminjamans,
        ]);
    }

    public function store(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIPINJAM) {
            return back()->with('error', 'Hanya buku yang berstatus "dipinjam" yang dapat dicatat pengembaliannya.');
        }

        $peminjaman->status = Peminjaman::DIKEMBALIKAN;
        $peminjaman->tanggal_kembali = now()->toDateString();
        $peminjaman->save();

        return back()->with('sukses', 'Pengembalian buku berhasil dicatat.');
    }
}
