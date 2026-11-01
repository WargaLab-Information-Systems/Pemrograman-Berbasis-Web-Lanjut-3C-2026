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

        if ($request->has('cari')) {
            $cari = $request->input('cari');
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->input('kategori'));
        }

        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->input('tahun'));
        }


        $bukus = $query->latest()->paginate(10)->appends($request->query());

        return response()->json([
            'status' => 'success',
            'data' => $bukus
        ], 200);
    }

    public function store(StoreBukuRequest $request)
    {
        
        $buku = Buku::create($request->validated());
        $buku->load('kategori');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (! $buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $buku
        ], 200);
    }

    public function update(UpdateBukuRequest $request, $id)
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'not found'
            ], 404);
        }

        $buku->update($request->validated());
        $buku->load('kategori');

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (! $buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'not found'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

   
    public function rekapBukuPerKategori()
    {
        $rekap = DB::table('buku')
            ->join('kategoris', 'buku.kategori_id', '=', 'kategoris.id')
            ->select(
                'kategoris.id',
                'kategoris.nama as kategori',
                DB::raw('count(buku.id) as total_buku')
            )
            ->groupBy('kategoris.id', 'kategoris.nama')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap jumlah buku per kategori berhasil dimuat',
            'data' => $rekap
        ], 200);
    }

    public function rekapPeminjamanPerBuku()
    {
        $rekap = DB::table('buku')
            ->leftJoin('peminjamans', 'buku.id', '=', 'peminjamans.buku_id')
            ->select(
                'buku.id',
                'buku.judul',
                DB::raw('count(peminjamans.id) as total_peminjaman')
            )
            ->groupBy('buku.id', 'buku.judul')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Rekap total peminjaman per buku berhasil dimuat',
            'data' => $rekap
        ], 200);
    }
}