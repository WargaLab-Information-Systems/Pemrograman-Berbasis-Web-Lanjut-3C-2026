<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // GET: Menampilkan daftar buku
    public function index(Request $request): JsonResponse
    {
        $query = Buku::with('kategori')
            ->withCount('peminjaman');

        // Pencarian berdasarkan judul atau penulis
        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan tahun terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $buku = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'Daftar buku berhasil ditampilkan.',
            'data' => BukuResource::collection($buku->items()),
            'meta' => [
                'current_page' => $buku->currentPage(),
                'last_page' => $buku->lastPage(),
                'per_page' => $buku->perPage(),
                'total' => $buku->total(),
            ],
            'links' => [
                'first' => $buku->url(1),
                'last' => $buku->url($buku->lastPage()),
                'prev' => $buku->previousPageUrl(),
                'next' => $buku->nextPageUrl(),
            ],
        ], 200);
    }

    // POST: Menambahkan buku menggunakan kategori pilihan pengguna
    public function store(StoreBukuRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Kategori diambil dari pilihan form, bukan dari judul
        $data['kategori_id'] = (int) $data['kategori_id'];

        $buku = Buku::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan.',
            'data' => new BukuResource($buku->load('kategori')),
        ], 201);
    }

    // GET: Menampilkan detail satu buku
    public function show(string $id): JsonResponse
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail buku berhasil ditampilkan.',
            'data' => new BukuResource($buku),
        ], 200);
    }

    // PUT/PATCH: Mengubah data buku
    public function update(
        UpdateBukuRequest $request,
        string $id
    ): JsonResponse {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $data = $request->validated();

        // Jika kategori dipilih, simpan kategori yang dipilih
        if (isset($data['kategori_id'])) {
            $data['kategori_id'] = (int) $data['kategori_id'];
        }

        $buku->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui.',
            'data' => new BukuResource($buku->fresh()->load('kategori')),
        ], 200);
    }

    // DELETE: Menghapus buku
    public function destroy(string $id): JsonResponse
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus.',
        ], 200);
    }
}
