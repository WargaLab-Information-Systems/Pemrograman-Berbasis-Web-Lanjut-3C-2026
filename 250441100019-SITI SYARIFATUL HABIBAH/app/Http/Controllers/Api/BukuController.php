<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $bukus = Buku::query()
            ->with('kategori')                 // eager loading
            ->withCount('peminjamans')         // jumlah peminjaman per buku
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->query('cari');
                $q->where(function ($w) use ($cari) {
                    $w->where('judul', 'like', "%{$cari}%")
                      ->orWhere('penulis', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->query('kategori')))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_terbit', $request->query('tahun')))
            ->latest()                         // terbaru lebih dulu
            ->latest('id')                     // pembeda jika created_at sama
            ->paginate(10)
            ->withQueryString();               // query string terbawa saat pindah halaman

        return response()->json([
            'message' => 'Daftar buku berhasil diambil',
            'data'    => $bukus->items(),
            'meta'    => [
                'current_page' => $bukus->currentPage(),
                'per_page'     => $bukus->perPage(),
                'total'        => $bukus->total(),
                'last_page'    => $bukus->lastPage(),
                'from'         => $bukus->firstItem(),
                'to'           => $bukus->lastItem(),
            ],
            'links'   => [
                'first' => $bukus->url(1),
                'last'  => $bukus->url($bukus->lastPage()),
                'prev'  => $bukus->previousPageUrl(),
                'next'  => $bukus->nextPageUrl(),
            ],
        ], 200);
    }

    public function store(StoreBukuRequest $request): JsonResponse
    {
        $buku = Buku::create($request->validated());   // BUKAN $request->all()
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $buku,
        ], 201);
    }

    public function show(Buku $buku): JsonResponse
    {
        $buku->load(['kategori', 'peminjamans.anggota']);

        return response()->json([
            'message' => 'Detail buku berhasil diambil',
            'data'    => $buku,
        ], 200);
    }

    public function update(UpdateBukuRequest $request, Buku $buku): JsonResponse
    {
        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => $buku->fresh('kategori'),
        ], 200);
    }

    public function destroy(Buku $buku): JsonResponse
    {
        // Tabel peminjamans punya foreign key ke bukus (tanpa cascade).
        // Tanpa pengecekan ini, menghapus buku yang pernah dipinjam jadi error 500.
        if ($buku->peminjamans()->exists()) {
            return response()->json([
                'message' => 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman',
            ], 409);
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }
}