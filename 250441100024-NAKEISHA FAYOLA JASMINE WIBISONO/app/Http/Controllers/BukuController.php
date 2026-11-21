<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        // Tabel diisi lewat Fetch API, jadi tidak ada data yang dikirim dari sini.
        return view('buku.index');
    }

    public function show($id)
    {
        $data = Buku::with('kategori')->find((int) $id);
        $buku = $data ? $this->format($data) : null;

        return view('buku.show', compact('buku'));
    }

    private function format(Buku $b): array
    {
        return [
            'id'       => $b->id,
            'judul'    => $b->judul,
            'penulis'  => $b->penulis,
            'tahun'    => $b->tahun_terbit,
            'kategori' => $b->kategori->nama,
        ];
    }
}
