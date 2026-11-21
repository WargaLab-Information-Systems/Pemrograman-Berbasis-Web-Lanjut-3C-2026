<x-guest-layout>

    <div class="auth-header">

        <span class="auth-label">
            PEMULIHAN AKUN
        </span>

        <h2>
            Lupa Kata Sandi?
        </h2>

        <p>
            Masukkan email yang terdaftar pada akun KATAMITHA.
            Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>

    </div>


    {{-- Status Pengiriman --}}
    <x-auth-session-status
        class="auth-session-status"
        :status="session('status')"
    />


    <form
        method="POST"
        action="{{ route('password.email') }}"
        class="auth-form"
    >

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
                autocomplete="email"
                placeholder="Masukkan email"
            >

            @error('email')
                <span class="auth-error">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Tombol Kirim --}}
        <button type="submit" class="auth-button">
            Kirim Tautan Atur Ulang Kata Sandi
        </button>

    </form>


    <div class="auth-footer">

        <p>
            Sudah ingat kata sandi?

            <a href="{{ route('login') }}">
                Kembali ke halaman masuk
            </a>
        </p>

    </div>

</x-guest-layout>