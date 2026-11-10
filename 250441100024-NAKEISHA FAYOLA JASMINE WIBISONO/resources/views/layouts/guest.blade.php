<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'e-Pustaka') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
            <a href="{{ route('home') }}" class="flex flex-col items-center">
                <x-application-logo class="w-16 h-16 fill-current text-brand" />
                <span class="mt-2 text-2xl font-semibold text-brand">e-Pustaka</span>
            </a>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white border border-gray-200 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <a href="{{ route('home') }}" class="mt-4 mb-6 text-sm text-gray-600 underline hover:text-gray-900">
                &larr; Kembali ke Beranda e-Pustaka
            </a>
        </div>
    </body>
</html>
