<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_student_can_view_book_list_without_management_controls(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $book = Buku::factory()->create();

        $this->actingAs($student)
            ->get(route('buku.katalog'))
            ->assertOk()
            ->assertSee($book->judul)
            ->assertDontSee('Tambah Buku Baru')
            ->assertDontSee('Ubah')
            ->assertDontSee('Hapus');
    }

    public function test_students_are_forbidden_from_all_book_management_routes(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $book = Buku::factory()->create();

        $this->actingAs($student)
            ->get(route('buku.create'))
            ->assertForbidden();

        $this->post(route('buku.store'))->assertForbidden();
        $this->get(route('buku.edit', $book))->assertForbidden();
        $this->put(route('buku.update', $book))->assertForbidden();
        $this->delete(route('buku.destroy', $book))->assertForbidden();
    }

    public function test_admin_and_petugas_can_access_book_management_routes(): void
    {
        foreach (['admin', 'petugas'] as $role) {
            $user = User::factory()->create();
            $user->assignRole($role);
            $book = Buku::factory()->create();

            $this->actingAs($user)
                ->get(route('buku.create'))
                ->assertOk();

            $this->get(route('buku.edit', $book))->assertOk();

            $attributes = [
                'kategori_id' => $book->kategori_id,
                'judul' => "Buku baru {$role}",
                'penulis' => 'Penulis Uji',
                'tahun_terbit' => 2020,
            ];

            $this->post(route('buku.store'), $attributes)
                ->assertRedirect(route('buku.index'));
            $this->put(route('buku.update', $book), $attributes)
                ->assertRedirect(route('buku.index'));
            $this->delete(route('buku.destroy', $book))
                ->assertRedirect(route('buku.index'));
        }
    }
}
