<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Modul 6: Keamanan Web dan Otorisasi.
 * Menguji KONDISI AKHIR project (setelah Soal 2 sampai Soal 5 diterapkan),
 * sesuai tabel pengujian pada Soal 5. Nomor test mengikuti nomor baris tabel.
 *
 * Jalankan: php artisan test --filter=SecurityTest
 */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    private Kategori $kategori;
    private Buku $buku;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->kategori = Kategori::factory()->create(['nama' => 'Fiksi']);
        $this->buku = Buku::factory()->create([
            'kategori_id'  => $this->kategori->id,
            'judul'        => 'Buku Uji Keamanan',
            'penulis'      => 'Penulis Uji',
            'tahun_terbit' => 2024,
        ]);
    }

    private function akun(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);
        $user->anggota()->create([
            'nama' => $user->name, 'email' => $user->email, 'no_telepon' => '081234567890',
        ]);

        return $user;
    }

    private function pinjam(User $user, ?Buku $buku = null): Peminjaman
    {
        $p = new Peminjaman([
            'anggota_id' => $user->anggota->id,
            'buku_id'    => ($buku ?? $this->buku)->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);
        $p->status = 'dipinjam';
        $p->save();

        return $p;
    }

    // ---------------------------------------------------- A1: XSS (Soal 2a)

    public function test_a1_judul_buku_dicetak_sebagai_teks_bukan_html(): void
    {
        $admin = $this->akun('admin');
        $payload = "<script>alert(1)</script>";

        Buku::factory()->create([
            'kategori_id' => $this->kategori->id, 'judul' => $payload,
            'penulis' => 'Penguji', 'tahun_terbit' => 2024,
        ]);

        $response = $this->actingAs($admin)->get('/buku');

        $response->assertOk();
        $response->assertDontSee($payload, false);      // tag asli TIDAK ada
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false); // sudah di-escape
    }

    // ------------------------------------------------- A2: SQLi (Soal 2b)

    public function test_a2_pencarian_buku_tahan_sql_injection(): void
    {
        Buku::factory()->count(3)->create(['kategori_id' => $this->kategori->id]);
        $admin = $this->akun('admin');

        $response = $this->actingAs($admin)->getJson("/buku-cari?judul=" . urlencode("' OR '1'='1"));

        $response->assertOk();
        $response->assertExactJson([]);   // payload TIDAK membocorkan seluruh tabel
    }

    public function test_a2_pencarian_buku_normal_tetap_berfungsi(): void
    {
        $admin = $this->akun('admin');

        $response = $this->actingAs($admin)->getJson('/buku-cari?judul=' . urlencode('Buku Uji Keamanan'));

        $response->assertOk()->assertJsonCount(1);
    }

    // -------------------------------------------- A3: CSRF hapus (Soal 2c)

    public function test_a3_route_get_hapus_buku_sudah_tidak_ada(): void
    {
        $admin = $this->akun('admin');

        $response = $this->actingAs($admin)->get("/buku/{$this->buku->id}/hapus");

        $this->assertContains($response->status(), [404, 405]);
        $this->assertDatabaseHas('bukus', ['id' => $this->buku->id]);
    }

    /**
     * CATATAN: Laravel SECARA BAWAAN melewati pemeriksaan CSRF saat PHPUnit
     * berjalan (lihat PreventRequestForgery::runningUnitTests()), supaya
     * feature test lain tidak perlu mengurus token. Karena itu status 419
     * TIDAK bisa diuji lewat PHPUnit di sini, dan sesuai spesifikasi soal,
     * skenario ini memang diuji lewat Postman terhadap server yang benar-benar
     * berjalan (lihat tangkapan layar pada laporan, bukan PHPUnit).
     */

    public function test_a3_tombol_hapus_dengan_token_csrf_tetap_berhasil(): void
    {
        $admin = $this->akun('admin');

        // Laravel otomatis menyertakan token CSRF yang valid pada request test ini.
        $response = $this->actingAs($admin)
            ->withSession(['_token' => 'token-uji'])
            ->delete("/buku/{$this->buku->id}", ['_token' => 'token-uji']);

        $response->assertRedirect(route('buku.index'));
        $this->assertDatabaseMissing('bukus', ['id' => $this->buku->id]);
    }

    // ---------------------------------------------- Soal 3: Gate kelola-buku

    public function test_gate_kelola_buku_mengizinkan_admin_dan_petugas_saja(): void
    {
        $admin = $this->akun('admin');
        $petugas = $this->akun('petugas');
        $siswa = $this->akun('siswa');

        $this->assertTrue($admin->can('kelola-buku'));
        $this->assertTrue($petugas->can('kelola-buku'));
        $this->assertFalse($siswa->can('kelola-buku'));
    }

    public function test_siswa_ketik_buku_create_langsung_ditolak_403(): void
    {
        $siswa = $this->akun('siswa');

        $this->actingAs($siswa)->get('/buku/create')->assertForbidden();
    }

    public function test_admin_dan_petugas_buku_create_tidak_ditolak(): void
    {
        $this->actingAs($this->akun('admin'))->get('/buku/create')->assertRedirect(route('buku.index'));
        $this->actingAs($this->akun('petugas'))->get('/buku/create')->assertRedirect(route('buku.index'));
    }

    public function test_tombol_kelola_buku_tidak_tampil_untuk_siswa(): void
    {
        $siswa = $this->akun('siswa');

        $response = $this->actingAs($siswa)->get('/buku');

        $response->assertDontSee('id="form-buku"', false);
        $response->assertSee('data-kelola="false"', false);
    }

    public function test_tombol_kelola_buku_tampil_untuk_admin_dan_petugas(): void
    {
        foreach (['admin', 'petugas'] as $role) {
            $response = $this->actingAs($this->akun($role))->get('/buku');
            $response->assertSee('id="form-buku"', false);
            $response->assertSee('data-kelola="true"', false);
        }
    }

    public function test_gate_kelola_buku_juga_melindungi_operasi_tulis_api(): void
    {
        $siswa = $this->akun('siswa');

        $this->actingAs($siswa)->postJson('/api/buku', [
            'kategori_id' => $this->kategori->id, 'judul' => 'Dibobol', 'penulis' => 'X', 'tahun_terbit' => 2024,
        ])->assertForbidden();
    }

    // ------------------------------------------- Soal 4: PeminjamanPolicy (A4)

    public function test_a4_siswa_melihat_peminjaman_sendiri_200(): void
    {
        $siswa = $this->akun('siswa');
        $p = $this->pinjam($siswa);

        $this->actingAs($siswa)->get("/peminjaman/{$p->id}")->assertOk();
    }

    public function test_a4_siswa_tidak_bisa_melihat_peminjaman_siswa_lain_403(): void
    {
        $budi = $this->akun('siswa');
        $siti = $this->akun('siswa');
        $p = $this->pinjam($siti);

        $this->actingAs($budi)->get("/peminjaman/{$p->id}")->assertForbidden();
    }

    public function test_a4_admin_dan_petugas_melihat_peminjaman_siapa_pun_200(): void
    {
        $siswa = $this->akun('siswa');
        $p = $this->pinjam($siswa);

        $this->actingAs($this->akun('admin'))->get("/peminjaman/{$p->id}")->assertOk();
        $this->actingAs($this->akun('petugas'))->get("/peminjaman/{$p->id}")->assertOk();
    }

    public function test_a4_belum_login_dialihkan_ke_login(): void
    {
        $p = $this->pinjam($this->akun('siswa'));

        $this->get("/peminjaman/{$p->id}")->assertRedirect(route('login'));
    }

    // ------------------------------------------------- Soal 5: konfigurasi

    public function test_password_admin_bawaan_seeder_tidak_lagi_password(): void
    {
        // UserSeeder butuh data Anggota ('Budi Santoso', dst.) sudah ada,
        // jadi seed lewat DatabaseSeeder (urutan lengkap), bukan UserSeeder saja.
        $this->seed();

        $response = $this->post('/login', [
            'email' => 'admin@perpustakaan.test', 'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_env_tidak_dapat_diakses_lewat_browser(): void
    {
        // Laravel default: webroot ada di public/, .env tidak pernah
        // berada di bawah public/, sehingga permintaan ke /.env selalu 404.
        $response = $this->get('/.env');

        $response->assertNotFound();
    }
}
