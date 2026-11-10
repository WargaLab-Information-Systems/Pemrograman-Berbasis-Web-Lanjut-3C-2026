<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        return [
            'anggota_id'      => Anggota::inRandomOrder()->value('id'),
            'buku_id'         => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam'  => fake()->dateTimeBetween('-1 month', '-1 week')->format('Y-m-d'),
            'tanggal_kembali' => fake()->optional()->dateTimeBetween('-1 week', 'now')?->format('Y-m-d'),
        ];
    }
}
