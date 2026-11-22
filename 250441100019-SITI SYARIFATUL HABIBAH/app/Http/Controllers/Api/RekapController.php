<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class RekapController extends Controller
{
    public function bukuPerKategori(): JsonResponse
    {
        $data = Kategori::query()
            ->withCount('bukus')
            ->orderBy('nama')
            ->get()
            ->map(fn ($k) => [
                'kategori_id'   => $k->id,
                'nama_kategori' => $k->nama,
                'jumlah_buku'   => $k->bukus_count,
            ]);

        return response()->json(['message' => 'Rekap buku per kategori', 'data' => $data], 200);
    }

    public function peminjamanPerBuku(): JsonResponse
    {
        $data = Buku::query()
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->get()
            ->map(fn ($b) => [
                'buku_id'          => $b->id,
                'judul'            => $b->judul,
                'total_peminjaman' => $b->peminjamans_count,
            ]);

        return response()->json(['message' => 'Rekap peminjaman per buku', 'data' => $data], 200);
    }
}