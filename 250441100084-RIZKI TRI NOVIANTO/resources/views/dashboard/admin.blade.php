@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Card Selamat Datang -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900">Dashboard Administrator</h3>
                <p class="text-gray-600 mt-1">
                    Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>. Anda memiliki akses penuh ke
                    seluruh administrasi perpustakaan.
                </p>
            </div>

            <!-- Ringkasan Stats & Menu Cepat -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Koleksi Buku -->
                <div class="bg-blue-600 text-white p-6 rounded-lg shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium opacity-90">Total Koleksi Buku</p>
                    <h4 class="text-5xl font-bold mt-2">5</h4>
                </div>

                <!-- Total Pengguna Terdaftar -->
                <div class="bg-emerald-600 text-white p-6 rounded-lg shadow-sm flex flex-col justify-between">
                    <p class="text-sm font-medium opacity-90">Total Pengguna Terdaftar</p>
                    <h4 class="text-5xl font-bold mt-2">6</h4>
                </div>

                <!-- Menu Cepat -->
                <div class="bg-slate-800 text-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm font-semibold mb-4">Menu Cepat</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('buku.index') }}"
                            class="bg-white text-gray-900 hover:bg-gray-100 px-3 py-1.5 rounded-md text-sm font-semibold transition">
                            Daftar Buku
                        </a>
                        <a href="{{ route('admin.pengguna') }}"
                            class="bg-amber-400 text-gray-900 hover:bg-amber-300 px-3 py-1.5 rounded-md text-sm font-semibold transition">
                            Kelola Role User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
