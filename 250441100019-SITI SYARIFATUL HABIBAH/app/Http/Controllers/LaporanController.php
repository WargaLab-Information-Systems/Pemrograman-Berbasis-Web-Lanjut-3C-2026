<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\View\View;

// Laporan perpustakaan (permission: lihat laporan perpustakaan)
class LaporanController extends Controller
{
    public function index(): View
    {
        $ringkasan = [
            'Total Buku'           => Buku::count(),
            'Total Anggota'        => Anggota::count(),
            'Total Peminjaman'     => Peminjaman::count(),
            'Menunggu Persetujuan' => Peminjaman::where('status', Peminjaman::DIAJUKAN)->count(),
            'Sedang Dipinjam'      => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'Sudah Dikembalikan'   => Peminjaman::where('status', Peminjaman::DIKEMBALIKAN)->count(),
            'Ditolak'              => Peminjaman::where('status', Peminjaman::DITOLAK)->count(),
        ];

        $terpopuler = Buku::with('kategori')
            ->withCount(['peminjamans as total_dipinjam' => function ($q) {
                $q->whereIn('status', [Peminjaman::DIPINJAM, Peminjaman::DIKEMBALIKAN]);
            }])
            ->orderByDesc('total_dipinjam')
            ->orderBy('judul')
            ->limit(5)
            ->get();

        $perKategori = Kategori::withCount('bukus')->orderBy('nama')->get();

        return view('laporan.index', compact('ringkasan', 'terpopuler', 'perKategori'));
    }
}
