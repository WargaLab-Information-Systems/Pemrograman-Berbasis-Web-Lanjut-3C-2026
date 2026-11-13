<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota; // Pastikan ini Anggota, bukan Buku

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 3 data anggota palsu
        Anggota::factory(3)->create();
    }
}