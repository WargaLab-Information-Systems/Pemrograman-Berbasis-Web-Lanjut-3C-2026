<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar: eager loading kategori, hitung jumlah peminjaman, dan urutkan terbaru
        $query = Buku::with('kategori')->withCount('peminjaman')->latest();

        // Filter pencarian berdasarkan judul atau penulis (menggunakan LIKE)
        if ($request->has('cari')) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // Filter berdasarkan kategori_id
        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan tahun terbit
        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Pagination 10 data per halaman dengan membawa query string
        $bukus = $query->paginate(10)->appends($request->all());

        return response()->json($bukus, 200);
    }

    public function store(StoreBukuRequest $request)
    {
        // Menyimpan data buku baru dengan mass assignment dari input yang sudah divalidasi
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }

    public function show($id)
    {
        // Menampilkan detail buku beserta relasi kategori dan peminjaman
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json([
            'message' => 'Detail buku',
            'data' => $buku
        ], 200);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        // Memperbarui data buku dengan input yang sudah divalidasi
        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}