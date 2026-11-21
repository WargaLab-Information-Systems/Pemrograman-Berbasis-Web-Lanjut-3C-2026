<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Menampilkan daftar buku
    public function index()
    {
        $buku = Buku::with('kategori')
            ->orderByDesc('id')
            ->get();

        return BukuResource::collection($buku);
    }

    // Menampilkan satu buku
    public function show(string $id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.'
            ], 404);
        }

        return new BukuResource($buku);
    }

    // Menambahkan buku
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $buku = Buku::create($validated);

        return (new BukuResource(
            $buku->load('kategori')
        ))->response()->setStatusCode(201);
    }

    // Mengubah buku
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'judul' => 'required|string|min:3|max:150',
            'penulis' => 'required|string|min:3|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $buku->update($validated);

        return new BukuResource(
            $buku->load('kategori')
        );
    }

    // Menghapus buku
    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus.'
        ], 200);
    }
}
