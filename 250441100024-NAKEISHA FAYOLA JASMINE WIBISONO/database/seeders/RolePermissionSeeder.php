<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Anggotas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 16 permission sesuai tabel soal nomor 4
        $semuaPermission = [
            'akses-dashboard-admin',               // 1
            'akses-dashboard-petugas',             // 2
            'lihat-daftar-buku',                   // 3
            'kelola-data-buku',                    // 4
            'kelola-data-pengguna',                // 5
            'kelola-role-pengguna',                // 6
            'kelola-peminjaman',                   // 7
            'kelola-pengembalian',                 // 8
            'ajukan-peminjaman-buku',              // 9
            'proses-persetujuan-peminjaman',       // 10
            'catat-pengembalian-buku',             // 11
            'lihat-status-peminjaman-sendiri',     // 12
            'lihat-riwayat-peminjaman-sendiri',    // 13
            'lihat-laporan-perpustakaan',          // 14
            'lihat-profil-sendiri',                // 15
            'ubah-profil-sendiri',                 // 16
        ];

        foreach ($semuaPermission as $nama) {
            Permission::findOrCreate($nama, 'web');
        }

        // Pembagian permission per role (kolom Admin / Petugas / Siswa pada tabel soal)
        $peran = [
            'admin' => array_values(array_diff($semuaPermission, [
                'akses-dashboard-petugas',
            ])),

            'petugas' => array_values(array_diff($semuaPermission, [
                'akses-dashboard-admin',
                'kelola-data-pengguna',
                'kelola-role-pengguna',
            ])),

            'siswa' => [
                'lihat-daftar-buku',
                'ajukan-peminjaman-buku',
                'lihat-status-peminjaman-sendiri',
                'lihat-riwayat-peminjaman-sendiri',
                'lihat-profil-sendiri',
                'ubah-profil-sendiri',
            ],
        ];

        foreach ($peran as $namaRole => $daftarPermission) {
            Role::findOrCreate($namaRole, 'web')->syncPermissions($daftarPermission);
        }

        // Akun awal. Role hanya bisa ditetapkan lewat seeder atau menu Kelola Pengguna (admin),
        // tidak lewat formulir registrasi.
        $akun = [
            ['Admin Perpustakaan', 'admin@perpus.test', 'admin'],
            ['Petugas Perpustakaan', 'petugas@perpus.test', 'petugas'],
            ['Siswa Contoh', 'siswa@perpus.test', 'siswa'],
        ];

        foreach ($akun as [$nama, $email, $namaRole]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => Hash::make('password123')]
            );

            $user->syncRoles([$namaRole]);

            if ($namaRole === 'siswa') {
                Anggotas::pastikan($user, '081234567890', true);
            }
        }
    }
}
