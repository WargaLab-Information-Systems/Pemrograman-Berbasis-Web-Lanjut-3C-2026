<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        // Mengambil data dari database beserta nama kategorinya
        $bukuList = Buku::with('kategori')->get()->map(function ($buku) {
            return [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'tahun_terbit' => $buku->tahun_terbit,
                'kategori' => $buku->kategori->nama ?? 'Tidak Ada',
            ];
        });

        return view('buku.index', ['bukuList' => $bukuList]);
    }

    public function show($id)
    {
        // Cari data di database
        $bukuData = Buku::with('kategori')->find($id);

        $buku = null;
        if ($bukuData) {
            $buku = [
                'id' => $bukuData->id,
                'judul' => $bukuData->judul,
                'penulis' => $bukuData->penulis,
                'tahun_terbit' => $bukuData->tahun_terbit,
                'kategori' => $bukuData->kategori->nama ?? 'Tidak Ada',
            ];
        }

        return view('buku.show', compact('buku'));
    }
}