<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan perpustakaan.
     */
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Ringkasan Data
        |--------------------------------------------------------------------------
        */

        $totalBuku = Buku::count();

        $totalAnggota = Anggota::count();

        $totalPeminjaman = Peminjaman::count();

        $totalMenunggu = Peminjaman::where(
            'status',
            'menunggu'
        )->count();

        $totalDipinjam = Peminjaman::where(
            'status',
            'dipinjam'
        )->count();

        $totalDikembalikan = Peminjaman::where(
            'status',
            'dikembalikan'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Data Peminjaman
        |--------------------------------------------------------------------------
        |
        | Data ini digunakan untuk menampilkan laporan transaksi
        | peminjaman beserta anggota dan buku yang berkaitan.
        |
        */

        $peminjamans = Peminjaman::with([
            'anggota',
            'buku',
        ])
            ->latest('id')
            ->get();


        return view(
            'laporan.index',
            compact(
                'totalBuku',
                'totalAnggota',
                'totalPeminjaman',
                'totalMenunggu',
                'totalDipinjam',
                'totalDikembalikan',
                'peminjamans'
            )
        );
    }
}