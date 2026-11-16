<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Perpustakaan')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>Perpustakaan Digital</h1>
            <p>Membaca untuk menambah wawasan</p>
        </div>
    </header>

    <nav class="navbar">
        <div class="container nav-content">

            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>

                @can('lihat daftar buku')
                    <a href="{{ route('buku.index') }}">Daftar Buku</a>
                @endcan

                @can('kelola data pengguna')
                    <a href="{{ route('users.index') }}">Pengguna</a>
                @endcan

                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
                @endcan

                @can('kelola pengembalian')
                    <a href="{{ route('pengembalian.index') }}">Pengembalian</a>
                @endcan

                @can('lihat laporan perpustakaan')
                    <a href="{{ route('laporan.index') }}">Laporan</a>
                @endcan

                @can('melihat status peminjaman sendiri')
                    <a href="{{ route('peminjaman.status') }}">Status Pinjam</a>
                @endcan

                @can('melihat riwayat peminjaman sendiri')
                    <a href="{{ route('riwayat.saya') }}">Riwayat</a>
                @endcan

                @can('lihat profil sendiri')
                    <a href="{{ route('profile.edit') }}">Profil</a>
                @endcan

                <span class="nav-spacer"></span>

                <span class="nav-user">
                    {{ auth()->user()->name }}
                    @foreach (auth()->user()->getRoleNames() as $namaRole)
                        <span class="badge badge-role">{{ ucfirst($namaRole) }}</span>
                    @endforeach
                </span>

                <form method="POST" action="{{ route('logout') }}" class="nav-form">
                    @csrf
                    <button type="submit" class="nav-button">Logout</button>
                </form>
            @endauth

            @guest
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('buku.index') }}">Daftar Buku</a>

                <span class="nav-spacer"></span>

                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Registrasi</a>
            @endguest

        </div>
    </nav>

    <main class="container content">

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

        @yield('content')

    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Perpustakaan Digital</p>
        </div>
    </footer>

</body>
</html>
