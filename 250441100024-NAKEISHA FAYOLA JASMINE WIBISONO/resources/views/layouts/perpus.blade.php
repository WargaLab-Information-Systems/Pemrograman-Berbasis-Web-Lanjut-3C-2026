<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - e-Pustaka</title>

    @vite(['resources/css/app.css'])
</head>

<body>

    <header class="app-header">
        <div class="container">
            <h1 class="app-name">e-Pustaka</h1>
        </div>
    </header>

    <nav class="app-navbar">
        <div class="container" style="display:flex; align-items:center; flex-wrap:wrap;">

            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                Beranda
            </a>
            <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">
                Daftar Buku
            </a>

            <span style="margin-left:auto; display:flex; align-items:center;">
                @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                    @csrf
                    <button type="submit"
                        style="background:none; border:0; color:#f1e6d6; cursor:pointer; padding:12px 16px; font:inherit;">
                        Logout ({{ Auth::user()->name }})
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Daftar</a>
                @endauth
            </span>
        </div>
    </nav>

    <main class="container app-content">
        @yield('content')
    </main>

    <footer class="app-footer">
        <div class="container">
            &copy; {{ date('Y') }} e-Pustaka
        </div>
    </footer>

</body>

</html>