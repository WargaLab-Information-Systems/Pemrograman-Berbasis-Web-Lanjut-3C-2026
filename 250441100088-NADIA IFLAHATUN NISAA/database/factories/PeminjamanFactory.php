<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $pinjam = fake()->dateTimeBetween('-2 months', '-1 week');

        return [
            'anggota_id' => Anggota::inRandomOrder()->value('id'),
            'buku_id' => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam' => $pinjam->format('Y-m-d'),
            'tanggal_kembali' => fake()->optional()->dateTimeBetween($pinjam, 'now')?->format('Y-m-d'),
        ];
    }
}
