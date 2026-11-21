<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'anggota_id' => Anggota::inRandomOrder()->first()->id,

            'buku_id' => Buku::inRandomOrder()->first()->id,

            'tanggal_pinjam' => fake()->dateTimeBetween('-30 days', 'now')
                ->format('Y-m-d'),

            'tanggal_kembali' => fake()->optional(0.7)->dateTimeBetween('-20 days', 'now')
                ?->format('Y-m-d'),
        ];
    }
}