<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian Modul 5 (Autentikasi dan RBAC).
 * Nomor skenario mengikuti tabel pengujian pada soal nomor 5.
 * Jalankan: php artisan test --filter=RbacTest
 */
class RbacTest extends TestCase
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
            'judul'        => 'Laskar Pelangi',
            'penulis'      => 'Andrea Hirata',
            'tahun_terbit' => 2005,
        ]);
    }

    private function akun(string $role): User
    {
        $user = User::factory()->create([
            'name'  => ucfirst($role) . ' Uji',
            'email' => $role . '.' . uniqid() . '@uji.test',
        ]);
        $user->assignRole($role);
        $user->anggota()->create([
            'nama'       => $user->name,
            'email'      => $user->email,
            'no_telepon' => '081234567890',
        ]);

        return $user;
    }

    private function pinjam(User $user, string $status = 'dipinjam', ?Buku $buku = null): Peminjaman
    {
        $peminjaman = new Peminjaman([
            'anggota_id'     => $user->anggota->id,
            'buku_id'        => ($buku ?? $this->buku)->id,
            'tanggal_pinjam' => now()->toDateString(),
        ]);
        $peminjaman->status = $status;
        $peminjaman->save();

        return $peminjaman;
    }

    private function dataBuku(array $timpa = []): array
    {
        return array_merge([
            'kategori_id'  => $this->kategori->id,
            'judul'        => 'Buku Baru Uji',
            'penulis'      => 'Penulis Uji',
            'tahun_terbit' => 2024,
        ], $timpa);
    }

    public function test_login_dengan_kredensial_valid_berhasil_dan_tidak_valid_gagal(): void
    {
        $user = $this->akun('siswa');

        $this->post('/login', ['email' => 'tidak.ada@uji.test', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_kata_sandi_disimpan_dalam_bentuk_hash(): void
    {
        $this->post('/register', [
            'name' => 'Pendaftar Baru', 'email' => 'baru@uji.test', 'no_telepon' => '081234567890',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ]);

        $tersimpan = User::where('email', 'baru@uji.test')->value('password');

        $this->assertNotSame('rahasia123', $tersimpan);
        $this->assertTrue(password_verify('rahasia123', $tersimpan));
    }

    public function test_registrasi_selalu_menjadi_siswa_walau_mengirim_role_admin(): void
    {
        $this->post('/register', [
            'name' => 'Penyusup', 'email' => 'penyusup@uji.test', 'no_telepon' => '081234567890',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
            'role' => 'admin', 'roles' => ['admin'], 'permissions' => ['kelola data pengguna'],
        ])->assertRedirect();

        $user = User::where('email', 'penyusup@uji.test')->firstOrFail();

        $this->assertTrue($user->hasRole('siswa'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->can('kelola data pengguna'));
    }

    public function test_middleware_guest_pengguna_login_tidak_bisa_membuka_login_dan_registrasi(): void
    {
        $user = $this->akun('siswa');

        $this->actingAs($user)->get('/login')->assertRedirect(route('dashboard', absolute: false));
        $this->actingAs($user)->get('/register')->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_dashboard_mengarahkan_sesuai_role(): void
    {
        $this->actingAs($this->akun('admin'))->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($this->akun('petugas'))->get('/dashboard')->assertRedirect(route('petugas.dashboard'));
        $this->actingAs($this->akun('siswa'))->get('/dashboard')->assertRedirect(route('buku.index'));
    }

    public function test_api_tanpa_login_ditolak_401(): void
    {
        $this->getJson('/api/buku')->assertStatus(401);
        $this->postJson('/api/buku', $this->dataBuku())->assertStatus(401);
        $this->getJson('/api/rekap/buku-per-kategori')->assertStatus(401);
    }

    public function test_skenario_01_belum_login_membuka_dashboard_admin_dialihkan_ke_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_skenario_02_admin_membuka_dashboard_admin(): void
    {
        $this->actingAs($this->akun('admin'))->get('/admin/dashboard')->assertOk()->assertSee('Dashboard Admin');
    }

    public function test_skenario_03_admin_menambah_mengubah_menghapus_buku(): void
    {
        $admin = $this->akun('admin');

        $id = $this->actingAs($admin)->postJson('/api/buku', $this->dataBuku())
            ->assertStatus(201)->json('data.id');
        $this->assertDatabaseHas('bukus', ['id' => $id, 'judul' => 'Buku Baru Uji']);

        $this->actingAs($admin)->putJson("/api/buku/{$id}", ['judul' => 'Judul Diubah'])->assertOk();
        $this->assertDatabaseHas('bukus', ['id' => $id, 'judul' => 'Judul Diubah']);

        $this->actingAs($admin)->deleteJson("/api/buku/{$id}")->assertOk();
        $this->assertDatabaseMissing('bukus', ['id' => $id]);
    }

    public function test_skenario_04_admin_mengelola_akun_dan_role_pengguna(): void
    {
        $admin = $this->akun('admin');

        $this->actingAs($admin)->post('/admin/pengguna', [
            'name' => 'Petugas Baru', 'email' => 'petugas.baru@uji.test', 'no_telepon' => '081234567891',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123', 'role' => 'petugas',
        ])->assertRedirect(route('admin.pengguna.index'));

        $baru = User::where('email', 'petugas.baru@uji.test')->firstOrFail();
        $this->assertTrue($baru->hasRole('petugas'));
        $this->assertNotNull($baru->anggota);

        $this->actingAs($admin)->put("/admin/pengguna/{$baru->id}", [
            'name' => 'Petugas Baru', 'email' => 'petugas.baru@uji.test', 'no_telepon' => '081234567891', 'role' => 'siswa',
        ])->assertRedirect(route('admin.pengguna.index'));
        $this->assertTrue($baru->fresh()->hasRole('siswa'));
        $this->assertFalse($baru->fresh()->hasRole('petugas'));

        $this->actingAs($admin)->delete("/admin/pengguna/{$baru->id}")->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseMissing('users', ['id' => $baru->id]);
    }

    public function test_admin_tidak_bisa_menghapus_atau_mengubah_role_akun_sendiri(): void
    {
        $admin = $this->akun('admin');

        $this->actingAs($admin)->delete("/admin/pengguna/{$admin->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        $this->actingAs($admin)->put("/admin/pengguna/{$admin->id}", [
            'name' => $admin->name, 'email' => $admin->email, 'no_telepon' => '081234567890', 'role' => 'siswa',
        ])->assertSessionHasErrors('role');
        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_skenario_05_admin_mengakses_peminjaman_pengembalian_dan_laporan(): void
    {
        $admin = $this->akun('admin');

        $this->actingAs($admin)->get('/kelola/peminjaman')->assertOk();
        $this->actingAs($admin)->get('/kelola/pengembalian')->assertOk();
        $this->actingAs($admin)->get('/laporan')->assertOk();
        $this->actingAs($admin)->getJson('/api/rekap/buku-per-kategori')->assertOk();
    }

    public function test_skenario_06_petugas_membuka_dashboard_petugas(): void
    {
        $petugas = $this->akun('petugas');

        $this->actingAs($petugas)->get('/petugas/dashboard')->assertOk()->assertSee('Dashboard Petugas');
        $this->actingAs($petugas)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_skenario_07_petugas_mengelola_data_buku(): void
    {
        $petugas = $this->akun('petugas');

        $id = $this->actingAs($petugas)->postJson('/api/buku', $this->dataBuku())->assertStatus(201)->json('data.id');
        $this->actingAs($petugas)->putJson("/api/buku/{$id}", ['judul' => 'Diubah Petugas'])->assertOk();
        $this->actingAs($petugas)->deleteJson("/api/buku/{$id}")->assertOk();
        $this->actingAs($petugas)->get('/kelola-buku')->assertOk();
    }

    public function test_skenario_08_petugas_memproses_peminjaman_dan_pengembalian(): void
    {
        $petugas = $this->akun('petugas');
        $siswa = $this->akun('siswa');

        $this->actingAs($siswa)->post("/buku/{$this->buku->id}/pinjam")->assertRedirect(route('peminjaman.status'));
        $p = Peminjaman::firstOrFail();
        $this->assertSame('diajukan', $p->status);

        $this->actingAs($petugas)->post("/kelola/peminjaman/{$p->id}/setujui")->assertSessionHas('sukses');
        $this->assertSame('dipinjam', $p->fresh()->status);

        $this->actingAs($petugas)->post("/kelola/pengembalian/{$p->id}")->assertSessionHas('sukses');
        $this->assertSame('dikembalikan', $p->fresh()->status);
        $this->assertNotNull($p->fresh()->tanggal_kembali);

        $lain = $this->pinjam($siswa, 'diajukan');
        $this->actingAs($petugas)->post("/kelola/peminjaman/{$lain->id}/tolak")->assertSessionHas('sukses');
        $this->assertSame('ditolak', $lain->fresh()->status);

        $this->actingAs($petugas)->get('/laporan')->assertOk();
    }

    public function test_skenario_09_petugas_tidak_bisa_mengelola_akun_atau_role(): void
    {
        $petugas = $this->akun('petugas');
        $korban = $this->akun('siswa');

        $this->actingAs($petugas)->get('/admin/pengguna')->assertForbidden();
        $this->actingAs($petugas)->get('/admin/pengguna/create')->assertForbidden();
        $this->actingAs($petugas)->get("/admin/pengguna/{$korban->id}/edit")->assertForbidden();
        $this->actingAs($petugas)->post('/admin/pengguna', [])->assertForbidden();
        $this->actingAs($petugas)->put("/admin/pengguna/{$korban->id}", ['role' => 'admin'])->assertForbidden();
        $this->actingAs($petugas)->delete("/admin/pengguna/{$korban->id}")->assertForbidden();

        $this->assertTrue($korban->fresh()->hasRole('siswa'));
    }

    public function test_skenario_10_siswa_melihat_daftar_dan_detail_buku(): void
    {
        $siswa = $this->akun('siswa');

        $this->actingAs($siswa)->get('/buku')->assertOk();
        $this->actingAs($siswa)->get("/buku/{$this->buku->id}")->assertOk()->assertSee('Laskar Pelangi');
        $this->actingAs($siswa)->getJson('/api/buku')->assertOk()->assertJsonPath('data.0.judul', 'Laskar Pelangi');
    }

    public function test_siswa_tidak_melihat_formulir_kelola_buku_tetapi_admin_melihat(): void
    {
        $this->actingAs($this->akun('siswa'))->get('/buku')->assertOk()->assertDontSee('id="form-buku"', false)->assertSee('data-kelola="false"', false);
        $this->actingAs($this->akun('admin'))->get('/buku')->assertOk()->assertSee('id="form-buku"', false)->assertSee('data-kelola="true"', false);
    }

    public function test_skenario_11_siswa_melihat_dan_mengubah_profil_sendiri(): void
    {
        $siswa = $this->akun('siswa');
        $lain = $this->akun('siswa');

        $this->actingAs($siswa)->get('/profile')->assertOk()->assertSee($siswa->email)->assertDontSee($lain->email);

        $this->actingAs($siswa)->patch('/profile', [
            'name' => 'Nama Baru', 'email' => $siswa->email, 'no_telepon' => '089999999999',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Nama Baru', $siswa->fresh()->name);
        $this->assertSame('089999999999', $siswa->fresh()->anggota->no_telepon);

        $this->assertNotSame('Nama Baru', $lain->fresh()->name);
        $this->actingAs($siswa)->patch('/profile', [
            'name' => 'Nama Baru', 'email' => $siswa->email, 'no_telepon' => '089999999999', 'id' => $lain->id,
        ]);
        $this->assertNotSame('Nama Baru', $lain->fresh()->name);
    }

    public function test_skenario_12_siswa_melihat_riwayat_milik_sendiri_saja(): void
    {
        $budi = $this->akun('siswa');
        $siti = $this->akun('siswa');

        $bukuLain = Buku::factory()->create([
            'kategori_id' => $this->kategori->id, 'judul' => 'Buku Milik Siti', 'penulis' => 'X', 'tahun_terbit' => 2020,
        ]);
        $this->pinjam($budi, 'dikembalikan');
        $this->pinjam($siti, 'dipinjam', $bukuLain);

        $this->actingAs($budi)->get('/riwayat-peminjaman')->assertOk()->assertSee('Laskar Pelangi')->assertDontSee('Buku Milik Siti');
        $this->actingAs($budi)->get('/peminjaman-saya')->assertOk()->assertDontSee('Buku Milik Siti');
    }

    public function test_skenario_13_siswa_ditolak_ke_dashboard_admin_dan_pengelolaan_buku(): void
    {
        $siswa = $this->akun('siswa');

        $this->actingAs($siswa)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($siswa)->get('/petugas/dashboard')->assertForbidden();
        $this->actingAs($siswa)->get('/kelola-buku')->assertForbidden();
        $this->actingAs($siswa)->get('/laporan')->assertForbidden();
        $this->actingAs($siswa)->get('/kelola/peminjaman')->assertForbidden();
        $this->actingAs($siswa)->get('/kelola/pengembalian')->assertForbidden();
        $this->actingAs($siswa)->get('/admin/pengguna')->assertForbidden();

        $this->actingAs($siswa)->postJson('/api/buku', $this->dataBuku())->assertForbidden();
        $this->actingAs($siswa)->putJson("/api/buku/{$this->buku->id}", ['judul' => 'Dibobol'])->assertForbidden();
        $this->actingAs($siswa)->deleteJson("/api/buku/{$this->buku->id}")->assertForbidden();
        $this->actingAs($siswa)->getJson('/api/rekap/peminjaman-per-buku')->assertForbidden();

        $this->assertDatabaseHas('bukus', ['id' => $this->buku->id, 'judul' => 'Laskar Pelangi']);
    }

    public function test_siswa_tidak_bisa_menyetujui_atau_mencatat_pengembalian(): void
    {
        $siswa = $this->akun('siswa');
        $p = $this->pinjam($siswa, 'diajukan');

        $this->actingAs($siswa)->post("/kelola/peminjaman/{$p->id}/setujui")->assertForbidden();
        $this->actingAs($siswa)->post("/kelola/pengembalian/{$p->id}")->assertForbidden();
        $this->assertSame('diajukan', $p->fresh()->status);
    }

    public function test_skenario_14_siswa_tidak_bisa_melihat_riwayat_siswa_lain(): void
    {
        $budi = $this->akun('siswa');
        $siti = $this->akun('siswa');

        $milikBudi = $this->pinjam($budi, 'dikembalikan');
        $milikSiti = $this->pinjam($siti, 'dipinjam');

        $this->actingAs($budi)->get("/riwayat-peminjaman/{$milikSiti->id}")->assertForbidden();
        $this->actingAs($siti)->get("/riwayat-peminjaman/{$milikBudi->id}")->assertForbidden();
        $this->actingAs($budi)->get("/riwayat-peminjaman/{$milikBudi->id}")->assertOk();
    }

    public function test_staf_boleh_melihat_detail_peminjaman_anggota_lain(): void
    {
        $siswa = $this->akun('siswa');
        $p = $this->pinjam($siswa);

        $this->actingAs($this->akun('petugas'))->get("/riwayat-peminjaman/{$p->id}")->assertOk();
    }

    public function test_skenario_15_setelah_logout_harus_login_kembali(): void
    {
        foreach (['admin', 'petugas', 'siswa'] as $role) {
            $user = $this->akun($role);

            $this->actingAs($user)->get('/buku')->assertOk();

            $this->post('/logout')->assertRedirect('/');
            $this->assertGuest();

            $this->get('/buku')->assertRedirect(route('login'));
            $this->get('/profile')->assertRedirect(route('login'));
        }
    }


    public function test_siswa_mengajukan_peminjaman_atas_nama_sendiri_dan_tidak_bisa_ganda(): void
    {
        $siswa = $this->akun('siswa');
        $lain = $this->akun('siswa');

        $this->actingAs($siswa)->post("/buku/{$this->buku->id}/pinjam", ['anggota_id' => $lain->anggota->id, 'status' => 'dipinjam'])
            ->assertRedirect(route('peminjaman.status'));

        $p = Peminjaman::firstOrFail();
        $this->assertSame($siswa->anggota->id, $p->anggota_id);
        $this->assertSame('diajukan', $p->status);

        $this->actingAs($siswa)->post("/buku/{$this->buku->id}/pinjam")->assertSessionHas('error');
        $this->assertSame(1, Peminjaman::count());
    }

    public function test_pengguna_tanpa_login_tidak_bisa_mengajukan_peminjaman(): void
    {
        $this->post("/buku/{$this->buku->id}/pinjam")->assertRedirect(route('login'));
        $this->assertSame(0, Peminjaman::count());
    }

    public function test_jumlah_role_dan_permission_sesuai_tabel_soal(): void
    {
        $this->assertSame(16, \Spatie\Permission\Models\Permission::count());
        $this->assertSame(3, \Spatie\Permission\Models\Role::count());

        $admin = $this->akun('admin');
        $petugas = $this->akun('petugas');
        $siswa = $this->akun('siswa');

        $this->assertTrue($admin->can('akses dashboard admin'));
        $this->assertFalse($admin->can('akses dashboard petugas'));
        $this->assertTrue($petugas->can('akses dashboard petugas'));
        $this->assertFalse($petugas->can('kelola data pengguna'));
        $this->assertFalse($petugas->can('kelola role pengguna'));
        $this->assertSame(6, $siswa->getAllPermissions()->count());
    }
}
