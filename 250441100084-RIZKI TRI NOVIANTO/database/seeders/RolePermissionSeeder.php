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
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($roleAdmin);

        // 4. Buat Akun Anggota
        $anggota = User::firstOrCreate(
            ['email' => 'anggota@gmail.com'],
            [
                'name' => 'Anggota Perpustakaan',
                'password' => Hash::make('password123'),
            ]
        );
        $anggota->assignRole($roleAnggota);
    }
}