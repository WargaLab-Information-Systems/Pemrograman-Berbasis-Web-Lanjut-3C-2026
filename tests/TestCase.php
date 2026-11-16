<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function buatUser(string $role, array $atribut = []): User
    {
        $this->seed(RolePermissionSeeder::class);

        $user = User::factory()->create($atribut);
        $user->assignRole($role);
        $user->ensureAnggota();

        return $user;
    }
}
