<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Support\Anggotas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Fitur peminjaman milik pengguna yang sedang login.
 * Anggota SELALU diambil dari identitas user yang login, tidak pernah dari input request.
 */
class PeminjamanSayaController extends Controller
{
    /** Status peminjaman sendiri: yang masih diajukan atau sedang dipinjam. */
    public function status(Request $request): View
    {
        $anggota = Anggotas::untuk($request->user());

        $daftar = $anggota
            ? Peminjaman::with('buku')
                ->where('anggota_id', $anggota->id)
                ->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM])
                ->latest('id')
                ->get()
            : collect();

        return view('peminjaman-saya.status', compact('anggota', 'daftar'));
    }

    /** /riwayat-peminjaman: arahkan ke riwayat milik sendiri. */
    public function riwayatSaya(Request $request): View|RedirectResponse
    {
        $anggota = Anggotas::untuk($request->user());

        if ($anggota) {
            return redirect()->route('riwayat.show', $anggota);
        }

        return view('peminjaman-saya.riwayat', ['anggota' => null, 'daftar' => collect()]);
    }

    /**
     * /anggota/{anggota}/riwayat: dicek lewat Policy.
     * Siswa yang membuka riwayat anggota lain akan ditolak (403).
     */
    public function riwayat(Anggota $anggota): View
    {
        Gate::authorize('viewRiwayat', $anggota);

        $daftar = Peminjaman::with('buku')
            ->where('anggota_id', $anggota->id)
            ->latest('id')
            ->get();

        return view('peminjaman-saya.riwayat', compact('anggota', 'daftar'));
    }

    public function ajukan(Request $request, Buku $buku): RedirectResponse
    {
        $anggota = Anggotas::pastikan($request->user());

        if (! $anggota) {
            return back()->with('error', 'Akun Anda belum terhubung dengan data anggota. Hubungi admin.');
        }

        $sudahAda = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', [Peminjaman::DIAJUKAN, Peminjaman::DIPINJAM])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => Peminjaman::DIAJUKAN,
        ]);

        return redirect()->route('peminjaman.saya')->with('sukses', 'Pengajuan peminjaman berhasil dikirim.');
    }
}
