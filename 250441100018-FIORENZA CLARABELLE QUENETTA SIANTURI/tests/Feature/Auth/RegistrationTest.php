<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registered_user_is_siswa_and_password_is_hashed(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->post('/register', [
            'name' => 'Siswa Baru',
            'email' => 'baru@example.com',
            'no_telepon' => '0811',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'baru@example.com')->firstOrFail();

        $this->assertTrue($user->hasRole('siswa'));
        $this->assertNotSame('password', $user->password);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertSame('0811', $user->anggota->no_telepon);
    }

    public function test_role_cannot_be_chosen_via_registration_form(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->post('/register', [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'roles' => ['admin'],
        ]);

        $user = User::where('email', 'penyusup@example.com')->firstOrFail();

        $this->assertTrue($user->hasRole('siswa'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertCount(1, $user->roles);
    }

    public function test_registration_fails_with_invalid_data(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->post('/register', [
            'name' => '',
            'email' => 'bukan-email',
            'password' => 'password',
            'password_confirmation' => 'beda',
        ])->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertGuest();
    }
}
