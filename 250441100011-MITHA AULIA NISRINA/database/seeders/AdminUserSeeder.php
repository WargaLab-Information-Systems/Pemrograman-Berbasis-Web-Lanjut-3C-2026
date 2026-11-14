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
                'password' => Hash::make('MithaAdmin2026!'),
            ]
        );

        $admin->syncRoles(['admin']);
    }
}