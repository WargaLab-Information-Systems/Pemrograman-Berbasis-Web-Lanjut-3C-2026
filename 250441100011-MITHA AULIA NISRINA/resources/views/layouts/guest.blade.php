<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KATAMITHA') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <div class="auth-page">

        <div class="auth-brand">

            <a href="{{ route('home') }}" class="auth-brand-link">

                <span class="auth-brand-label">
                    DIGITAL LIBRARY
                </span>

                <h1>
                    KATAMITHA
                </h1>

                <p>
                    Tempat Kata Menjadi Cerita
                </p>

            </a>

        </div>

        <div class="auth-container">

            {{ $slot }}

        </div>

        <a href="{{ route('home') }}" class="auth-back">
            Kembali ke Beranda
        </a>

    </div>

</body>

</html>