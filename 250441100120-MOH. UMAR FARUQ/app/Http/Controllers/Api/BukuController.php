<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->withCount('peminjaman');

        if ($request->filled('cari')) {
            $cari = trim($request->cari);
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $buku = $query->latest()->paginate(10)->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $buku,
        ], 200);
    }

    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku,
        ], 201);
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (! $buku) {
            return response()->json([
                'success' => false,
                'message' => 'not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $buku,
        ], 200);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json([
                'success' => false,
                'message' => 'not found',
            ], 404);
        }

        $buku->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku,
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json([
                'success' => false,
                'message' => 'not found',
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus',
        ], 200);
    }

    public function rekapBukuPerKategori()
    {
        $rekap = DB::table('kategoris')
            ->leftJoin('bukus', 'kategoris.id', '=', 'bukus.kategori_id')
            ->select('kategoris.nama as nama_kategori', DB::raw('count(bukus.id) as total_buku'))
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rekap,
        ], 200);
    }

    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjaman')->get(['id', 'judul', 'peminjaman_count']);

        return response()->json([
            'success' => true,
            'data' => $rekap,
        ], 200);
    }
}
