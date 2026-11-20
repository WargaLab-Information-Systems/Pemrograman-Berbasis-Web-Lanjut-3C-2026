@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Card Selamat Datang -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h3>
            <p class="text-gray-600 mt-1">
                Selamat datang di Perpustakaan Digital, <span class="font-semibold">{{ Auth::user()->name }}</span>. Anda dapat melihat koleksi buku, memeriksa riwayat peminjaman, serta memperbarui data profil Anda.
            </p>
        </div>

        <!-- Grid Fitur Siswa -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Koleksi Buku -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Koleksi Buku</p>
                    <h4 class="text-3xl font-bold text-blue-600 mt-2 mb-1">5</h4>
                    <p class="text-sm text-gray-500 mb-4">Buku tersedia untuk dipinjam.</p>
                </div>
                <div>
                    <a href="{{ route('buku.index') }}" class="inline-block border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md text-sm font-medium transition">
                        Buka Katalog Buku
                    </a>
                </div>
            </div>

            <!-- Riwayat Peminjaman -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Riwayat Peminjaman</p>
                    <p class="text-sm text-gray-500 mt-2 mb-4">
                        Periksa daftar buku yang sedang atau pernah Anda pinjam.
                    </p>
                </div>
                <div>
                    <a href="{{ route('peminjaman.saya') }}" class="inline-block border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-4 py-2 rounded-md text-sm font-medium transition">
                        Lihat Riwayat Saya
                    </a>
                </div>
            </div>

            <!-- Akun Saya -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Akun Saya</p>
                    <p class="text-sm text-gray-500 mt-2 mb-4">
                        Kelola nama dan alamat email akun Anda secara mandiri.
                    </p>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="inline-block border border-gray-400 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition">
                        Perbarui Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection