<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = Kategori::pluck('id', 'nama');

        $data = [
            ['Laskar Pelangi', 'Andrea Hirata', 2005, 'Novel'],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', 1980, 'Novel'],
            ['Negeri 5 Menara', 'Ahmad Fuadi', 2009, 'Novel'],
            ['Saman', 'Ayu Utami', 1998, 'Sastra'],
            ['Pulang', 'Leila S. Chudori', 2012, 'Sastra'],
            ['Tempurung', 'Oka Rusmini', 2010, 'Sastra'],
            ['Cantik Itu Luka', 'Eka Kurniawan', 2002, 'Fiksi'],
            ['Perahu Kertas', 'Dee Lestari', 2009, 'Fiksi'],
            ['Supernova: Ksatria, Puteri, dan Bintang Jatuh', 'Dee Lestari', 2001, 'Fiksi'],
        ];

        $baris = array_map(fn ($d) => [
            'judul'        => $d[0],
            'penulis'      => $d[1],
            'tahun_terbit' => $d[2],
            'kategori_id'  => $kategori[$d[3]],
        ], $data);

        Buku::factory()
            ->count(count($baris))
            ->sequence(...$baris)
            ->create();
    }
}
