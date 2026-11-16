<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Romance',
            'Drama',
            'Novel',
            'Fantasi',
            'Pengembangan Diri',
        ];

        foreach ($kategori as $nama) {
            Kategori::factory()->create([
                'nama' => $nama,
            ]);
        }
    }
}