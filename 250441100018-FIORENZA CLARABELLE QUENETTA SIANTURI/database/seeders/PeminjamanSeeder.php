<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $anggota = Anggota::all();
        $buku = Buku::all();

        Peminjaman::factory()->count(3)->create([
            'anggota_id' => fn () => $anggota->random()->id,
            'buku_id' => fn () => $buku->random()->id,
        ]);
    }
}