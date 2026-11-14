<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1. Buat Role
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAnggota = Role::firstOrCreate(['name' => 'anggota']);

        // 2. Buat Permission
        $permKelola = Permission::firstOrCreate(['name' => 'kelola buku']);
        $permLihat = Permission::firstOrCreate(['name' => 'lihat buku']);

        $roleAdmin->givePermissionTo($permKelola);
        $roleAnggota->givePermissionTo($permLihat);

        // 3. Buat Akun Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123')
            ]
        );
        $admin->syncRoles(['admin']);

        // 2. Akun Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@gmail.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password')
            ]
        );
        $petugas->assignRole('petugas');

        // 3. Akun Siswa
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@gmail.com'],
            [
                'name' => 'Siswa Teladan',
                'password' => Hash::make('password')
            ]
        );
        $siswa->assignRole('siswa');
    }
}