<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $anggota = Anggota::whereNull('user_id')->get();
        $buku = Buku::all();

        Peminjaman::factory()->count(3)->create([
            'anggota_id' => fn () => $anggota->random()->id,
            'buku_id' => fn () => $buku->random()->id,
        ]);

        $siswa1 = User::where('email', 'siswa1@perpustakaan.test')->first()?->anggota;
        $siswa2 = User::where('email', 'siswa2@perpustakaan.test')->first()?->anggota;

        if ($siswa1 && $siswa2 && $buku->count() >= 4) {
            $data = [
                [$siswa1, $buku[0], '2026-10-01', '2026-10-08', Peminjaman::STATUS_DIKEMBALIKAN],
                [$siswa1, $buku[1], '2026-10-20', null, Peminjaman::STATUS_DIPINJAM],
                [$siswa1, $buku[2], '2026-11-01', null, Peminjaman::STATUS_DIAJUKAN],
                [$siswa2, $buku[3], '2026-10-05', '2026-10-12', Peminjaman::STATUS_DIKEMBALIKAN],
            ];

            foreach ($data as [$a, $b, $pinjam, $kembali, $status]) {
                Peminjaman::create([
                    'anggota_id' => $a->id,
                    'buku_id' => $b->id,
                    'tanggal_pinjam' => $pinjam,
                    'tanggal_kembali' => $kembali,
                    'status' => $status,
                ]);
            }
        }
    }
}
