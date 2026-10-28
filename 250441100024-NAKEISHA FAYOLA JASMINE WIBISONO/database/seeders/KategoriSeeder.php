<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::factory()->count(3)->state(new Sequence(
            ['nama' => 'Fiksi'],
            ['nama' => 'Sejarah'],
            ['nama' => 'Sains'],
        ))->create();
    }
}
