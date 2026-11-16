<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // public function index()
    // {
    //     $bukus = Buku::with('kategori')->get();

    //     $bukuList = $bukus->map(function ($buku) {
    //         return [
    //             'id'           => $buku->id,
    //             'judul'        => $buku->judul,
    //             'penulis'      => $buku->penulis,
    //             'tahun_terbit' => $buku->tahun_terbit,
    //             'kategori'     => $buku->kategori ? $buku->kategori->nama : '-',
    //             'kategori_id'  => $buku->kategori_id,
    //             ];
    //     });

    //     return view('buku.index', compact('bukuList'));
    // }
    public function index()
    {
        $bukus = Buku::with('kategori')->get();

        return view('buku.index', compact('bukus'));
    }

    public function detail($id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
        return view('buku.detail', [
            'buku' => null
          ]);
        }
        
        $bukuData = [
            'id'           => $buku->id,
            'judul'        => $buku->judul,
            'penulis'      => $buku->penulis,
            'tahun_terbit' => $buku->tahun_terbit,
            'kategori'     => $buku->kategori?->nama ?? '-',
            'cover'        => asset($buku->cover),
        ];

        return view('buku.detail', compact('buku'));
    }

    // public function cari(Request $request)
    // {
    //     $judul = $request->query('judul');

    //     // Celah: Variabel $judul digabungkan langsung (concatenation) ke SQL mentah
    //     $buku = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

    //     return response()->json($buku);
    // }

    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        // $buku = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

    // Pake Parameter Binding (?) buat misahkan logika SQL dan data
        $buku = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);

        return response()->json($buku);
    }

    public function destroy($id)
    {
        // Mengambil data buku berdasarkan ID dan menghapusnya
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dihapus!'
        ], 200);
    }
}