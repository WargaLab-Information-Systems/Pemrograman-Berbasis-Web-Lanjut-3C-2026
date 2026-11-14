@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            
            <!-- Header & Badge Pengguna -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Status & Riwayat Peminjaman Saya</h3>
                <span class="mt-2 md:mt-0 bg-gray-900 text-white font-semibold px-3 py-1 rounded-md text-xs">
                    Pengguna: {{ $user->name }} (ID: {{ $user->id }})
                </span>
            </div>

            <!-- Tabel Riwayat Saya -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-3 text-sm font-bold text-gray-900 text-center border-r border-gray-200" style="width: 60px;">No</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Kategori</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Judul Buku</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Penulis</th>
                            <th class="p-3 text-sm font-bold text-gray-900 text-center">Status Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $index => $item)
                        @php
                            $badgeColor = match($item->status) {
                                'Disetujui' => 'bg-cyan-500 text-white',
                                'Dikembalikan' => 'bg-emerald-600 text-white',
                                'Pengajuan' => 'bg-amber-400 text-gray-900',
                                default => 'bg-gray-200 text-gray-700'
                            };
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="p-3 text-sm text-gray-800 text-center border-r border-gray-200">{{ $index + 1 }}</td>
                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">
                                {{ $item->buku->kategori ? ($item->buku->kategori->nama_kategori ?? $item->buku->kategori->nama) : 'Tanpa Kategori' }}
                            </td>
                            <td class="p-3 text-sm text-gray-900 font-medium border-r border-gray-200">{{ $item->buku->judul ?? '-' }}</td>
                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">{{ $item->buku->penulis ?? '-' }}</td>
                            <td class="p-3 text-sm text-center">
                                <span class="{{ $badgeColor }} font-bold px-2.5 py-1 rounded text-xs">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500 text-sm">Belum ada riwayat peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection