<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Perpustakaan', 'password' => Hash::make('admin123')]
        );
        $admin->assignRole('admin');

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@gmail.com'],
            ['name' => 'Petugas Perpustakaan', 'password' => Hash::make('petugas123')]
        );
        $petugas->assignRole('petugas');

        $siswa = User::updateOrCreate(
            ['email' => 'siswa@gmail.com'],
            ['name' => 'Siswa Utama', 'password' => Hash::make('siswa123')]
        );
        $siswa->assignRole('siswa');
    }
}
    
