<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::factory()->create([
            'nama' => 'Fiksi',
        ]);

        Kategori::factory()->create([
            'nama' => 'Nonfiksi',
        ]);

        Kategori::factory()->create([
            'nama' => 'Pengembangan Diri',
        ]);
    }
}