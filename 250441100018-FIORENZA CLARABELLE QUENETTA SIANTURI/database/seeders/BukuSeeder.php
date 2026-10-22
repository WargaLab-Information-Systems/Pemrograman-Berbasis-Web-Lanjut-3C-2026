<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $buku = [
            ['judul' => 'Dilan 1990', 'penulis' => 'Pidi Baiq', 'tahun_terbit' => 2014, 'kategori' => 'Romance'],
            ['judul' => 'Hujan', 'penulis' => 'Tere Liye', 'tahun_terbit' => 2016, 'kategori' => 'Drama'],
            ['judul' => 'Mariposa', 'penulis' => 'Luluk HF', 'tahun_terbit' => 2018, 'kategori' => 'Romance'],
            ['judul' => 'Perahu Kertas', 'penulis' => 'Dee Lestari', 'tahun_terbit' => 2009, 'kategori' => 'Romance'],
            ['judul' => 'Laut Bercerita', 'penulis' => 'Leila S. Chudori', 'tahun_terbit' => 2017, 'kategori' => 'Novel'],
            ['judul' => 'Bumi', 'penulis' => 'Tere Liye', 'tahun_terbit' => 2014, 'kategori' => 'Fantasi'],
            ['judul' => 'Negeri 5 Menara', 'penulis' => 'Ahmad Fuadi', 'tahun_terbit' => 2009, 'kategori' => 'Novel'],
            ['judul' => 'Nanti Kita Cerita tentang Hari Ini', 'penulis' => 'Marchella FP', 'tahun_terbit' => 2018, 'kategori' => 'Drama'],
            ['judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri'],
            ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'tahun_terbit' => 2018, 'kategori' => 'Pengembangan Diri'],
        ];

        foreach ($buku as $data) {
            $kategori = Kategori::where('nama', $data['kategori'])->first();

            if ($kategori) {
                Buku::factory()->create([
                    'kategori_id' => $kategori->id,
                    'judul' => $data['judul'],
                    'penulis' => $data['penulis'],
                    'tahun_terbit' => $data['tahun_terbit'],
                ]);
            }
        }
    }
}