@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

<div class="card card-narrow">
    <h3>Tambah Pengguna</h3>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        @can('kelola role pengguna')
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    @foreach ($roles as $r)
                        <option value="{{ $r }}" @selected(old('role', 'siswa') === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                @error('role') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        @endcan

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan</button>
            <a class="btn btn-light" href="{{ route('users.index') }}">Batal</a>
        </div>
    </form>
</div>

@endsection
