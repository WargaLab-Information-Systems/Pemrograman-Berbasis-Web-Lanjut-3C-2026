<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    private function formatBuku(Buku $buku): array
    {
        return [
            'id'       => $buku->id,
            'judul'    => $buku->judul,
            'penulis'  => $buku->penulis,
            'tahun'    => $buku->tahun_terbit,
            'kategori' => $buku->kategori?->nama ?? '-',
        ];
    }

    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get(['id', 'nama']);

        return view('buku.index', compact('kategoris'));
    }

    public function show($id)
    {
        $data = Buku::with('kategori')->find($id);

        $detailBuku = $data ? $this->formatBuku($data) : null;

        return view('buku.show', compact('detailBuku'));
    }
}