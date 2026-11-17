<?php

namespace Tests\Feature\Api;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_guests_cannot_create_update_or_delete_books(): void
    {
        $book = Buku::factory()->create();

        $this->postJson('/api/buku', $this->bookData($book->kategori_id))->assertUnauthorized();
        $this->putJson("/api/buku/{$book->id}", $this->bookData($book->kategori_id))->assertUnauthorized();
        $this->deleteJson("/api/buku/{$book->id}")->assertUnauthorized();
    }

    public function test_authenticated_users_without_book_management_permission_cannot_mutate_books(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');
        $book = Buku::factory()->create(['judul' => 'Judul awal']);

        $this->actingAs($student)
            ->postJson('/api/buku', $this->bookData($book->kategori_id))
            ->assertForbidden();
        $this->putJson("/api/buku/{$book->id}", $this->bookData($book->kategori_id, 'Judul baru'))
            ->assertForbidden();
        $this->deleteJson("/api/buku/{$book->id}")->assertForbidden();

        $this->assertDatabaseCount('bukus', 1);
        $this->assertDatabaseHas('bukus', ['id' => $book->id, 'judul' => 'Judul awal']);
    }

    public function test_user_with_book_management_permission_can_create_update_and_delete_books(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('petugas');
        $category = Kategori::factory()->create();

        $createResponse = $this->actingAs($staff)
            ->postJson('/api/buku', $this->bookData($category->id));

        $createResponse->assertCreated()->assertJsonPath('data.judul', 'Judul awal');
        $bookId = $createResponse->json('data.id');

        $this->putJson("/api/buku/{$bookId}", $this->bookData(
            $category->id,
            'Judul diperbarui',
        ))
            ->assertOk()
            ->assertJsonPath('data.judul', 'Judul diperbarui');
        $this->assertDatabaseHas('bukus', ['id' => $bookId, 'judul' => 'Judul diperbarui']);

        $this->deleteJson("/api/buku/{$bookId}")->assertOk();
        $this->assertDatabaseMissing('bukus', ['id' => $bookId]);
    }

    public function test_book_read_endpoints_remain_public(): void
    {
        $book = Buku::factory()->create();

        $this->getJson('/api/buku')->assertOk();
        $this->getJson("/api/buku/{$book->id}")->assertOk();
    }

    /**
     * @return array{kategori_id: int, judul: string, penulis: string, tahun_terbit: int}
     */
    private function bookData(int $categoryId, string $title = 'Judul awal'): array
    {
        return [
            'kategori_id' => $categoryId,
            'judul' => $title,
            'penulis' => 'Nama Penulis',
            'tahun_terbit' => (int) date('Y'),
        ];
    }
}
