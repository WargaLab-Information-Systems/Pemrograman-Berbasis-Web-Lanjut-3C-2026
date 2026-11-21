<x-guest-layout>
    <style>
        .login-card-container {
            font-family: 'Poppins', sans-serif;
            color: #493728;
        }

        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: #654a32;
            text-align: center;
            margin-bottom: 4px;
        }

        .login-subtitle {
            font-size: 13px;
            color: #8b7865;
            text-align: center;
            margin-bottom: 24px;
        }

        .earthy-label {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #654a32 !important;
        }

        .earthy-input {
            width: 100%;
            border: 1px solid #e3cdb5 !important;
            background-color: #fffdf9 !important;
            color: #493728 !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            font-size: 14px !important;
            outline: none !important;
            transition: all 0.2s ease;
        }

        .earthy-input:focus {
            border-color: #806548 !important;
            box-shadow: 0 0 0 3px rgba(128, 101, 72, 0.15) !important;
        }

        .earthy-checkbox {
            accent-color: #806548 !important;
            border-color: #e3cdb5 !important;
            border-radius: 4px !important;
        }

        .earthy-link {
            color: #8b7865 !important;
            font-size: 12px !important;
            text-decoration: none !important;
            transition: color 0.2s;
        }

        .earthy-link:hover {
            color: #654a32 !important;
            text-decoration: underline !important;
        }

        .btn-earthy {
            width: 100%;
            background-color: #806548 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 11px !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            font-size: 14px !important;
            text-align: center;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-earthy:hover {
            background-color: #654a32 !important;
        }
    </style>

    <div class="login-card-container">
        <h1 class="login-title">Selamat Datang</h1>
        <p class="login-subtitle">Masuk ke Sistem Perpustakaan</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="earthy-label" />
                <x-text-input id="email" class="block mt-1 w-full earthy-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email anda" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="earthy-label" />
                <x-text-input id="password" class="block mt-1 w-full earthy-input"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="earthy-checkbox" name="remember">
                    <span class="ms-2 text-xs text-[#8b7865]">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="earthy-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-5">
                <button type="submit" class="btn-earthy">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>