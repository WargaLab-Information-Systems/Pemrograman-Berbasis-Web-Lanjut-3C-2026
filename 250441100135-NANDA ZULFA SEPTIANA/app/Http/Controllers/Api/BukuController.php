<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->latest()->get();        
        return BukuResource::collection($buku);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|min:3',
            'penulis'      => 'required|string|min:3',
            'tahun_terbit' => 'required|integer|min:1901|max:2026',
        ]);

        $buku = Buku::create($request->all());

        return new BukuResource($buku);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|min:3',
            'penulis'      => 'required|string|min:3',
            'tahun_terbit' => 'required|integer|min:1901|max:2026',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return new BukuResource($buku);
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return response()->json(['message' => 'Buku berhasil dihapus']);
    }

    public function getKategori()
    {
        $kategoris = Kategori::all();
        
        return response()->json([
            'success' => true,
            'data' => $kategoris
        ]);
    }
    
}