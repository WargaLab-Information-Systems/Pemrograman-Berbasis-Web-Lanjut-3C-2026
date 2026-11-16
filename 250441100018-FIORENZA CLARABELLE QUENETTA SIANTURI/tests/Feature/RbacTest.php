<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    private function buku(): Buku
    {
        return Buku::factory()->create([
            'kategori_id' => Kategori::factory()->create(['nama' => 'Novel'])->id,
        ]);
    }

    private function pinjaman(User $user, string $status = 'dipinjam'): Peminjaman
    {
        return Peminjaman::factory()->create([
            'anggota_id' => $user->ensureAnggota()->id,
            'buku_id' => $this->buku()->id,
            'status' => $status,
            'tanggal_kembali' => $status === 'dikembalikan' ? now()->toDateString() : null,
        ]);
    }

    public function test_seeder_membuat_3_role_dan_16_permission_sesuai_tabel(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $this->assertSame(16, Permission::count());
        $this->assertSame(['admin', 'petugas', 'siswa'], Role::orderBy('name')->pluck('name')->all());

        $this->assertCount(15, Role::findByName('admin')->permissions);
        $this->assertCount(13, Role::findByName('petugas')->permissions);
        $this->assertCount(6, Role::findByName('siswa')->permissions);

        $this->assertTrue(Role::findByName('admin')->hasPermissionTo('akses dashboard admin'));
        $this->assertFalse(Role::findByName('admin')->hasPermissionTo('akses dashboard petugas'));
        $this->assertTrue(Role::findByName('petugas')->hasPermissionTo('akses dashboard petugas'));
        $this->assertFalse(Role::findByName('petugas')->hasPermissionTo('kelola data pengguna'));
        $this->assertFalse(Role::findByName('petugas')->hasPermissionTo('kelola role pengguna'));
        $this->assertFalse(Role::findByName('siswa')->hasPermissionTo('kelola data buku'));
        $this->assertFalse(Role::findByName('siswa')->hasPermissionTo('lihat laporan perpustakaan'));
    }

    public function test_skenario_1_belum_login_dialihkan_ke_login(): void
    {
        foreach (['/admin/dashboard', '/petugas/dashboard', '/dashboard', '/buku', '/profile',
                     '/riwayat', '/laporan', '/admin/users', '/peminjaman'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_api_buku_tanpa_login_ditolak(): void
    {
        $this->getJson('/api/buku')->assertUnauthorized();
        $this->postJson('/api/buku', [])->assertUnauthorized();
        $this->deleteJson('/api/buku/1')->assertUnauthorized();
    }

    public function test_guest_middleware_user_login_tidak_bisa_buka_login_dan_register(): void
    {
        $user = $this->buatUser('siswa');

        $this->actingAs($user)->get('/login')->assertRedirect();
        $this->actingAs($user)->get('/register')->assertRedirect();
    }

    public function test_skenario_2_admin_dapat_membuka_dashboard_admin(): void
    {
        $admin = $this->buatUser('admin');

        $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->assertSee('Dashboard Admin');

        $this->actingAs($admin)->get('/petugas/dashboard')->assertForbidden();
    }

    public function test_skenario_3_admin_menambah_mengubah_menghapus_buku(): void
    {
        $admin = $this->buatUser('admin');
        $kategori = Kategori::factory()->create();

        $payload = ['kategori_id' => $kategori->id, 'judul' => 'Buku Uji', 'penulis' => 'Penulis Uji', 'tahun_terbit' => 2020];

        $id = $this->actingAs($admin)->postJson('/api/buku', $payload)
            ->assertCreated()->json('data.id');

        $this->actingAs($admin)->putJson("/api/buku/$id", [...$payload, 'judul' => 'Judul Baru'])->assertOk();
        $this->assertDatabaseHas('bukus', ['id' => $id, 'judul' => 'Judul Baru']);

        $this->actingAs($admin)->deleteJson("/api/buku/$id")->assertOk();
        $this->assertDatabaseMissing('bukus', ['id' => $id]);
    }

    public function test_skenario_4_admin_mengelola_akun_dan_role_pengguna(): void
    {
        $admin = $this->buatUser('admin');

        $this->actingAs($admin)->get('/admin/users')->assertOk();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Petugas Baru', 'email' => 'pb@example.com', 'role' => 'petugas',
            'password' => 'password', 'password_confirmation' => 'password',
        ])->assertRedirect(route('users.index'));

        $baru = User::where('email', 'pb@example.com')->firstOrFail();
        $this->assertTrue($baru->hasRole('petugas'));

        $this->actingAs($admin)->put("/admin/users/{$baru->id}", ['name' => 'Nama Ubah', 'email' => 'pb@example.com'])
            ->assertRedirect(route('users.index'));
        $this->assertSame('Nama Ubah', $baru->fresh()->name);

        $this->actingAs($admin)->patch("/admin/users/{$baru->id}/role", ['role' => 'siswa'])
            ->assertRedirect(route('users.index'));
        $this->assertTrue($baru->fresh()->hasRole('siswa'));
        $this->assertFalse($baru->fresh()->hasRole('petugas'));

        $this->actingAs($admin)->delete("/admin/users/{$baru->id}")->assertRedirect(route('users.index'));
        $this->assertNull(User::find($baru->id));
    }

    public function test_admin_tidak_bisa_menurunkan_role_atau_menghapus_dirinya_sendiri(): void
    {
        $admin = $this->buatUser('admin');

        $this->actingAs($admin)->patch("/admin/users/{$admin->id}/role", ['role' => 'siswa'])
            ->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->hasRole('admin'));

        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")->assertSessionHas('error');
        $this->assertNotNull(User::find($admin->id));
    }

    public function test_role_tidak_valid_ditolak(): void
    {
        $admin = $this->buatUser('admin');
        $siswa = $this->buatUser('siswa');

        $this->actingAs($admin)->patch("/admin/users/{$siswa->id}/role", ['role' => 'superadmin'])
            ->assertSessionHasErrors('role');
        $this->assertTrue($siswa->fresh()->hasRole('siswa'));
    }

    public function test_skenario_5_admin_mengakses_peminjaman_pengembalian_dan_laporan(): void
    {
        $admin = $this->buatUser('admin');
        $siswa = $this->buatUser('siswa');
        $this->pinjaman($siswa);

        $this->actingAs($admin)->get('/peminjaman')->assertOk();
        $this->actingAs($admin)->get('/pengembalian')->assertOk();
        $this->actingAs($admin)->get('/laporan')->assertOk();
        $this->actingAs($admin)->getJson('/api/rekap/buku-per-kategori')->assertOk();
    }

    public function test_skenario_6_petugas_dapat_membuka_dashboard_petugas(): void
    {
        $petugas = $this->buatUser('petugas');

        $this->actingAs($petugas)->get('/dashboard')->assertRedirect(route('petugas.dashboard'));
        $this->actingAs($petugas)->get('/petugas/dashboard')->assertOk()->assertSee('Dashboard Petugas');
        $this->actingAs($petugas)->get('/admin/dashboard')->assertForbidden();
    }

    public function test_skenario_7_petugas_mengelola_data_buku(): void
    {
        $petugas = $this->buatUser('petugas');
        $kategori = Kategori::factory()->create();

        $payload = ['kategori_id' => $kategori->id, 'judul' => 'Buku Petugas', 'penulis' => 'Penulis', 'tahun_terbit' => 2021];

        $id = $this->actingAs($petugas)->postJson('/api/buku', $payload)->assertCreated()->json('data.id');
        $this->actingAs($petugas)->putJson("/api/buku/$id", [...$payload, 'judul' => 'Diubah'])->assertOk();
        $this->actingAs($petugas)->deleteJson("/api/buku/$id")->assertOk();
    }

    public function test_skenario_8_petugas_memproses_peminjaman_dan_pengembalian(): void
    {
        $petugas = $this->buatUser('petugas');
        $siswa = $this->buatUser('siswa');

        $buku = $this->buku();
        $this->actingAs($siswa)->post('/peminjaman', ['buku_id' => $buku->id])->assertRedirect(route('peminjaman.status'));
        $p = Peminjaman::firstOrFail();
        $this->assertSame('diajukan', $p->status);

        $this->actingAs($petugas)->post("/peminjaman/{$p->id}/setujui")->assertSessionHas('success');
        $this->assertSame('dipinjam', $p->fresh()->status);
        $this->assertSame($petugas->id, $p->fresh()->diproses_oleh);

        $this->actingAs($petugas)->post("/peminjaman/{$p->id}/kembali")->assertSessionHas('success');
        $this->assertSame('dikembalikan', $p->fresh()->status);
        $this->assertNotNull($p->fresh()->tanggal_kembali);

        $p2 = $this->pinjaman($siswa, 'diajukan');
        $this->actingAs($petugas)->post("/peminjaman/{$p2->id}/tolak")->assertSessionHas('success');
        $this->assertSame('ditolak', $p2->fresh()->status);
    }

    public function test_alur_peminjaman_tidak_bisa_dilompati_dan_tidak_untuk_diri_sendiri(): void
    {
        $petugas = $this->buatUser('petugas');
        $siswa = $this->buatUser('siswa');

        $p = $this->pinjaman($siswa, 'diajukan');
        $this->actingAs($petugas)->post("/peminjaman/{$p->id}/kembali")->assertSessionHas('error');
        $this->assertSame('diajukan', $p->fresh()->status);

        $milikPetugas = $this->pinjaman($petugas, 'diajukan');
        $this->actingAs($petugas)->post("/peminjaman/{$milikPetugas->id}/setujui")->assertSessionHas('error');
        $this->assertSame('diajukan', $milikPetugas->fresh()->status);
    }

    public function test_pengajuan_ganda_untuk_buku_yang_sama_ditolak(): void
    {
        $siswa = $this->buatUser('siswa');
        $buku = $this->buku();

        $this->actingAs($siswa)->post('/peminjaman', ['buku_id' => $buku->id]);
        $this->actingAs($siswa)->post('/peminjaman', ['buku_id' => $buku->id])->assertSessionHas('error');

        $this->assertSame(1, Peminjaman::count());
    }

    public function test_skenario_9_petugas_tidak_bisa_mengelola_akun_atau_role(): void
    {
        $petugas = $this->buatUser('petugas');
        $siswa = $this->buatUser('siswa');

        $this->actingAs($petugas)->get('/admin/users')->assertForbidden();
        $this->actingAs($petugas)->get('/admin/users/create')->assertForbidden();
        $this->actingAs($petugas)->post('/admin/users', [
            'name' => 'X', 'email' => 'x@example.com', 'password' => 'password', 'password_confirmation' => 'password',
        ])->assertForbidden();
        $this->actingAs($petugas)->get("/admin/users/{$siswa->id}/edit")->assertForbidden();
        $this->actingAs($petugas)->delete("/admin/users/{$siswa->id}")->assertForbidden();
        $this->actingAs($petugas)->patch("/admin/users/{$siswa->id}/role", ['role' => 'admin'])->assertForbidden();

        $this->assertTrue($siswa->fresh()->hasRole('siswa'));
        $this->assertFalse($petugas->fresh()->hasRole('admin'));
    }

    public function test_petugas_dapat_melihat_laporan(): void
    {
        $this->actingAs($this->buatUser('petugas'))->get('/laporan')->assertOk();
    }

    public function test_skenario_10_siswa_melihat_daftar_dan_detail_buku(): void
    {
        $siswa = $this->buatUser('siswa');
        $buku = $this->buku();

        $this->actingAs($siswa)->get('/buku')->assertOk();
        $this->actingAs($siswa)->get("/buku/{$buku->id}")->assertOk()->assertSee($buku->judul);
        $this->actingAs($siswa)->getJson('/api/buku')->assertOk();
        $this->actingAs($siswa)->getJson("/api/buku/{$buku->id}")->assertOk();
    }

    public function test_skenario_11_siswa_melihat_dan_memperbarui_profil_sendiri(): void
    {
        $siswa = $this->buatUser('siswa');
        $lain = $this->buatUser('siswa');

        $this->actingAs($siswa)->get('/profile')->assertOk()->assertSee($siswa->email)->assertDontSee($lain->email);

        $this->actingAs($siswa)->patch('/profile', [
            'name' => 'Nama Baru', 'email' => $siswa->email, 'no_telepon' => '0899',
        ])->assertSessionHasNoErrors()->assertRedirect('/profile');

        $this->assertSame('Nama Baru', $siswa->fresh()->name);
        $this->assertSame('Nama Baru', $siswa->fresh()->anggota->nama);
        $this->assertSame('0899', $siswa->fresh()->anggota->no_telepon);

        $this->assertNotSame('Nama Baru', $lain->fresh()->name);
    }

    public function test_profil_tidak_bisa_dipakai_untuk_menaikkan_role(): void
    {
        $siswa = $this->buatUser('siswa');

        $this->actingAs($siswa)->patch('/profile', [
            'name' => $siswa->name, 'email' => $siswa->email, 'role' => 'admin', 'roles' => ['admin'],
        ]);

        $this->assertTrue($siswa->fresh()->hasRole('siswa'));
        $this->assertFalse($siswa->fresh()->hasRole('admin'));
    }

    public function test_skenario_12_siswa_melihat_riwayat_dan_status_miliknya_sendiri(): void
    {
        $siswa = $this->buatUser('siswa');
        $milik = $this->pinjaman($siswa, 'dikembalikan');
        $aktif = $this->pinjaman($siswa, 'dipinjam');

        $res = $this->actingAs($siswa)->get('/riwayat')->assertOk();
        $res->assertSee($milik->buku->judul)->assertSee($aktif->buku->judul);

        $this->actingAs($siswa)->get('/status-peminjaman')->assertOk()
            ->assertSee($aktif->buku->judul)->assertDontSee($milik->buku->judul);

        $this->actingAs($siswa)->get("/riwayat/{$siswa->id}")->assertOk();
        $this->actingAs($siswa)->get("/peminjaman/{$milik->id}")->assertOk();
    }

    public function test_riwayat_siswa_tidak_memuat_data_siswa_lain(): void
    {
        $siswa = $this->buatUser('siswa');
        $lain = $this->buatUser('siswa');
        $punyaLain = $this->pinjaman($lain);

        $this->actingAs($siswa)->get('/riwayat')->assertOk()->assertDontSee($punyaLain->buku->judul);
    }

    public function test_skenario_13_siswa_tidak_bisa_buka_dashboard_admin_atau_kelola_buku(): void
    {
        $siswa = $this->buatUser('siswa');
        $kategori = Kategori::factory()->create();
        $buku = $this->buku();

        $this->actingAs($siswa)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($siswa)->get('/petugas/dashboard')->assertForbidden();
        $this->actingAs($siswa)->get('/admin/users')->assertForbidden();
        $this->actingAs($siswa)->get('/peminjaman')->assertForbidden();
        $this->actingAs($siswa)->get('/pengembalian')->assertForbidden();
        $this->actingAs($siswa)->get('/laporan')->assertForbidden();
        $this->actingAs($siswa)->getJson('/api/rekap/peminjaman-per-buku')->assertForbidden();

        $this->actingAs($siswa)->postJson('/api/buku', [
            'kategori_id' => $kategori->id, 'judul' => 'Ilegal', 'penulis' => 'Penyusup', 'tahun_terbit' => 2020,
        ])->assertForbidden();
        $this->actingAs($siswa)->putJson("/api/buku/{$buku->id}", [
            'kategori_id' => $kategori->id, 'judul' => 'Diubah', 'penulis' => 'Penyusup', 'tahun_terbit' => 2020,
        ])->assertForbidden();
        $this->actingAs($siswa)->deleteJson("/api/buku/{$buku->id}")->assertForbidden();

        $this->assertDatabaseMissing('bukus', ['judul' => 'Ilegal']);
        $this->assertDatabaseHas('bukus', ['id' => $buku->id, 'judul' => $buku->judul]);
    }

    public function test_siswa_tidak_melihat_tombol_kelola_buku_dan_menu_admin(): void
    {
        $siswa = $this->buatUser('siswa');

        $html = $this->actingAs($siswa)->get('/buku')->assertOk()->getContent();

        $this->assertStringNotContainsString(route('buku.create'), $html);
        $this->assertStringNotContainsString('name="_method" value="DELETE"', $html);
        $this->assertStringNotContainsString('/admin/users', $html);
        $this->assertStringNotContainsString('href="'.route('laporan.index').'"', $html);
    }

    public function test_admin_melihat_form_kelola_buku_dan_menu_pengguna(): void
    {
        $html = $this->actingAs($this->buatUser('admin'))->get('/buku')->assertOk()->getContent();

        $this->assertStringContainsString(route('buku.create'), $html);
        $this->assertStringContainsString(route('users.index'), $html);
    }

    public function test_skenario_14_siswa_tidak_bisa_melihat_riwayat_siswa_lain(): void
    {
        $siswa = $this->buatUser('siswa');
        $lain = $this->buatUser('siswa');
        $punyaLain = $this->pinjaman($lain);

        $this->actingAs($siswa)->get("/riwayat/{$lain->id}")->assertForbidden();

        $this->actingAs($siswa)->get("/peminjaman/{$punyaLain->id}")->assertForbidden();

        $this->actingAs($siswa)->post("/peminjaman/{$punyaLain->id}/kembali")->assertForbidden();
        $this->assertSame('dipinjam', $punyaLain->fresh()->status);
    }

    public function test_admin_dan_petugas_dapat_melihat_riwayat_pengguna_lain(): void
    {
        $siswa = $this->buatUser('siswa');
        $p = $this->pinjaman($siswa);

        foreach (['admin', 'petugas'] as $role) {
            $u = $this->buatUser($role);
            $this->actingAs($u)->get("/riwayat/{$siswa->id}")->assertOk()->assertSee($p->buku->judul);
            $this->actingAs($u)->get("/peminjaman/{$p->id}")->assertOk();
        }
    }

    public function test_semua_role_dapat_mengajukan_peminjaman(): void
    {
        $buku = $this->buku();

        foreach (['admin', 'petugas', 'siswa'] as $role) {
            $u = $this->buatUser($role);
            $this->actingAs($u)->post('/peminjaman', ['buku_id' => $buku->id])->assertRedirect(route('peminjaman.status'));
            $this->assertSame(1, $u->anggota->peminjamans()->count());
        }
    }

    public function test_skenario_15_setelah_logout_harus_login_kembali(): void
    {
        foreach (['admin' => '/admin/dashboard', 'petugas' => '/petugas/dashboard', 'siswa' => '/riwayat'] as $role => $url) {
            $user = $this->buatUser($role);

            $this->actingAs($user)->get($url)->assertOk();

            $this->post('/logout')->assertRedirect('/');
            $this->assertGuest();

            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_login_valid_diarahkan_sesuai_role_dan_kredensial_salah_ditolak(): void
    {
        $admin = $this->buatUser('admin', ['email' => 'a@example.com']);

        $this->post('/login', ['email' => 'a@example.com', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/login', ['email' => 'a@example.com', 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($admin);
        $this->get('/dashboard')->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_tanpa_role_tidak_punya_akses_apa_pun(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/buku')->assertForbidden();
        $this->actingAs($user)->get('/riwayat')->assertForbidden();
        $this->actingAs($user)->get('/profile')->assertForbidden();
    }

    public function test_halaman_403_tampil_dalam_bahasa_indonesia(): void
    {
        $this->actingAs($this->buatUser('siswa'))->get('/admin/dashboard')
            ->assertForbidden()->assertSee('Akses Ditolak');
    }
}
