@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')

<div class="card card-narrow">
    <h3>Ubah Pengguna</h3>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi Baru (opsional)</label>
            <input id="password" type="password" name="password" autocomplete="new-password">
            <div class="form-hint">Kosongkan jika tidak ingin mengubah.</div>
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan</button>
            <a class="btn btn-light" href="{{ route('users.index') }}">Batal</a>
        </div>
    </form>
</div>

@can('kelola role pengguna')
    <div class="card card-narrow">
        <h3>Role Pengguna</h3>

        @if ($user->is(auth()->user()))
            <p class="form-hint">Anda tidak dapat mengubah role akun Anda sendiri.</p>
        @else
            <form method="POST" action="{{ route('users.role', $user) }}">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <select name="role">
                        @foreach ($roles as $r)
                            <option value="{{ $r }}" @selected($user->hasRole($r))>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn">Simpan Role</button>
            </form>
        @endif
    </div>
@endcan

@endsection
