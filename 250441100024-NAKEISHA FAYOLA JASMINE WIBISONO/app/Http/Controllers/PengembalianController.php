<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/** Pencatatan pengembalian buku oleh admin dan petugas. */
class PengembalianController extends Controller
{
    public function index(): View
    {
        $daftar = Peminjaman::with(['anggota', 'buku'])
            ->whereIn('status', [Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN])
            ->latest('id')
            ->paginate(15);

        return view('pengembalian.index', compact('daftar'));
    }

    public function store(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIPINJAM) {
            return back()->with('error', 'Hanya buku yang sedang dipinjam yang bisa dicatat pengembaliannya.');
        }

        $peminjaman->update([
            'status' => Peminjaman::DIKEMBALIKAN,
            'tanggal_kembali' => now()->toDateString(),
        ]);

        return back()->with('sukses', 'Pengembalian buku berhasil dicatat.');
    }
}
