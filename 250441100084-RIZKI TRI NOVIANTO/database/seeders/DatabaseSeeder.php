<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class, // 1. Buat Role & Permission terlebih dahulu
            UserSeeder::class,               // 2. Buat Akun Penguji (Admin, Petugas, Siswa)
            KategoriSeeder::class,           // 3. Data Master Kategori
            BukuSeeder::class,               // 4. Data Master Buku
            AnggotaSeeder::class,            // 5. Data Master Anggota
            PeminjamanSeeder::class,         // 6. Data Transaksi Peminjaman
        ]);
    }
}