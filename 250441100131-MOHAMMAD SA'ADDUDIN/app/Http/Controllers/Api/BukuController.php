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
        $buku = Buku::with('kategori')             
            ->withCount('peminjaman')              
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('judul', 'like', '%' . $request->cari . '%')
                        ->orWhere('penulis', 'like', '%' . $request->cari . '%');
                });
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun_terbit', $request->tahun))
            ->latest()                           
            ->latest('id')                          
            ->paginate(10)                    
            ->withQueryString();                    

        return response()->json([
            'data'  => $buku->items(),
            'meta'  => [
                'current_page' => $buku->currentPage(),
                'per_page'     => $buku->perPage(),
                'total'        => $buku->total(),
                'last_page'    => $buku->lastPage(),
                'from'         => $buku->firstItem(),
                'to'           => $buku->lastItem(),
            ],
            'links' => [
                'first' => $buku->url(1),
                'last'  => $buku->url($buku->lastPage()),
                'prev'  => $buku->previousPageUrl(),
                'next'  => $buku->nextPageUrl(),
            ],
        ]);
    }

    
    public function store(StoreBukuRequest $request): JsonResponse
    {
        
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => $buku->load('kategori'),
        ], 201);
    }

   
    public function show(Buku $buku): JsonResponse
    {
        return response()->json([
            'data' => $buku->load(['kategori', 'peminjaman']),
        ]);
    }

    public function update(UpdateBukuRequest $request, Buku $buku): JsonResponse
    {
        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data'    => $buku->load('kategori'),
        ]);
    }

    public function destroy(Buku $buku): JsonResponse
    {
        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ]);
    }
}
