<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Peminjaman MILIK SENDIRI. Semua data difilter berdasarkan anggota milik
 * akun yang sedang login, bukan berdasarkan input dari pengguna.
 */
class PeminjamanSayaController extends Controller
{
    public function status(Request $request): View
    {
        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $this->idAnggota($request))
            ->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM])
            ->latest('id')
            ->get();

        return view('peminjaman.saya', [
            'judul'       => 'Status Peminjaman Saya',
            'keterangan'  => 'Pengajuan yang menunggu persetujuan dan buku yang sedang Anda pinjam.',
            'peminjamans' => $peminjamans,
        ]);
    }

    public function riwayat(Request $request): View
    {
        $peminjamans = Peminjaman::with('buku.kategori')
            ->where('anggota_id', $this->idAnggota($request))
            ->latest('id')
            ->get();

        return view('peminjaman.saya', [
            'judul'       => 'Riwayat Peminjaman Saya',
            'keterangan'  => 'Seluruh peminjaman atas nama Anda.',
            'peminjamans' => $peminjamans,
        ]);
    }

    public function show(Peminjaman $peminjaman): View
    {
        Gate::authorize('view', $peminjaman);   
        $peminjaman->load(['buku.kategori', 'anggota']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function store(Request $request, Buku $buku): RedirectResponse
    {
        $anggota = $request->user()->anggota;   

        if (! $anggota) {
            abort(403, 'Akun Anda belum terhubung dengan data anggota.');
        }

        $masihAktif = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM])
            ->exists();

        if ($masihAktif) {
            return back()->with('error', 'Anda masih memiliki pengajuan atau peminjaman aktif untuk buku ini.');
        }

        $peminjaman = new Peminjaman([
            'anggota_id'     => $anggota->id,
            'buku_id'        => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);
        $peminjaman->status = Peminjaman::DIAJUKAN;  
        $peminjaman->save();

        return redirect()->route('peminjaman.status')
            ->with('sukses', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }

    private function idAnggota(Request $request): int
    {
        return $request->user()->anggota?->id ?? 0;
    }
}
