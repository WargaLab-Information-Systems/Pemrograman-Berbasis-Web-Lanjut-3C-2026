<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_named_routes_use_the_required_urls(): void
    {
        $this->assertSame('/', route('home', absolute: false));
        $this->assertSame('/buku', route('buku.index', absolute: false));
        $this->assertSame('/buku/5', route('buku.show', 5, absolute: false));
    }

    public function test_api_book_routes_do_not_override_web_book_routes(): void
    {
        $this->assertSame('/buku', route('buku.index', absolute: false));
        $this->assertSame('/api/buku', route('api.buku.index', absolute: false));
    }

    public function test_home_page_uses_the_main_layout_and_links_to_the_book_list(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Selamat Datang di Perpustakaan Efqai')
            ->assertSee('Beranda')
            ->assertSee(route('buku.index'));
    }

    public function test_petugas_can_view_book_index_and_details(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('petugas');

        $technology = Kategori::factory()->create(['nama' => 'Teknologi']);
        $book = Buku::factory()->create([
            'kategori_id' => $technology->id,
            'judul' => 'Pemrograman Web dengan Laravel',
            'penulis' => 'Budi Raharjo',
            'tahun_terbit' => 2023,
        ]);
        foreach ([
            'Algoritma dan Struktur Data',
            'Basis Data Relasional',
            'Jaringan Komputer Modern',
            'Kecerdasan Buatan dan Pembelajaran Mesin',
        ] as $title) {
            Buku::factory()->create(['judul' => $title]);
        }

        $this->actingAs($staff)
            ->get(route('buku.index'))
            ->assertOk()
            ->assertSee('Pemrograman Web dengan Laravel')
            ->assertSee('Budi Raharjo')
            ->assertSee('2023')
            ->assertSee('Teknologi')
            ->assertSee('Algoritma dan Struktur Data')
            ->assertSee('Basis Data Relasional')
            ->assertSee('Jaringan Komputer Modern')
            ->assertSee('Kecerdasan Buatan dan Pembelajaran Mesin')
            ->assertSee(route('buku.show', $book));
    }

    public function test_book_index_escapes_html_in_book_titles(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('petugas');
        $scriptTitle = "<script>alert('NIM')</script>";
        Buku::factory()->create(['judul' => $scriptTitle]);

        $this->actingAs($staff)
            ->get(route('buku.index'))
            ->assertOk()
            ->assertSee($scriptTitle);
    }

    public function test_book_detail_displays_the_book_matching_its_id(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('petugas');
        $category = Kategori::factory()->create(['nama' => 'Database']);
        $book = Buku::factory()->create([
            'kategori_id' => $category->id,
            'judul' => 'Basis Data Relasional',
            'penulis' => 'Fathansyah',
            'tahun_terbit' => 2020,
        ]);

        $this->actingAs($staff)
            ->get(route('buku.show', $book))
            ->assertOk()
            ->assertSee('Basis Data Relasional')
            ->assertSee('Fathansyah')
            ->assertSee('2020')
            ->assertSee('Database');
    }

    public function test_missing_book_returns_not_found(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('petugas');

        $this->actingAs($staff)
            ->get(route('buku.show', 99))
            ->assertNotFound();
    }
}
