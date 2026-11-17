<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource; 
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku (dengan pencarian dan pagination).
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->withCount('peminjaman')->latest();

        if ($request->has('cari')) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        $buku = $query->paginate(10);

        return BukuResource::collection($buku)->additional([
            'status' => 'success'
        ]);
    }

    /**
     * Menyimpan data buku baru.
     */
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => new BukuResource($buku)
        ], 201);
    }

    /**
     * Menampilkan detail satu buku berdasarkan ID.
     */
    public function show(int $id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        return (new BukuResource($buku))->additional([
            'status' => 'success'
        ]);
    }

    /**
     * Mengubah data buku yang sudah ada.
     */
    public function update(UpdateBukuRequest $request, int $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku berhasil diubah',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    /**
     * Menghapus buku.
     */
    public function destroy(int $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'message' => 'not found'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

    /**
     * Rekap jumlah buku per kategori (menggunakan model Kategori).
     */
    public function rekapBukuPerKategori()
    {
        $data = Kategori::withCount('buku')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ], 200);
    }

    /**
     * Rekap jumlah buku per kategori menggunakan join dan groupBy.
     */
    public function bukuPerKategori()
    {
        $rekap = Buku::select('kategoris.nama as kategori', DB::raw('count(bukus.id) as jumlah_buku'))
            ->join('kategoris', 'bukus.kategori_id', '=', 'kategoris.id')
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rekap
        ], 200);
    }

    /**
     * Rekap jumlah peminjaman per buku.
     */
    public function rekapPeminjamanPerBuku()
    {
        $data = Buku::withCount('peminjaman')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ], 200);
    }

    /**
     * Alias peminjaman per buku.
     */
    public function peminjamanPerBuku()
    {
        $rekap = Buku::select('id', 'judul', 'penulis', 'tahun_terbit')
            ->withCount('peminjaman')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rekap
        ], 200);
    }
}