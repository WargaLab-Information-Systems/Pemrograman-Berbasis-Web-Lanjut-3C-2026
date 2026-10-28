<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    // Menampilkan halaman daftar buku
    // Data buku diambil melalui Fetch API dari GET /api/buku
    public function index(Request $request)
    {
        $kategori = $request->query('category');

        // Daftar kategori digunakan untuk pilihan kategori pada form buku
        $daftarKategori = Kategori::orderBy('nama')->get();

        return view('buku.index', compact('kategori', 'daftarKategori'));
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