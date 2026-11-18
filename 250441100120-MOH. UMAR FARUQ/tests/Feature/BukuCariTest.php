<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuCariTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_with_book_permission_can_search_books_and_receive_json(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $book = Buku::factory()->create(['judul' => 'Pemrograman API Laravel']);
        Buku::factory()->create(['judul' => 'Basis Data']);

        $this->actingAs($student)
            ->getJson(route('buku.cari', ['judul' => 'Pemrograman Laravel']))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $book->id)
            ->assertJsonPath('0.judul', $book->judul);
    }

    public function test_search_requires_a_title(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');

        $this->actingAs($student)
            ->getJson(route('buku.cari'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('judul');
    }

    public function test_search_requires_authentication_and_book_permission(): void
    {
        $this->get(route('buku.cari', ['judul' => 'Pemrograman Laravel']))
            ->assertRedirect(route('login'));

        $userWithoutBookPermission = User::factory()->create();

        $this->actingAs($userWithoutBookPermission)
            ->getJson(route('buku.cari', ['judul' => 'Pemrograman Laravel']))
            ->assertForbidden();
    }

    public function test_book_index_escapes_book_titles(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $book = Buku::factory()->create(['judul' => '<b>Judul buku</b>']);

        $this->actingAs($admin)
            ->get(route('buku.index'))
            ->assertOk()
            ->assertSee(e($book->judul), false)
            ->assertDontSee($book->judul, false);
    }

    public function test_book_index_displays_books_matching_search_terms(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $matchingBook = Buku::factory()->create(['judul' => 'Pemrograman API Laravel']);
        $otherBook = Buku::factory()->create(['judul' => 'Basis Data']);

        $this->actingAs($admin)
            ->get(route('buku.index', ['judul' => 'Pemrograman Laravel']))
            ->assertOk()
            ->assertSee($matchingBook->judul)
            ->assertDontSee($otherBook->judul)
            ->assertSee('value="Pemrograman Laravel"', false);
    }
}
