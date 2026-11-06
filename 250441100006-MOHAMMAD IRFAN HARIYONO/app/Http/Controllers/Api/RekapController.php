<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori; // Pastikan model Kategori sudah di-import
use Illuminate\Http\Request;

class RekapController extends Controller
{
    // Endpoint: GET /api/rekap/buku-per-kategori
    public function bukuPerKategori()
    {
        // Menghitung jumlah buku per kategori menggunakan withCount
        $data = Kategori::withCount('buku')->get();

        return response()->json([
            'message' => 'Rekap jumlah buku per kategori',
            'data' => $data
        ], 200);
    }

    // Endpoint: GET /api/rekap/pemminjaman-per-buku
    public function peminjamanPerBuku()
    {
        // Menghitung total peminjaman per buku menggunakan withCount
        // Pastikan relasi di model Buku bernama 'peminjaman'
        $data = Buku::withCount('peminjaman')->get();

        return response()->json([
            'message' => 'Rekap total peminjaman per buku',
            'data' => $data
        ], 200);
    }
}