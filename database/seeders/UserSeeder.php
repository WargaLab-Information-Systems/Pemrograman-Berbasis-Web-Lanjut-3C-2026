<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Akun demo. Role ditetapkan di sini (jalur berwenang), bukan lewat form registrasi.
     * Password akun admin TIDAK lagi "password": diambil dari ADMIN_PASSWORD di .env
     * (bila kosong, dibuat acak dan ditampilkan sekali saat seeding).
     * Akun petugas/siswa demo tetap memakai "password" HANYA untuk pengujian lokal.
     */
    public function run(): void
    {
        $akun = [
            ['Admin Perpustakaan', 'admin@perpustakaan.test', User::ROLE_ADMIN],
            ['Petugas Perpustakaan', 'petugas@perpustakaan.test', User::ROLE_PETUGAS],
            ['Siswa Satu', 'siswa1@perpustakaan.test', User::ROLE_SISWA],
            ['Siswa Dua', 'siswa2@perpustakaan.test', User::ROLE_SISWA],
        ];

        $passwordAdmin = env('ADMIN_PASSWORD');

        if (blank($passwordAdmin)) {
            $passwordAdmin = Str::password(20);
            $this->command?->warn("ADMIN_PASSWORD kosong; password admin acak: {$passwordAdmin}");
        }

        foreach ($akun as [$nama, $email, $role]) {
            if ($role === User::ROLE_ADMIN) {
                // updateOrCreate: bila admin lama sudah ada dengan password "password", password-nya diganti.
                $user = User::updateOrCreate(
                    ['email' => $email],
                    ['name' => $nama, 'password' => $passwordAdmin] // di-hash oleh cast 'hashed'
                );
            } else {
                $user = User::firstOrCreate(
                    ['email' => $email],
                    ['name' => $nama, 'password' => 'password']
                );
            }

            $user->syncRoles([$role]);
            $user->ensureAnggota();
        }
    }
}
