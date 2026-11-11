<x-guest-layout>

    <div class="auth-header">

        <span class="auth-label">
            BERGABUNG DENGAN KATAMITHA
        </span>

        <h2>
            Buat Akun Baru
        </h2>

        <p>
            Daftarkan akun untuk mulai menggunakan perpustakaan digital KATAMITHA.
        </p>

    </div>


    <form method="POST" action="{{ route('register') }}" class="auth-form">

        @csrf


        {{-- Nama --}}
        <div class="auth-form-group">

            <label for="name">
                Nama
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama"
            >

            @error('name')
                <span class="auth-error">
                    {{ $message }}
                </span>
            @enderror

        </div>


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
                    autocomplete="new-password"
                    placeholder="Masukkan kata sandi"
                >

                <button
                    type="button"
                    class="auth-password-toggle"
                    onclick="toggleRegisterPassword('password', this)"
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


        {{-- Konfirmasi Kata Sandi --}}
        <div class="auth-form-group">

            <label for="password_confirmation">
                Konfirmasi Kata Sandi
            </label>

            <div class="auth-password-wrapper">

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Masukkan kembali kata sandi"
                >

                <button
                    type="button"
                    class="auth-password-toggle"
                    onclick="toggleRegisterPassword('password_confirmation', this)"
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

            @error('password_confirmation')
                <span class="auth-error">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Tombol Daftar --}}
        <button type="submit" class="auth-button">
            Daftar
        </button>

    </form>


    <div class="auth-footer">

        <p>
            Sudah memiliki akun?

            <a href="{{ route('login') }}">
                Masuk sekarang
            </a>
        </p>

    </div>


    <script>

        function toggleRegisterPassword(inputId, button) {

            const input = document.getElementById(inputId);

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