<x-guest-layout>

    <div class="auth-header">

        <span class="auth-label">
            SELAMAT DATANG KEMBALI
        </span>

        <h2>
            Masuk ke KATAMITHA
        </h2>

        <p>
            Masukkan email dan kata sandi untuk melanjutkan ke perpustakaan digital KATAMITHA.
        </p>

    </div>

    <x-auth-session-status
        class="auth-session-status"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" class="auth-form">

        @csrf

        {{-- Email --}}
        <div class="auth-form-group">

            <label for="email">
                Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan email"
            >

            @error('email')
                <span class="auth-error">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Kata Sandi --}}
        <div class="auth-form-group">

            <label for="password">
                Kata Sandi
            </label>

            <div class="auth-password-wrapper">

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi"
                >

                <button
                    type="button"
                    class="auth-password-toggle"
                    onclick="toggleLoginPassword(this)"
                    aria-label="Tampilkan kata sandi"
                >

                    <svg
                        class="auth-eye-icon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                </button>

            </div>

            @error('password')
                <span class="auth-error">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Opsi Login --}}
        <div class="auth-options">

            <label class="auth-remember">

                <input
                    id="remember"
                    type="checkbox"
                    name="remember"
                >

                <span>
                    Ingat saya
                </span>

            </label>

            @if (Route::has('password.request'))

                <a href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>

            @endif

        </div>


        {{-- Tombol Masuk --}}
        <button type="submit" class="auth-button">
            Masuk
        </button>

    </form>


    <div class="auth-footer">

        <p>
            Belum memiliki akun?

            <a href="{{ route('register') }}">
                Daftar sekarang
            </a>
        </p>

    </div>


    <script>

        function toggleLoginPassword(button) {

            const input = document.getElementById('password');

            if (!input) {
                return;
            }

            const icon = button.querySelector('.auth-eye-icon');

            if (input.type === 'password') {

                // Tampilkan kata sandi
                input.type = 'text';

                button.setAttribute(
                    'aria-label',
                    'Sembunyikan kata sandi'
                );

                // Ikon mata dicoret
                icon.innerHTML = `
                    <path d="M3 3l18 18"/>

                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>

                    <path d="M9.9 4.2A10.7 10.7 0 0 1 12 4
                        c7 0 10 8 10 8
                        a18.4 18.4 0 0 1-3.1 4.4"/>

                    <path d="M6.6 6.6
                        C3.8 8.4 2 12 2 12
                        a18.5 18.5 0 0 0 3.8 4.9"/>

                    <path d="M14.1 19.5
                        A10.8 10.8 0 0 1 12 20
                        c-7 0-10-8-10-8"/>
                `;

            } else {

                // Sembunyikan kata sandi
                input.type = 'password';

                button.setAttribute(
                    'aria-label',
                    'Tampilkan kata sandi'
                );

                // Ikon mata biasa
                icon.innerHTML = `
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;

            }

        }

    </script>

</x-guest-layout>