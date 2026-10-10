<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::InRandomOrder()->first()->id,
            'judul' => fake()->sentence(),
            'penulis' => fake()->name(),
            'tahun_terbit' => fake()->year()
        ];
    }
}
