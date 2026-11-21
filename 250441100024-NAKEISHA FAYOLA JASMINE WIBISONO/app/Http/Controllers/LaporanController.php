<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(): View
    {
        $ringkasan = [
            'total_buku' => Buku::count(),
            'total_anggota' => Anggota::count(),
            'total_peminjaman' => Peminjaman::count(),
        ];

        $status = Peminjaman::select('status', DB::raw('count(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $populer = Buku::withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->limit(5)
            ->get();

        $perKategori = DB::table('kategoris')
            ->leftJoin('bukus', 'bukus.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama', DB::raw('COUNT(bukus.id) as jumlah_buku'))
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->get();

        return view('laporan.index', compact('ringkasan', 'status', 'populer', 'perKategori'));
    }
}
