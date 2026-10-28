<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        // Format: [nama anggota, judul buku, tanggal pinjam, tanggal kembali (null = belum kembali)]
        $data = [
            ['Budi Santoso',   'Laskar Pelangi',  '2026-08-03', '2026-08-10'],
            ['Siti Aminah',    'Laskar Pelangi',  '2026-08-15', '2026-08-22'],
            ['Eko Prasetyo',   'Laskar Pelangi',  '2026-09-20', null],
            ['Ahmad Fauzi',    'Atomic Habits',   '2026-08-05', '2026-08-12'],
            ['Rina Wulandari', 'Atomic Habits',   '2026-09-01', '2026-09-09'],
            ['Dimas Aditya',   'Atomic Habits',   '2026-10-02', null],
            ['Putri Maharani', 'Filosofi Teras',  '2026-09-10', '2026-09-17'],
            ['Nur Aisyah',     'Filosofi Teras',  '2026-10-05', null],
            ['Budi Santoso',   'Bumi Manusia',    '2026-09-12', '2026-09-26'],
            ['Siti Aminah',    'Clean Code',      '2026-09-18', '2026-09-30'],
            ['Dimas Aditya',   'Clean Code',      '2026-10-08', null],
            ['Ahmad Fauzi',    'Negeri 5 Menara', '2026-10-01', null],
            ['Rina Wulandari', 'Laut Bercerita',  '2026-09-25', '2026-10-06'],
            ['Eko Prasetyo',   'Deep Work',       '2026-10-10', null],
        ];

        foreach ($data as [$namaAnggota, $judulBuku, $pinjam, $kembali]) {
            Peminjaman::factory()->create([
                'anggota_id'      => Anggota::where('nama', $namaAnggota)->value('id'),
                'buku_id'         => Buku::where('judul', $judulBuku)->value('id'),
                'tanggal_pinjam'  => $pinjam,
                'tanggal_kembali' => $kembali,
            ]);
        }
    }
}