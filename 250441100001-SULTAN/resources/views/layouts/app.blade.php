<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Perpustakaan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex flex-col justify-between text-gray-800">

    <div>
        <!-- Header Banner Biru -->
        <header class="bg-blue-600 py-6 text-center text-white shadow-md">
            <h1 class="text-3xl font-bold tracking-wide">Sistem Perpustakaan</h1>
        </header>

        <!-- Navigasi Utama Rata Tengah -->
        <nav class="bg-white border-b border-gray-200 py-3 flex justify-center items-center space-x-6 text-sm font-semibold text-gray-700">
            {{-- Link Beranda --}}
            <a href="{{ route('dashboard') }}" 
               class="{{ request()->routeIs('dashboard*') || request()->routeIs('*.dashboard') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1' : 'hover:text-blue-600' }}">
                Beranda
            </a>

            {{-- Link Daftar Buku (Langsung ke Web URL /buku) --}}
            @can('Lihat daftar buku')
                <a href="{{ url('/buku') }}" 
                   class="{{ request()->is('buku*') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1' : 'hover:text-blue-600' }}">
                    Daftar Buku
                </a>
            @endcan

            {{-- Link Riwayat Peminjaman (Khusus Siswa) --}}
            @can('Melihat riwayat peminjaman sendiri')
                <a href="{{ route('peminjaman.riwayat') }}" 
                   class="{{ request()->routeIs('peminjaman.riwayat') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1' : 'hover:text-blue-600' }}">
                    Riwayat Saya
                </a>
            @endcan

            {{-- Link Laporan Peminjaman (Petugas & Admin) --}}
            @can('Lihat laporan perpustakaan')
                <a href="{{ route('laporan.index') }}" 
                   class="{{ request()->routeIs('laporan.*') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1' : 'hover:text-blue-600' }}">
                    Laporan Peminjaman
                </a>
            @endcan

            {{-- Link Kelola Akun (Khusus Admin) --}}
            @role('admin')
                <a href="{{ route('admin.users') }}" 
                   class="{{ request()->routeIs('admin.users') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1' : 'hover:text-blue-600' }}">
                    Kelola Akun
                </a>
            @endrole

            {{-- Link Edit Profil --}}
            @can('Lihat profil sendiri')
                <a href="{{ route('profile.edit') }}" 
                   class="{{ request()->routeIs('profile.*') ? 'text-blue-600 font-bold border-b-2 border-blue-600 pb-1' : 'hover:text-blue-600' }}">
                    Edit Profil
                </a>
            @endcan

            {{-- Tombol Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:text-red-600 font-semibold transition">
                    Logout ({{ Auth::user()->name }})
                </button>
            </form>
        </nav>

        <!-- Slot Isi Konten Tiap Halaman -->
        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white py-4 text-center text-xs text-gray-500 border-t border-gray-200">
        © 2026 Sistem Informasi
    </footer>

</body>
</html>