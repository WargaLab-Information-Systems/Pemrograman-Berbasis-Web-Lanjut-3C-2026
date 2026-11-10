<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Akun demo. Role ditetapkan di sini (jalur berwenang), bukan lewat form registrasi.
     * Password semua akun demo: "password" — ganti sebelum dipakai sungguhan.
     */
    public function run(): void
    {
        $akun = [
            ['Admin Perpustakaan', 'admin@perpustakaan.test', User::ROLE_ADMIN],
            ['Petugas Perpustakaan', 'petugas@perpustakaan.test', User::ROLE_PETUGAS],
            ['Siswa Satu', 'siswa1@perpustakaan.test', User::ROLE_SISWA],
            ['Siswa Dua', 'siswa2@perpustakaan.test', User::ROLE_SISWA],
        ];

        foreach ($akun as [$nama, $email, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => 'password'] // di-hash oleh cast 'hashed'
            );

            $user->syncRoles([$role]);
            $user->ensureAnggota();
        }
    }
}
