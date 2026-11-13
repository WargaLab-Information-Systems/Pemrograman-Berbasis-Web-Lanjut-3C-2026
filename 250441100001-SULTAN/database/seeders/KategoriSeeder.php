<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
       
        $kategoris = [
            ['id' => 1, 'nama' => 'Teknologi'],
            ['id' => 2, 'nama' => 'Akademik'],
            ['id' => 3, 'nama' => 'Desain'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}