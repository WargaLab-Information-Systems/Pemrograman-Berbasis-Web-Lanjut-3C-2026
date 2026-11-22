<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- No. Telepon (data anggota perpustakaan) -->
        <div class="mt-4">
            <x-input-label for="no_telepon" value="No. Telepon" />
            <x-text-input id="no_telepon" class="block mt-1 w-full" type="text" name="no_telepon" :value="old('no_telepon')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('no_telepon')" class="mt-2" />
        </div>

        <!-- Kata sandi -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi kata sandi -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <p class="mt-4 text-xs text-gray-500">Akun baru otomatis berperan sebagai <strong>siswa</strong>. Role lain hanya dapat ditetapkan oleh admin.</p>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
