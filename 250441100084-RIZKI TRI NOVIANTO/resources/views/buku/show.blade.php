@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if ($buku)
            @php
                // Normalisasi variabel agar aman dibaca baik berbentuk Object Eloquent maupun Array
                $idBuku = is_array($buku) ? $buku['id'] : $buku->id;
                $judulBuku = is_array($buku) ? $buku['judul'] : $buku->judul;
                $penulisBuku = is_array($buku) ? $buku['penulis'] : $buku->penulis;
                $tahunTerbit = is_array($buku) ? ($buku['tahun_terbit'] ?? $buku['tahun'] ?? '-') : $buku->tahun_terbit;
                
                $namaKategori = '-';
                if (is_array($buku)) {
                    $namaKategori = $buku['kategori'] ?? '-';
                } else {
                    $namaKategori = $buku->kategori->nama_kategori ?? $buku->kategori->nama ?? '-';
                }
            @endphp

            <!-- Card Utama Detail Buku -->
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200">
                
                <!-- Header Card & Tombol Kembali -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Detail Informasi Buku</h3>
                    <a href="{{ route('buku.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-md text-sm transition shadow-sm">
                        Kembali ke Daftar Buku
                    </a>
                </div>

                <!-- Tabel Detail Informasi Buku -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-gray-200">
                        <tbody>
                            <!-- ID Buku -->
                            <tr class="border-b border-gray-200">
                                <th class="p-3 text-sm font-bold text-gray-900 w-1/4 border-r border-gray-200 bg-white">
                                    ID Buku
                                </th>
                                <td class="p-3 text-sm text-gray-800">
                                    {{ $idBuku }}
                                </td>
                            </tr>

                            <!-- Kategori -->
                            <tr class="border-b border-gray-200">
                                <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200 bg-white">
                                    Kategori
                                </th>
                                <td class="p-3 text-sm text-gray-800">
                                    {{ $namaKategori }}
                                </td>
                            </tr>

                            <!-- Judul Buku -->
                            <tr class="border-b border-gray-200">
                                <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200 bg-white">
                                    Judul Buku
                                </th>
                                <td class="p-3 text-sm font-bold text-gray-900">
                                    {{ $judulBuku }}
                                </td>
                            </tr>

                            <!-- Penulis -->
                            <tr class="border-b border-gray-200">
                                <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200 bg-white">
                                    Penulis
                                </th>
                                <td class="p-3 text-sm text-gray-800">
                                    {{ $penulisBuku }}
                                </td>
                            </tr>

                            <!-- Tahun Terbit -->
                            <tr>
                                <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200 bg-white">
                                    Tahun Terbit
                                </th>
                                <td class="p-3 text-sm text-gray-800">
                                    {{ $tahunTerbit }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        @else
            <!-- Tampilan Jika Buku Tidak Ditemukan -->
            <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
                <h3 class="text-xl font-bold text-red-600 mb-2">Buku Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-4">Data detail untuk buku ini tidak ditemukan di database.</p>
                <a href="{{ route('buku.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md text-sm transition inline-block">
                    Kembali ke Daftar Buku
                </a>
            </div>
        @endif

    </div>
</div>
@endsection