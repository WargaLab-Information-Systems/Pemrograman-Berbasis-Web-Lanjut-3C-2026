<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    // GET /api/rekap/buku-per-kategori
    public function bukuPerKategori()
    {
        $data = DB::table('kategoris')
            ->leftJoin(
                'bukus',
                'kategoris.id',
                '=',
                'bukus.kategori_id'
            )
            ->select(
                'kategoris.id',
                'kategoris.nama'
            )
            ->selectRaw(
                'COUNT(bukus.id) as jumlah_buku'
            )
            ->groupBy(
                'kategoris.id',
                'kategoris.nama'
            )
            ->orderBy('kategoris.id')
            ->get();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    // GET /api/rekap/pemminjaman-per-buku
    public function peminjamanPerBuku()
    {
        $data = Buku::with('kategori')
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->get()
            ->map(function ($buku) {
                return [
                    'id' => $buku->id,
                    'judul' => $buku->judul,
                    'penulis' => $buku->penulis,
                    'kategori' => $buku->kategori
                        ? $buku->kategori->nama
                        : null,
                    'jumlah_peminjaman' =>
                        $buku->peminjamans_count,
                ];
            });

        return response()->json([
            'data' => $data,
        ], 200);
    }
}
