<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategori')
            ->withCount('peminjamans')
            ->latest();

        if ($request->filled('cari')) {
            $cari = $request->input('cari');

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where(
                'kategori_id',
                $request->input('kategori')
            );
        }

        if ($request->filled('tahun')) {
            $query->where(
                'tahun_terbit',
                $request->input('tahun')
            );
        }

        // Mengambil semua buku tanpa membatasi 10 buku per halaman.
        $bukus = $query->get();

        return response()->json([
            'message' => 'Daftar buku berhasil diambil.',
            'data' => BukuResource::collection($bukus),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => [
                'required',
                'integer',
                'between:1900,' . date('Y'),
            ],
        ]);

        $buku = Buku::create($validated)->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $buku = Buku::with('kategori')
            ->withCount('peminjamans')
            ->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail buku berhasil diambil.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'min:3', 'max:150'],
            'penulis' => ['required', 'string', 'min:3', 'max:100'],
            'tahun_terbit' => [
                'required',
                'integer',
                'between:1900,' . date('Y'),
            ],
        ]);

        $buku->update($validated);
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    public function destroy(string $id): JsonResponse
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
