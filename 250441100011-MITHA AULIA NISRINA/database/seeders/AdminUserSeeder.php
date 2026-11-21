<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@katamitha.com',
            ],
            [
                'name' => 'Admin KATAMITHA',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->syncRoles(['admin']);
    }
}