<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class FixAdminRoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cache Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Pastikan Role Admin Ada
        $adminRole = Role::findOrCreate('admin', 'web');

        // Berikan semua hak akses/permission ke role Admin
        $permissions = [
            'akses dashboard admin', 'lihat daftar buku', 'kelola data buku',
            'kelola data pengguna', 'kelola role pengguna', 'kelola peminjaman',
            'kelola pengembalian', 'lihat laporan perpustakaan'
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }
        $adminRole->syncPermissions(Permission::all());

        // Lampirkan role Admin ke SELURUH pengguna yang ada di database saat ini
        $users = User::all();
        foreach ($users as $user) {
            $user->syncRoles(['admin']);
        }
    }
}