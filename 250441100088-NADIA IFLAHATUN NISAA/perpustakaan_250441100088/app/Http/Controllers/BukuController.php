<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')
            ->orderBy('id')
            ->get()
            ->map(fn (Buku $item) => $this->bentukData($item))
            ->all();

        return view('buku.index', [
            'buku' => $buku
        ]);
    }

    public function show($id)
    {
        $item = Buku::with('kategori')->find($id);

        return view('buku.show', [
            'buku' => $item ? $this->bentukData($item) : null
        ]);
    }

    private function bentukData(Buku $buku): array
    {
        return [
            'id'       => $buku->id,
            'judul'    => $buku->judul,
            'penulis'  => $buku->penulis,
            'tahun'    => $buku->tahun_terbit,
            'kategori' => $buku->kategori->nama ?? '-',
            'sampul'   => 'sampul/' . Str::slug(Str::before($buku->judul, ':')) . '.jpg',
        ];
    }
}
