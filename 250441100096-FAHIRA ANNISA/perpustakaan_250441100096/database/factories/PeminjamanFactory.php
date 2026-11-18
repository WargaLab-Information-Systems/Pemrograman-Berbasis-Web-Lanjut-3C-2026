<?php

namespace Database\Factories;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        return [
            
            'anggota_id' => Anggota::inRandomOrder()->first()?->id ?? Anggota::factory(),
            'buku_id' => Buku::inRandomOrder()->first()?->id ?? Buku::factory(),
            'tanggal_pinjam' => fake()->date(),
            'tanggal_kembali' => fake()->optional()->date(),
        ];
    }
}


