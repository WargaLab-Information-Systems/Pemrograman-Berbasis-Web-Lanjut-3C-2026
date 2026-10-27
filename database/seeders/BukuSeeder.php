<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
    {
        $DataBuku = [
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'tahun_terbit' => 2005,
                'kategori' => 'Novel/Fiksi',
                'cover' => 'images/laskarpelangi.jpg',
            ],
            [
                'judul' => 'Laut Bercerita',
                'penulis' => 'Leila S. Chudori',
                'tahun_terbit' => 2007,
                'kategori' => 'Novel/Fiksi',
                'cover' => 'images/lautbercerita.jpg',
            ],
            [
                'judul' => 'Seporsi Mie Ayam Sebelum Mati',
                'penulis' => 'Khoirul Trian',
                'tahun_terbit' => 2024,
                'kategori' => 'Novel/Fiksi',
                'cover' => 'images/seporsimieayam.jpg',
            ],
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'tahun_terbit' => 2018,
                'kategori' => 'Pengembangan Diri/Non-Fiksi',
                'cover' => 'images/filosofiteras.png',
            ],
            [
                'judul' => 'Berpayung Tuhan',
                'penulis' => 'Jaquenza Eden',
                'tahun_terbit' => 2025,
                'kategori' => 'Novel/Fiksi',
                'cover' => 'images/berpayungtuhan.jpg',
            ],
            [
                'judul' => 'Hidup Begitu Indah Hanya Itu Yang Kita Punya',
                'penulis' => 'Dea Anugrah',
                'tahun_terbit' => 2019,
                'kategori' => 'Novel/Fiksi',
                'cover' => 'images/hidupbbegituindah.jpeg',
            ],
            [
                'judul' => 'Sabar Paling Dalam',
                'penulis' => 'Fajar Sulaiman',
                'tahun_terbit' => 2022,
                'kategori' => 'Novel/Fiksi',
                'cover' => 'images/sabarpalingdalam.jpeg',
            ],
            [
                'judul' => 'Gesture',
                'penulis' => 'Zaka Putra Ramdani',
                'tahun_terbit' => 2021,
                'kategori' => 'Pengembangan Diri/Non-Fiksi',
                'cover' => 'images/gesture.jpeg',
            ],
        ];

        foreach ($DataBuku as $item) {
            $kategori = Kategori::firstOrCreate(['nama' => $item['kategori']]);

            Buku::create([
                'kategori_id'  => $kategori->id,
                'judul'        => $item['judul'],
                'penulis'      => $item['penulis'],
                'tahun_terbit' => $item['tahun_terbit'],
                'cover'        => $item['cover'],
            ]);
        }
    }
}