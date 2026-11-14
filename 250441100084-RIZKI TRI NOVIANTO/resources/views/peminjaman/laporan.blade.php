@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            
            <!-- Header & Badge Total -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Laporan Rekapitulasi Perpustakaan</h3>
                <span class="mt-2 md:mt-0 bg-slate-700 text-white font-semibold px-3 py-1.5 rounded-md text-xs">
                    Total Pengguna: {{ $totalUser }} | Total Buku: {{ $totalBuku }}
                </span>
            </div>

            <!-- Tabel Laporan Rekapitulasi -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-3 text-sm font-bold text-gray-900 text-center border-r border-gray-200" style="width: 60px;">No</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Kategori</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Judul Buku</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Penulis</th>
                            <th class="p-3 text-sm font-bold text-gray-900 text-center border-r border-gray-200">Tahun Terbit</th>
                            <th class="p-3 text-sm font-bold text-gray-900 text-center">Status Koleksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $index => $buku)
                        @php
                            // Mengecek apakah buku sedang dalam status Dipinjam atau Pengajuan
                            $pinjamanAktif = $buku->peminjamans->first(function($p) {
                                return in_array($p->status, ['Dipinjam', 'Pengajuan']);
                            });

                            if ($pinjamanAktif) {
                                if ($pinjamanAktif->status === 'Dipinjam') {
                                    $statusKoleksi = 'Dipinjam';
                                    $badgeColor = 'bg-cyan-500 text-white';
                                } else {
                                    $statusKoleksi = 'Dipinjam'; // Atau 'Pengajuan' jika ingin dibedakan
                                    $badgeColor = 'bg-amber-400 text-gray-900';
                                }
                            } else {
                                $statusKoleksi = 'Tersedia';
                                $badgeColor = 'bg-emerald-600 text-white';
                            }
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="p-3 text-sm text-gray-800 text-center border-r border-gray-200">{{ $index + 1 }}</td>
                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">
                                {{ $buku->kategori ? ($buku->kategori->nama_kategori ?? $buku->kategori->nama) : 'Tanpa Kategori' }}
                            </td>
                            <td class="p-3 text-sm text-gray-900 font-medium border-r border-gray-200">{{ $buku->judul ?? $buku->judul_buku ?? '-' }}</td>
                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">{{ $buku->penulis ?? $buku->nama_penulis ?? '-' }}</td>
                            <td class="p-3 text-sm text-gray-800 text-center border-r border-gray-200">{{ $buku->tahun_terbit ?? $buku->tahun_rilis ?? $buku->tahun ?? '-' }}</td>
                            <td class="p-3 text-sm text-center">
                                <span class="{{ $badgeColor }} font-bold px-3 py-1 rounded text-xs inline-block">
                                    {{ $statusKoleksi }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500 text-sm">Belum ada data koleksi buku dalam sistem.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection