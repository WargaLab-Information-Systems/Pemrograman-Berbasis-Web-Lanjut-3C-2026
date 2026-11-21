@php($edit = $user->exists)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $edit ? 'Edit Pengguna' : 'Tambah Pengguna' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ $edit ? route('pengguna.update', $user) : route('pengguna.store') }}">
                    @csrf
                    @if ($edit)
                        @method('PUT')
                    @endif

                    <div>
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    @unless ($edit)
                        @can('kelola-role-pengguna')
                            <div class="mt-4">
                                <x-input-label for="role" value="Role" />
                                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @foreach ($roles as $r)
                                        <option value="{{ $r }}" @selected(old('role', 'siswa') === $r)>{{ $r }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                        @endcan

                        <div class="mt-4">
                            <x-input-label for="no_telepon" value="No. Telepon (untuk role siswa)" />
                            <x-text-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" :value="old('no_telepon')" />
                            <x-input-error :messages="$errors->get('no_telepon')" class="mt-2" />
                        </div>
                    @endunless

                    <div class="mt-4">
                        <x-input-label for="password" :value="$edit ? 'Kata Sandi Baru (kosongkan jika tidak diubah)' : 'Kata Sandi'" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" :required="! $edit" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" :required="! $edit" autocomplete="new-password" />
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('pengguna.index') }}" class="text-sm text-gray-600 underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
