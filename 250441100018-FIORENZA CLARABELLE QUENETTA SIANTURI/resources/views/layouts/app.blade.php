<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            <a href="{{ route('home') }}">Beranda</a>

            <a href="{{ route('buku.index') }}">Daftar Buku</a>

        </div>
    </nav>

    <main class="container content">

        @yield('content')

    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Perpustakaan Digital</p>
        </div>
    </footer>

</body>
</html>  