<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->get();

        $bukuList = $bukus->map(function ($buku) {
            return [
                'id'           => $buku->id,
                'judul'        => $buku->judul,
                'penulis'      => $buku->penulis,
                'tahun_terbit' => $buku->tahun_terbit,
                'kategori'     => $buku->kategori ? $buku->kategori->nama : '-',
                'kategori_id'  => $buku->kategori_id,
                ];
        });

        return view('buku.index', compact('bukuList'));
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
}