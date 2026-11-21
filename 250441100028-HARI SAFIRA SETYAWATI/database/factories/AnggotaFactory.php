<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnggotaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement([
                'Xylo Abc',
                'Qara Zun',
                'Bimo Qwert'
            ]),

            'email' => fake()->unique()->safeEmail(),

            'no_telepon' => fake()->numerify('08##########'),
        ];
    }
}