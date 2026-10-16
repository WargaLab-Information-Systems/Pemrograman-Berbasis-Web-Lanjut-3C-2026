<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $fiksi = Kategori::where('nama', 'Fiksi')->value('id');
        $nonfiksi = Kategori::where('nama', 'Nonfiksi')->value('id');
        $pengembanganDiri = Kategori::where('nama', 'Pengembangan Diri')->value('id');

        Buku::factory()->create([
            'kategori_id' => $pengembanganDiri,
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'tahun_terbit' => 2018,
        ]);

        Buku::factory()->create([
            'kategori_id' => $fiksi,
            'judul' => 'Edensor',
            'penulis' => 'Andrea Hirata',
            'tahun_terbit' => 2007,
        ]);

        Buku::factory()->create([
            'kategori_id' => $pengembanganDiri,
            'judul' => 'The Subtle Art of Not Giving a F*ck',
            'penulis' => 'Mark Manson',
            'tahun_terbit' => 2016,
        ]);

        Buku::factory()->create([
            'kategori_id' => $pengembanganDiri,
            'judul' => 'Ikigai: The Japanese Secret to a Long and Happy Life',
            'penulis' => 'Héctor García dan Francesc Miralles',
            'tahun_terbit' => 2016,
        ]);

        Buku::factory()->create([
            'kategori_id' => $pengembanganDiri,
            'judul' => 'The Courage to be Disliked',
            'penulis' => 'Ichiro Kishimi dan Fumitake Koga',
            'tahun_terbit' => 2013,
        ]);
    }
}