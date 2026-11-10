<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat akun Admin dengan password baru yang aman (Soal 5c)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('AdminAman2026!'), // Password baru
            ]
        );

        // 2. Buat data dummy anggota lainnya via factory
        Anggota::factory()->count(3)->create();
    }
}