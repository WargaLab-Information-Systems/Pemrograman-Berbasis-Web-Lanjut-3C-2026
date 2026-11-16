<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeamananTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function user(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);
        $user->ensureAnggota();

        return $user;
    }

    private function buku(string $judul = 'Buku Uji'): Buku
    {
        return Buku::factory()->create([
            'kategori_id' => Kategori::factory()->create()->id,
            'judul' => $judul,
        ]);
    }

    private function pinjaman(User $pemilik): Peminjaman
    {
        return Peminjaman::factory()->create([
            'anggota_id' => $pemilik->anggota->id,
            'buku_id' => $this->buku()->id,
            'status' => 'dipinjam',
        ]);
    }

    public function test_judul_buku_di_daftar_di_escape(): void
    {
        $this->buku("<script>alert('NIM')</script>");

        $html = $this->actingAs($this->user('siswa'))->get('/buku')->assertOk()->getContent();

        $this->assertStringNotContainsString("<script>alert('NIM')</script>", $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }

    public function test_pencarian_buku_tahan_sql_injection(): void
    {
        $this->buku('Bumi');

        $this->actingAs($this->user('admin'))
            ->getJson("/buku-cari?judul=' OR '1'='1")
            ->assertOk()->assertExactJson([]);

        $this->actingAs($this->user('admin'))
            ->getJson('/buku-cari?judul=Bumi')
            ->assertOk()->assertJsonCount(1);
    }

    public function test_hapus_buku_lewat_get_tidak_ada_dan_delete_berfungsi(): void
    {
        $buku = $this->buku();
        $admin = $this->user('admin');

        $this->actingAs($admin)->get("/buku/{$buku->id}/hapus")->assertNotFound();
        $this->assertDatabaseHas('bukus', ['id' => $buku->id]);

        $this->actingAs($admin)->delete("/buku/{$buku->id}")->assertRedirect(route('buku.index'));
        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    public function test_gate_kelola_buku_hanya_admin_dan_petugas(): void
    {
        $this->assertTrue($this->user('admin')->can('kelola-buku'));
        $this->assertTrue($this->user('petugas')->can('kelola-buku'));
        $this->assertFalse($this->user('siswa')->can('kelola-buku'));
    }

    public function test_siswa_ditolak_403_di_route_kelola_buku_dan_tidak_melihat_tombol(): void
    {
        $buku = $this->buku();
        $siswa = $this->user('siswa');

        $this->actingAs($siswa)->get('/buku/create')->assertForbidden();
        $this->actingAs($siswa)->get("/buku/{$buku->id}/edit")->assertForbidden();
        $this->actingAs($siswa)->post('/buku', [])->assertForbidden();
        $this->actingAs($siswa)->put("/buku/{$buku->id}", [])->assertForbidden();
        $this->actingAs($siswa)->delete("/buku/{$buku->id}")->assertForbidden();

        $html = $this->actingAs($siswa)->get('/buku')->getContent();
        $this->assertStringNotContainsString('Tambah Buku', $html);
        $this->assertStringNotContainsString('>Ubah<', $html);
        $this->assertStringNotContainsString('>Hapus<', $html);
    }

    public function test_admin_dan_petugas_dapat_tambah_ubah_hapus_buku(): void
    {
        foreach (['admin', 'petugas'] as $role) {
            $user = $this->user($role);
            $kategori = Kategori::factory()->create();

            $this->actingAs($user)->get('/buku/create')->assertOk();
            $this->actingAs($user)->post('/buku', [
                'kategori_id' => $kategori->id, 'judul' => "Judul $role", 'penulis' => 'Penulis', 'tahun_terbit' => 2020,
            ])->assertRedirect(route('buku.index'));

            $buku = Buku::where('judul', "Judul $role")->firstOrFail();
            $this->actingAs($user)->get("/buku/{$buku->id}/edit")->assertOk();
            $this->actingAs($user)->put("/buku/{$buku->id}", [
                'kategori_id' => $kategori->id, 'judul' => "Ubah $role", 'penulis' => 'Penulis', 'tahun_terbit' => 2021,
            ])->assertRedirect(route('buku.index'));
            $this->assertDatabaseHas('bukus', ['id' => $buku->id, 'judul' => "Ubah $role"]);

            $this->actingAs($user)->delete("/buku/{$buku->id}")->assertRedirect(route('buku.index'));
            $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
        }
    }

    public function test_policy_peminjaman_mencegah_idor(): void
    {
        $pemilik = $this->user('siswa');
        $lain = $this->user('siswa');
        $pinjam = $this->pinjaman($pemilik);

        $this->get("/peminjaman/{$pinjam->id}")->assertRedirect('/login');
        $this->actingAs($pemilik)->get("/peminjaman/{$pinjam->id}")->assertOk();
        $this->actingAs($lain)->get("/peminjaman/{$pinjam->id}")->assertForbidden();
        $this->actingAs($this->user('petugas'))->get("/peminjaman/{$pinjam->id}")->assertOk();
        $this->actingAs($this->user('admin'))->get("/peminjaman/{$pinjam->id}")->assertOk();
    }
}
