<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
        ]);

        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Soal 5 (c): Ganti password akun admin bawaan seeder
        User::where('email', 'admin@gmail.com')->update([
            'password' => Hash::make('AdminAman2026!')
        ]);
    }
}