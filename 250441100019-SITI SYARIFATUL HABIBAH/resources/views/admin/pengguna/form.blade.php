@php($edit = $user->exists)

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $edit ? 'Ubah Pengguna' : 'Tambah Pengguna' }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">

                <form method="POST" action="{{ $edit ? route('admin.pengguna.update', $user) : route('admin.pengguna.store') }}" class="space-y-5">
                    @csrf
                    @if ($edit)
                        @method('PUT')
                    @endif

                    <div>
                        <x-input-label for="name" value="Nama" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="no_telepon" value="No. Telepon" />
                        <x-text-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" :value="old('no_telepon', $user->anggota?->no_telepon)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('no_telepon')" />
                    </div>

                    {{-- Penetapan role: hanya tampil untuk yang punya permission 'kelola role pengguna' --}}
                    @can('kelola role pengguna')
                        <div>
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @php($roleSekarang = old('role', $edit ? $user->roles->first()?->name : 'siswa'))
                                @foreach ($roles as $nama)
                                    <option value="{{ $nama }}" @selected($roleSekarang === $nama)>{{ ucfirst($nama) }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>
                    @endcan

                    <div>
                        <x-input-label for="password" :value="$edit ? 'Kata Sandi Baru (kosongkan jika tidak diganti)' : 'Kata Sandi'" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" :required="! $edit" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" :required="! $edit" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ $edit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}</x-primary-button>
                        <a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
