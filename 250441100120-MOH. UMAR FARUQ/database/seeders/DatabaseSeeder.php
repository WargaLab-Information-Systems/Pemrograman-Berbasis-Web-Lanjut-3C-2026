<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil KategoriSeeder terlebih dahulu
        $this->call([
            KategoriSeeder::class,
            BukuSeeder::class,
        ]);
    }
}