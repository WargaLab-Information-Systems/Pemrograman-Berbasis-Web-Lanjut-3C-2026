<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasUserSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::updateOrCreate(
            [
                'email' => 'petugas@katamitha.com',
            ],
            [
                'name' => 'Petugas KATAMITHA',
                'password' => Hash::make('password123'),
            ]
        );

        $petugas->syncRoles(['petugas']);
    }
}