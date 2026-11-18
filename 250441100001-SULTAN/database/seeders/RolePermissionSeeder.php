<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat seluruh 16 Permission sesuai tabel
        $permissions = [
            'Akses dashboard admin',            // 1
            'Akses dashboard petugas',          // 2
            'Lihat daftar buku',                // 3
            'Kelola data buku',                 // 4
            'Kelola data pengguna',             // 5
            'Kelola role pengguna',             // 6
            'Kelola peminjaman',                // 7
            'Kelola pengembalian',              // 8
            'Mengajukan peminjaman buku',       // 9
            'Memproses persetujuan peminjaman', // 10
            'Mencatat pengembalian buku',       // 11
            'Melihat status peminjaman sendiri',// 12
            'Melihat riwayat peminjaman sendiri',// 13
            'Lihat laporan perpustakaan',       // 14
            'Lihat profil sendiri',             // 15
            'Ubah profil sendiri',              // 16
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 2. Buat Role
        $roleAdmin   = Role::findOrCreate('admin');
        $rolePetugas = Role::findOrCreate('petugas');
        $roleSiswa   = Role::findOrCreate('siswa');

        // 3. Tetapkan Permission ke Admin (15 Permission)
        $roleAdmin->syncPermissions([
            'Akses dashboard admin',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola data pengguna',
            'Kelola role pengguna',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Mengajukan peminjaman buku',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        // 4. Tetapkan Permission ke Petugas (13 Permission)
        $rolePetugas->syncPermissions([
            'Akses dashboard petugas',
            'Lihat daftar buku',
            'Kelola data buku',
            'Kelola peminjaman',
            'Kelola pengembalian',
            'Mengajukan peminjaman buku',
            'Memproses persetujuan peminjaman',
            'Mencatat pengembalian buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat laporan perpustakaan',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        // 5. Tetapkan Permission ke Siswa (6 Permission)
        $roleSiswa->syncPermissions([
            'Lihat daftar buku',
            'Mengajukan peminjaman buku',
            'Melihat status peminjaman sendiri',
            'Melihat riwayat peminjaman sendiri',
            'Lihat profil sendiri',
            'Ubah profil sendiri',
        ]);

        // 6. Pasangkan Role ke User Penguji
        $adminUser = User::where('email', 'admin@test.com')->first();
        if ($adminUser) $adminUser->assignRole($roleAdmin);

        $petugasUser = User::where('email', 'petugas@test.com')->first();
        if ($petugasUser) $petugasUser->assignRole($rolePetugas);

        $siswaUser = User::where('email', 'siswa@test.com')->first();
        if ($siswaUser) $siswaUser->assignRole($roleSiswa);
    }
}