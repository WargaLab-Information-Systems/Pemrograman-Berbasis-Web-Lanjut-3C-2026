<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Perpustakaan Digital') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 flex flex-col min-h-screen">
        <div class="min-h-screen bg-gray-100 flex flex-col flex-1">
            <!-- Navigasi Utama -->
            @include('layouts.navigation')

            <!-- Page Heading (Jika Ada) -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow py-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>

            <!-- Footer Aplikasi -->
            <footer class="bg-gray-800 text-white text-center py-4 mt-auto">
                <small class="text-sm font-medium">© 2026 Perpustakaan Digital By Rizki</small>
            </footer>
        </div>
    </body>
</html>