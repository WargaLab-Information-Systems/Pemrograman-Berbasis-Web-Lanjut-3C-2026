<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission disesuaikan dengan kebutuhan routes & Modul 5
        $permissions = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'Kelola data buku',
            'Kelola pengguna',
            'kelola data pengguna',
            'kelola role pengguna',
            'Kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'Lihat riwayat peminjaman',
            'melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roleAdmin   = Role::findOrCreate('Admin', 'web');
        $rolePetugas = Role::findOrCreate('Petugas', 'web');
        $roleSiswa   = Role::findOrCreate('Siswa', 'web');

        $roleAdmin->givePermissionTo(Permission::all());

        $rolePetugas->givePermissionTo([
            'akses dashboard petugas',
            'lihat daftar buku',
            'Kelola data buku',
            'Kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'Lihat riwayat peminjaman',
            'melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        $roleSiswa->givePermissionTo([
            'lihat daftar buku',
            'mengajukan peminjaman buku',
            'melihat status peminjaman sendiri',
            'Lihat riwayat peminjaman',
            'melihat riwayat peminjaman sendiri',
            'lihat profil sendiri',
            'ubah profil sendiri',
        ]);

        // Buat Akun & Assign Role
        $admin = User::firstOrCreate(['email' => 'admin@perpustakaan.com'], [
            'name' => 'Administrator',
            'password' => Hash::make('password123'),
        ]);
        $admin->syncRoles([$roleAdmin]);

        $petugas = User::firstOrCreate(['email' => 'petugas@perpustakaan.com'], [
            'name' => 'Petugas Perpustakaan',
            'password' => Hash::make('password123'),
        ]);
        $petugas->syncRoles([$rolePetugas]);

        $siswa = User::firstOrCreate(['email' => 'siswa@perpustakaan.com'], [
            'name' => 'Siswa Utama',
            'password' => Hash::make('password123'),
        ]);
        $siswa->syncRoles([$roleSiswa]);
    }
}