<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan Seeder untuk Role & Permission dari Spatie (Modul 5)
        $this->call(RoleSeeder::class);

        // 2. Buat Akun Dummy untuk Login (Modul 5)
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpus.com'],
            ['name' => 'Admin Perpus', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            ['name' => 'Petugas Perpus', 'password' => Hash::make('password')]
        );
        $petugas->assignRole('petugas');

        $siswa = User::firstOrCreate(
            ['email' => 'siswa@perpus.com'],
            ['name' => 'Siswa Perpus', 'password' => Hash::make('password')]
        );
        $siswa->assignRole('siswa');

        // 3. Jalankan Seeder untuk data perpustakaan (Modul sebelumnya)
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}