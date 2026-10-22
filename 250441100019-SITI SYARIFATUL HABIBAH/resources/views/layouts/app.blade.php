<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            <a href="{{ route('buku.index') }}">
                Daftar Buku
            </a>

        </div>

    </nav>


    <main class="container content">

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