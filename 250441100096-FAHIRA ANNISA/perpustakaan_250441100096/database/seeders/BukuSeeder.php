<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;
use App\Models\Kategori;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriNovel = Kategori::firstOrCreate(['nama' => 'Novel'])->id;
        $kategoriSains = Kategori::firstOrCreate(['nama' => 'Sains'])->id;
        $kategoriBisnis = Kategori::firstOrCreate(['nama' => 'Bisnis'])->id;
        $kategoriTeknologi = Kategori::firstOrCreate(['nama' => 'Teknologi'])->id;

        $databuku = [
            [
                'kode_buku' => 'B001',
                'judul' => 'Hujan',
                'penulis' => 'Tere Liye',
                'tahun_terbit' => 2016,
                'kategori_id' => $kategoriNovel,
                'gambar' => '/images/foto1.jpg'
            ],
            [
                'kode_buku' => 'B002',
                'judul' => 'Negeri 5 Menara',
                'penulis' => 'A. Fuadi',
                'tahun_terbit' => 2009,
                'kategori_id' => $kategoriNovel,
                'gambar' => '/images/foto2.jpg'
            ],
            [
                'kode_buku' => 'B003',
                'judul' => 'Rahasia Alam Semesta',
                'penulis' => 'Stephen Hawking',
                'tahun_terbit' => 2020,
                'kategori_id' => $kategoriSains,
                'gambar' => '/images/foto3.jpg'
            ],
            [
                'kode_buku' => 'B004',
                'judul' => 'Perahu Kertas',
                'penulis' => 'Dee Lestari',
                'tahun_terbit' => 2009,
                'kategori_id' => $kategoriNovel,
                'gambar' => '/images/foto4.png'
            ],
            [
                'kode_buku' => 'B005',
                'judul' => 'Pengantar Bisnis',
                'penulis' => 'Buchari Alma',
                'tahun_terbit' => 2018,
                'kategori_id' => $kategoriBisnis,
                'gambar' => '/images/foto5.jpeg'
            ],
            [
                'kode_buku' => 'B006',
                'judul' => 'Pengantar Sistem Informasi',
                'penulis' => "James A. O'Brien",
                'tahun_terbit' => 2011,
                'kategori_id' => $kategoriTeknologi,
                'gambar' => '/images/foto6.jpg'
            ],
            [
                'kode_buku' => 'B007',
                'judul' => 'Pulang',
                'penulis' => 'Tere Liye',
                'tahun_terbit' => 2015,
                'kategori_id' => $kategoriNovel,
                'gambar' => '/images/foto7.jpeg'
            ],
            [
                'kode_buku' => 'B008',
                'judul' => '3276 mdpl',
                'penulis' => 'Nurwina Sari',
                'tahun_terbit' => 2024,
                'kategori_id' => $kategoriNovel,
                'gambar' => '/images/3276mdpl.jpg'
            ]
        ];

        foreach ($databuku as $buku) {
            Buku::create($buku);
        }
    }
}