<section class="profile-password-content">

    <header>
        <h2>Ubah Password</h2>

        <p>
            Pastikan akun kamu menggunakan password yang kuat dan aman.
        </p>
    </header>

    <form
        method="post"
        action="{{ route('password.update') }}"
        class="katamitha-password-form"
    >

        @csrf
        @method('put')

        {{-- Password Saat Ini --}}
        <div class="form-group">

            <label for="update_password_current_password">
                Password Saat Ini
            </label>

            <div class="password-input-wrapper">

                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="Masukkan password saat ini"
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword('update_password_current_password', this)"
                    aria-label="Tampilkan password"
                >

                    <svg
                        class="eye-icon"
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

            @if ($errors->updatePassword->has('current_password'))

                <div class="profile-field-error">

                    @foreach ($errors->updatePassword->get('current_password') as $message)
                        <div>{{ $message }}</div>
                    @endforeach

                </div>

            @endif

        </div>


        {{-- Password Baru --}}
        <div class="form-group">

            <label for="update_password_password">
                Password Baru
            </label>

            <div class="password-input-wrapper">

                <input
                    id="update_password_password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Masukkan password baru"
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword('update_password_password', this)"
                    aria-label="Tampilkan password"
                >

                    <svg
                        class="eye-icon"
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

            @if ($errors->updatePassword->has('password'))

                <div class="profile-field-error">

                    @foreach ($errors->updatePassword->get('password') as $message)
                        <div>{{ $message }}</div>
                    @endforeach

                </div>

            @endif

        </div>


        {{-- Konfirmasi Password Baru --}}
        <div class="form-group">

            <label for="update_password_password_confirmation">
                Konfirmasi Password Baru
            </label>

            <div class="password-input-wrapper">

                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Ulangi password baru"
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword('update_password_password_confirmation', this)"
                    aria-label="Tampilkan password"
                >

                    <svg
                        class="eye-icon"
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

            @if ($errors->updatePassword->has('password_confirmation'))

                <div class="profile-field-error">

                    @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                        <div>{{ $message }}</div>
                    @endforeach

                </div>

            @endif

        </div>


        {{-- Tombol Simpan --}}
        <div class="form-actions">

            <button
                type="submit"
                class="detail-button"
            >
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')

                <p class="success-message">
                    Password berhasil diperbarui.
                </p>

            @endif

        </div>

    </form>

</section>


<script>

    function togglePassword(inputId, button) {

        const input = document.getElementById(inputId);

        if (!input) {
            return;
        }

        const icon = button.querySelector('.eye-icon');

        if (input.type === 'password') {

            // Tampilkan password
            input.type = 'text';

            button.setAttribute(
                'aria-label',
                'Sembunyikan password'
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

            // Sembunyikan password
            input.type = 'password';

            button.setAttribute(
                'aria-label',
                'Tampilkan password'
            );

            // Ikon mata biasa
            icon.innerHTML = `
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>

                <circle cx="12" cy="12" r="3"/>
            `;
        }
    }

</script>