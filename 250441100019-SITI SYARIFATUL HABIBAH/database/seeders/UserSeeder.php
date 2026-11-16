<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const NIM = '250441100019';

    public function run(): void
    {
        $passwordAdmin = 'Admin#'.self::NIM;

        $staf = [
            ['Admin Perpustakaan',   'admin@perpustakaan.test',   '081200000001', 'admin',   $passwordAdmin],
            ['Petugas Perpustakaan', 'petugas@perpustakaan.test', '081200000002', 'petugas', 'password'],
        ];

        foreach ($staf as [$nama, $email, $noTelepon, $role, $password]) {
            $user = User::create([
                'name'     => $nama,
                'email'    => $email,
                'password' => $password,
            ]);
            $user->assignRole($role);
            $user->anggota()->create([
                'nama'       => $nama,
                'email'      => $email,
                'no_telepon' => $noTelepon,
            ]);
        }

        // Siswa: akun dihubungkan ke data anggota yang SUDAH ADA (punya riwayat peminjaman)
        foreach (['Budi Santoso', 'Siti Aminah', 'Ahmad Fauzi'] as $nama) {
            $anggota = Anggota::where('nama', $nama)->firstOrFail();

            $user = User::create([
                'name'     => $anggota->nama,
                'email'    => $anggota->email,
                'password' => 'password',
            ]);
            $user->assignRole('siswa');

            $anggota->user_id = $user->id;   // hubungkan akun dengan anggota
            $anggota->save();
        }
    }
}
