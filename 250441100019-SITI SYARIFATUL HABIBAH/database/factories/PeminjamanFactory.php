<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $tanggalPinjam = fake()->dateTimeBetween('-1 year', 'now');
        $tanggalKembali = fake()->optional()->dateTimeBetween($tanggalPinjam, 'now');

        return [
            'anggota_id' => Anggota::inRandomOrder()->value('id'),
            'buku_id' => Buku::inRandomOrder()->value('id'),
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => $tanggalKembali ? 'dikembalikan' : 'dipinjam',
        ];
    }
}
