<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\View\View;

class RiwayatPeminjamanController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman milik pengguna
     * yang sedang login.
     */
    public function index(): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Ambil Data Anggota Milik Pengguna
        |--------------------------------------------------------------------------
        */

        $anggota = $user->anggota;

        /*
        |--------------------------------------------------------------------------
        | Ambil Riwayat Peminjaman Milik Sendiri
        |--------------------------------------------------------------------------
        |
        | Data difilter berdasarkan anggota_id milik pengguna yang sedang
        | login sehingga pengguna tidak dapat melihat riwayat milik
        | pengguna lain.
        |
        */

        $peminjamans = $anggota
            ? Peminjaman::with([
                'buku',
                'buku.kategori',
            ])
                ->where('anggota_id', $anggota->id)
                ->latest('id')
                ->get()
            : collect();

        return view(
            'riwayat-peminjaman.index',
            compact('peminjamans')
        );
    }
}