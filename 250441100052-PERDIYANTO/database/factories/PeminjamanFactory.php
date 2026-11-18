<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPinjam = fake()->dateTimeBetween('-1 year', 'now');
        return [
            'anggota_id' => Anggota::InRandomOrder()->first()->id,
            'buku_id' => Buku::InRandomOrder()->first()->id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => fake()->optional()->dateTimeBetween($tanggalPinjam, 'now')

        ];
    }
}
