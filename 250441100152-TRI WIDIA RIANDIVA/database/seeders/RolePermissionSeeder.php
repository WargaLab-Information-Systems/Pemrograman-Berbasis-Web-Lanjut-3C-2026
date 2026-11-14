<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
            'lihat riwayat peminjaman sendiri',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roleAdmin = Role::findOrCreate('admin', 'web');
        $rolePetugas = Role::findOrCreate('petugas', 'web');
        $roleSiswa = Role::findOrCreate('siswa', 'web');

        $roleAdmin->syncPermissions([
            'akses dashboard admin',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
            'lihat riwayat peminjaman sendiri',
        ]);

        $rolePetugas->syncPermissions([
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
            'lihat riwayat peminjaman sendiri',
        ]);

        $roleSiswa->syncPermissions([
            'lihat daftar buku',
            'lihat profil sendiri',
            'ubah profil sendiri',
            'lihat riwayat peminjaman sendiri',
        ]);
    }
}