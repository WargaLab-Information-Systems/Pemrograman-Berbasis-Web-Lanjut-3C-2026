<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;

class RekapController extends Controller
{
    // Rekap jumlah buku per kategori
    public function bukuPerKategori(): JsonResponse
    {
        $data = Kategori::withCount('bukus')->get();

        return response()->json([
            'success' => true,
            'message' => 'Rekap buku per kategori berhasil ditampilkan.',
            'data' => $data,
        ], 200);
    }

    // Rekap jumlah peminjaman per buku
    public function peminjamanPerBuku(): JsonResponse
    {
        $data = Buku::withCount('peminjaman')->get();

        return response()->json([
            'success' => true,
            'message' => 'Rekap peminjaman per buku berhasil ditampilkan.',
            'data' => $data,
        ], 200);
    }
}
