<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        
        $daftarBuku = Buku::with('kategori')
            ->orderBy('id')
            ->get()
            ->map(fn (Buku $buku) => $this->formatBuku($buku));

        return view('buku.index', [
            'daftarBuku' => $daftarBuku,
        ]);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);
        $buku = $buku ? $this->formatBuku($buku) : null;

        return view('buku.detail', compact('buku'));
    }

    // Bentuk datanya disamakan dengan array lama, jadi View tidak perlu diubah
    private function formatBuku(Buku $buku): array
    {
        return [
            'id'           => $buku->id,
            'judul'        => $buku->judul,
            'penulis'      => $buku->penulis,
            'tahun_terbit' => $buku->tahun_terbit,
            'kategori'     => $buku->kategori->nama,
        ];
    }
}