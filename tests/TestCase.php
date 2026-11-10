<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Buat user dengan role tertentu (role & permission di-seed dari RolePermissionSeeder).
     */
    protected function buatUser(string $role, array $atribut = []): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create($atribut);
        $user->assignRole($role);
        $user->ensureAnggota();

        return $user;
    }
}
