<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        Buku::factory()->count(5)->state(new Sequence(
            ['kategori_id' => 1, 'judul' => 'Hujan',                'penulis' => 'Tere Liye',             'tahun_terbit' => 2016],
            ['kategori_id' => 2, 'judul' => 'Bumi Manusia',         'penulis' => 'Pramoedya Ananta Toer', 'tahun_terbit' => 1980],
            ['kategori_id' => 1, 'judul' => 'Tentang Kamu',         'penulis' => 'Tere Liye',             'tahun_terbit' => 2016],
            ['kategori_id' => 1, 'judul' => 'Ronggeng Dukuh Paruk', 'penulis' => 'Ahmad Tohari',          'tahun_terbit' => 1982],
            ['kategori_id' => 1, 'judul' => 'Janji',                'penulis' => 'Tere Liye',             'tahun_terbit' => 2021],
        ))->create();
    }
}
