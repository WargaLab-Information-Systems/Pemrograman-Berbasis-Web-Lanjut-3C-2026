<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public const PERMISSIONS = [
        'akses dashboard admin',
        'akses dashboard petugas',
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
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $nama) {
            Permission::findOrCreate($nama, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $umum = [
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        $operasional = [
            'kelola data buku',
            'kelola peminjaman',
            'kelola pengembalian',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'lihat laporan perpustakaan',
        ];

        Role::findOrCreate('admin', 'web')->syncPermissions([
            ...$umum,
            ...$operasional,
            'akses dashboard admin',
            'kelola data pengguna',
            'kelola role pengguna',
        ]);

        Role::findOrCreate('petugas', 'web')->syncPermissions([
            ...$umum,
            ...$operasional,
            'akses dashboard petugas',
        ]);

        Role::findOrCreate('siswa', 'web')->syncPermissions($umum);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
