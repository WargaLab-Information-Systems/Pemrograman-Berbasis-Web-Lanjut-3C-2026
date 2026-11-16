<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class BukuController extends Controller
{
    public function index(): View
    {
        return view('buku.index', [
            'bukuList' => $this->bukuSementara(),
        ]);
    }

    public function show(int $id): View
    {
        $buku = collect($this->bukuSementara())->firstWhere('id', $id);

        return view('buku.show', [
            'buku' => $buku,
        ]);
    }

    /**
     * @return list<array{id: int, judul: string, penulis: string, tahun_terbit: int, kategori: string}>
     */
    private function bukuSementara(): array
    {
        return [
            [
                'id' => 1,
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2005,
                'kategori' => 'Novel',
            ],
            [
                'id' => 2,
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1980,
                'kategori' => 'Sastra',
            ],
            [
                'id' => 3,
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun_terbit' => 2018,
                'kategori' => 'Pengembangan Diri',
            ],
            [
                'id' => 4,
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'Ahmad Fuadi',
                'tahun_terbit' => 2009,
                'kategori' => 'Novel',
            ],
            [
                'id' => 5,
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'tahun_terbit' => 2018,
                'kategori' => 'Pengembangan Diri',
            ],
        ];
    }
}
