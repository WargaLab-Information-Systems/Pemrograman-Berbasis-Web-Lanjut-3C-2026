<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource; // 1. Import BukuResource di sini

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->withCount('peminjamans');

        if ($request->has('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%')
                  ->orWhere('penulis', 'like', '%' . $request->cari . '%');
        }
        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $buku = $query->latest()->paginate(10)->withQueryString();
        
        // 2. Bungkus data paginasi dengan BukuResource::collection
        return BukuResource::collection($buku);
    }

    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());
        
        // 3. Kembalikan respons menggunakan BukuResource
        return new BukuResource($buku->load('kategori'));
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjamans'])->find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        
        // 4. Kembalikan detail data buku berbasis BukuResource
        return new BukuResource($buku);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        
        $buku->update($request->validated());
        
        // 5. Kembalikan hasil pembaruan dengan BukuResource
        return new BukuResource($buku->load('kategori'));
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);
        
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }
        
        $buku->delete();
        return response()->json(['message' => 'Buku berhasil dihapus'], 200);
    }

    public function bukuPerKategori()
    {
        $rekap = Buku::selectRaw('kategori_id, count(*) as jumlah_buku')
            ->with('kategori')
            ->groupBy('kategori_id')
            ->get();
            
        return response()->json($rekap, 200);
    }

    public function peminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjamans')->get();
        return response()->json($rekap, 200);
    }
}