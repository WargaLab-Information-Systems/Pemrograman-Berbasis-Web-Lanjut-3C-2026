<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    // Menampilkan halaman daftar buku
    public function index(Request $request)
    {
        $kategori = $request->query('category');

        // Mengambil daftar kategori
        $daftarKategori = Kategori::orderBy('nama')->get();

        // Mengambil data buku beserta kategorinya
        $query = Buku::with('kategori');

        // Memfilter buku berdasarkan kategori jika dipilih
        if ($kategori) {
            $query->whereHas('kategori', function ($q) use ($kategori) {
                $q->where('nama', $kategori);
            });
        }

        $daftarBuku = $query->get();

        return view('buku.index', compact(
            'kategori',
            'daftarKategori',
            'daftarBuku'
        ));
    }

    // Pencarian buku untuk pengujian SQL Injection A2
    public function search(Request $request)
    {
        $judul = $request->query('judul');

        // Menggunakan query binding untuk mencegah SQL Injection
        $hasil = DB::select('SELECT * FROM bukus WHERE judul = ?', [$judul]);

        return response()->json($hasil);
    }

    // Menampilkan detail satu buku
    public function show($id)
    {
        $item = Buku::with('kategori')->find($id);

        $bukuDitemukan = null;

        if ($item) {
            $bukuDitemukan = [
                'id' => $item->id,
                'title' => $item->judul,
                'author' => $item->penulis,
                'year' => $item->tahun_terbit,
                'category' => $item->kategori->nama,
                'image' => $item->image,
                'description' => $item->description,
            ];
        }

        return view('buku.detail', compact('bukuDitemukan'));
    }
}
