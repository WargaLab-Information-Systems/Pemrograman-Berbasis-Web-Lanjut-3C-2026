<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    // Ubah model menjadi array dengan bentuk yang sama seperti data lama,
    // supaya view Modul 1 tidak perlu diubah sama sekali.
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
        $buku = Buku::with('kategori')
            ->orderBy('id')
            ->get()
            ->map(fn (Buku $item) => $this->formatBuku($item))
            ->all();

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $data = Buku::with('kategori')->find($id);

        $detailBuku = $data ? $this->formatBuku($data) : null;

        return view('buku.show', compact('detailBuku'));
    }
}