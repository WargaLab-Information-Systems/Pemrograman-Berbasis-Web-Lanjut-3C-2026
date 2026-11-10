<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index', [
            'totalBuku' => Buku::count(),
            'totalAnggota' => Anggota::count(),
            'statusCount' => Peminjaman::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'bukuPerKategori' => Kategori::withCount('bukus')->orderBy('nama')->get(),
            'peminjamanPerBuku' => Buku::withCount('peminjamans')->orderByDesc('peminjamans_count')->orderBy('judul')->limit(10)->get(),
            'sedangDipinjam' => Peminjaman::with(['anggota', 'buku'])
                ->where('status', Peminjaman::STATUS_DIPINJAM)->orderBy('tanggal_pinjam')->get(),
        ]);
    }
}
