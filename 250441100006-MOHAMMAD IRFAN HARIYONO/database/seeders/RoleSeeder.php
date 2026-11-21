<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar 16 Permission sesuai soal
        $permissions = [
            'akses dashboard admin', 'akses dashboard petugas', 'lihat daftar buku', 
            'kelola data buku', 'kelola data pengguna', 'kelola role pengguna', 
            'kelola peminjaman', 'kelola pengembalian', 'mengajukan peminjaman buku', 
            'memproses persetujuan peminjaman', 'mencatat pengembalian buku', 
            'melihat status peminjaman sendiri', 'melihat riwayat peminjaman sendiri', 
            'lihat laporan perpustakaan', 'lihat profil sendiri', 'ubah profil sendiri'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat Role dan Assign Permission Sesuai Tabel Soal
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'akses dashboard admin', 'lihat daftar buku', 'kelola data buku', 
            'kelola data pengguna', 'kelola role pengguna', 'kelola peminjaman', 
            'kelola pengembalian', 'memproses persetujuan peminjaman', 
            'melihat status peminjaman sendiri', 'melihat riwayat peminjaman sendiri', 
            'lihat laporan perpustakaan', 'lihat profil sendiri', 'ubah profil sendiri'
        ]);

        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $petugas->syncPermissions([
            'akses dashboard petugas', 'lihat daftar buku', 'kelola data buku', 
            'kelola peminjaman', 'kelola pengembalian', 'mencatat pengembalian buku', 
            'lihat laporan perpustakaan', 'lihat profil sendiri', 'ubah profil sendiri'
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'lihat daftar buku', 'mengajukan peminjaman buku', 
            'melihat status peminjaman sendiri', 'melihat riwayat peminjaman sendiri', 
            'lihat profil sendiri', 'ubah profil sendiri'
        ]);
    }
}
