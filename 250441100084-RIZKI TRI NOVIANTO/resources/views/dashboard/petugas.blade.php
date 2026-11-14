@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Card Selamat Datang -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900">Dashboard Petugas Perpustakaan</h3>
                <p class="text-gray-600 mt-1">
                    Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>. Anda dapat mengelola buku,
                    peminjaman, pengembalian, dan laporan.
                </p>
            </div>

            <!-- Ringkasan Operasional -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Buku Tersedia -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Total Buku Tersedia</p>
                        <h4 class="text-3xl font-bold text-blue-600 mt-2 mb-4">5 Judul</h4>
                    </div>
                    <a href="{{ route('buku.index') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        Daftar Buku
                    </a>
                </div>
            </div>

            <!-- Operasional Sirkulasi -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Operasional Sirkulasi</p>
                    <p class="text-sm text-gray-500 mt-1 mb-4">
                        Kelola peminjaman, pengembalian buku, dan laporan perpustakaan.
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('peminjaman.manage') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        Kelola Peminjaman
                    </a>
                    <a href="{{ route('laporan.index') }}"
                        class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
