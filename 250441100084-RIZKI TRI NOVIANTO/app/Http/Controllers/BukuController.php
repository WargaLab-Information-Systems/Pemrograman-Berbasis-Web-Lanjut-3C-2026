<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BukuController extends Controller
{

    private function dataBuku(): array
    {
        return [
            ['id' => 1, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun_terbit' => 2005, 'kategori' => 'Novel', 'gambar' => 'laskar-pelangi.jpg'],
            ['id' => 2, 'judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'tahun_terbit' => 1980, 'kategori' => 'Sastra', 'gambar' => 'bumi-manusia.jpg'],
            ['id' => 3, 'judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri', 'gambar' => 'filosofi-teras.jpg'],
            ['id' => 4, 'judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri', 'gambar' => 'atomic-habits.jpg'],
            ['id' => 5, 'judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'tahun_terbit' => 2008, 'kategori' => 'Teknologi', 'gambar' => 'clean-code.jpg'],
            ['id' => 6, 'judul' => 'Sapiens', 'penulis' => 'Yuval Noah Harari', 'tahun_terbit' => 2011, 'kategori' => 'Sejarah', 'gambar' => 'sapiens.jpg'],
        ];
    }

    // Halaman Daftar Buku
    public function index(): View
    {
        $daftarBuku = $this->dataBuku();

        return view('buku.index', compact('daftarBuku'));
    }

    // Halaman Detail Buku 
    public function show(int $id): View
    {
        $buku = collect($this->dataBuku())->firstWhere('id', $id); 

        return view('buku.show', ['buku' => $buku, 'id' => $id]);
    }
}
