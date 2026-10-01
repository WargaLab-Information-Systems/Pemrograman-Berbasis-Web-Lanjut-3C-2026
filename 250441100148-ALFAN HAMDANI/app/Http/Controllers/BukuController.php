<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

// use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Data buku sementara (Dummy Data)
    private $bukuList = [
        [
            'id' => 1,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'tahun_terbit' => 2005,
            'kategori' => 'Novel / Fiksi'
        ],
        [
            'id' => 2,
            'judul' => 'Bumi',
            'penulis' => 'Tere Liye',
            'tahun_terbit' => 2014,
            'kategori' => 'Fantasy'
        ],
        [
            'id' => 3,
            'judul' => 'Clean Code',
            'penulis' => 'Robert C. Martin',
            'tahun_terbit' => 2008,
            'kategori' => 'Teknologi'
        ],
        [
            'id' => 4,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'tahun_terbit' => 2018,
            'kategori' => 'Self-Development'
        ],
        [
            'id' => 5,
            'judul' => 'Atomic Habits',
            'penulis' => 'James Clear',
            'tahun_terbit' => 2018,
            'kategori' => 'Self-Development'
        ],
    ];

    // Halaman Daftar Buku
    public function index()
    {
        $buku = $this->bukuList;
        return view('buku.index', compact('buku'));
    }

    // Halaman Detail Buku berdasarkan ID
    public function show($id)
    {
        // Cari buku berdasarkan ID
        $buku = collect($this->bukuList)->firstWhere('id', (int) $id);

        return view('buku.show', compact('buku'));
    }
}