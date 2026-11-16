<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
    @yield('title', "Cale's Library")
    </title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>


<body>


    <header class="header">

        <div class="container">

            <h1>Perpustakaan Cale</h1>

            <p>Buku-buku up to date ada di Perpustakaan Cale!</p>

        </div>

    </header>


    <nav class="navbar">

        <div class="container nav-content">

            <a href="{{ route('home') }}">
                Beranda
            </a>

            {{-- Menu tampil sesuai hak akses (Spatie: @can / @canany) --}}
            @can('lihat daftar buku')
                <a href="{{ route('buku.index') }}">
                    {{ auth()->user()->can('kelola-buku') ? 'Kelola Buku' : 'Daftar Buku' }}
                </a>
            @endcan

            @canany(['akses dashboard admin', 'akses dashboard petugas'])
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            @endcanany

            @can('melihat status peminjaman sendiri')
                <a href="{{ route('peminjaman.status') }}">
                    Peminjaman Saya
                </a>
            @endcan

            @can('melihat riwayat peminjaman sendiri')
                <a href="{{ route('peminjaman.riwayat') }}">
                    Riwayat
                </a>
            @endcan

            <span class="nav-spacer"></span>

            @auth
                <span class="nav-user">
                    {{ auth()->user()->name }}
                    @role('admin') (Admin) @endrole
                    @role('petugas') (Petugas) @endrole
                    @role('siswa') (Siswa) @endrole
                </span>

                @can('lihat profil sendiri')
                    <a href="{{ route('profile.edit') }}">
                        Profil
                    </a>
                @endcan

                <form method="POST" action="{{ route('logout') }}" class="nav-form">
                    @csrf
                    <button type="submit" class="nav-logout">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}">
                    Masuk
                </a>

                <a href="{{ route('register') }}">
                    Daftar
                </a>
            @endauth

        </div>

    </nav>


    <main class="container content">

        @if (session('sukses'))
            <div class="alert alert-sukses">{{ session('sukses') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')

    </main>


    <footer class="footer">

        <div class="container">

            <p>
                &copy; 2026 Perpustakaan CALE
            </p>

        </div>

    </footer>


</body>

</html>