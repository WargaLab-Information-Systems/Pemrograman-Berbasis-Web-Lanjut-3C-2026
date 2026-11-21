<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'akses dashboard admin',           // 1
            'akses dashboard petugas',         // 2
            'lihat daftar buku',               // 3
            'kelola data buku',                // 4
            'kelola data pengguna',            // 5
            'kelola role pengguna',            // 6
            'kelola peminjaman',               // 7
            'kelola pengembalian',             // 8
            'mengajukan peminjaman buku',      // 9
            'memproses persetujuan peminjaman',// 10
            'mencatat pengembalian buku',      // 11
            'melihat status peminjaman sendiri',// 12
            'melihat riwayat peminjaman sendiri',// 13
            'lihat laporan perpustakaan',      // 14
            'lihat profil sendiri',            // 15
            'ubah profil sendiri',             // 16
        ];

        // Buat semua permission di database
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Buat atau Temukan Roles
        $adminRole = Role::findOrCreate('admin', 'web');
        $petugasRole  = Role::findOrCreate('petugas', 'web');  // Mewakili Petugas
        $siswaRole = Role::findOrCreate('siswa', 'web');

        // Opsional: Buat role 'petugas' jika sistem masih menggunakannya
        $petugasRole = Role::findOrCreate('petugas', 'web');

        // ADMIN
        $adminRole->syncPermissions([
            'akses dashboard admin',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // PETUGAS
        $petugasPermissions = [
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        $petugasRole->syncPermissions($petugasPermissions);

        // SISWA
        $siswaRole->syncPermissions([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);
    }
}