<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Efqai')</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}">Perpustakaan <span>Efqai</span></a>
        <p>Temukan bacaan berikutnya.</p>
    </header>

    <nav class="site-nav" aria-label="Navigasi utama">
        <div class="nav-inner">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Beranda</a>
            <a href="{{ route('buku.index') }}" @class(['active' => request()->routeIs('buku.*')])>Daftar Buku</a>
        </div>
    </nav>

    <main class="site-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <p>&copy; {{ date('Y') }} Perpustakaan Efqai</p>
    </footer>

</body>
</html>
