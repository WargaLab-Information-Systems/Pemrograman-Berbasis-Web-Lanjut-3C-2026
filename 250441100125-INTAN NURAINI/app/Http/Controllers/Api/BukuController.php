<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{

    public function index(Request $request)
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans');

        // A. Pencarian berdasarkan judul atau penulis
        if ($request->filled('cari')) {
            $cari = $request->input('cari');

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // B. Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where(
                'kategori_id',
                $request->input('kategori')
            );
        }

        // C. Filter berdasarkan tahun terbit
        if ($request->filled('tahun')) {
            $query->where(
                'tahun_terbit',
                $request->input('tahun')
            );
        }

        // D. Urutkan data terbaru dan tampilkan 10 data per halaman
        $bukus = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return response()->json($bukus, 200);
    }


    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $buku,
        ], 201);
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])
            ->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => $buku,
        ], 200);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $buku->update($request->validated());

        $buku->load('kategori');
        $buku->loadCount('peminjamans');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => $buku,
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}