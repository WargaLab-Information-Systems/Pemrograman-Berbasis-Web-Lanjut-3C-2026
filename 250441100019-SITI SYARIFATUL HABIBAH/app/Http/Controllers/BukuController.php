<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BukuController as ApiBukuController;
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

        $bukuTerbaru = Buku::latest()->take(5)->get();

        return view('buku.index', compact('kategoris', 'bukuTerbaru'));
    }

    public function show($id)
    {
        $data = Buku::with('kategori')->find($id);

        $detailBuku = $data ? $this->formatBuku($data) : null;

        return view('buku.show', compact('detailBuku'));
    }


    public function destroy(Buku $buku, ApiBukuController $apiBukuController)
    {
        $apiBukuController->destroy($buku);

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil dihapus.');
    }
}