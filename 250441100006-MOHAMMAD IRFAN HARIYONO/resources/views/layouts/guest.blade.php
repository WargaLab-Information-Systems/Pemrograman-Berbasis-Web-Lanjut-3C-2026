<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Perpustakaan Digital</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <!-- Logo Minimalis (SVG) -->
            <div class="mb-4 text-center">
                <a href="/" class="flex flex-col items-center gap-2 text-gray-800 hover:text-gray-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <!-- Teks diubah menjadi Perpustakaan Digital -->
                    <span class="text-xl font-semibold tracking-wide">Perpustakaan Digital</span>
                </a>
            </div>

            <!-- Kotak Form -->
            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-sm overflow-hidden sm:rounded-xl border border-gray-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>