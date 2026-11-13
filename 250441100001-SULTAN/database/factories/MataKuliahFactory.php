<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_studi_id' => ProgramStudi::inRandomOrder()->first()->id,

            'kode' => strtoupper(
                fake()->unique()->bothify('MK-###')
            ),

            'nama_mk' => fake()->sentence(3),

            'sks' => fake()->numberBetween(2, 4),

            'status_aktif' => fake()->boolean(),

            'tanggal_dibuka' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}