@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')

<div class="card card-narrow">
    <h3>Registrasi Akun Siswa</h3>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="no_telepon">No. Telepon (opsional)</label>
            <input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon') }}" autocomplete="tel">
            @error('no_telepon') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <p class="form-hint" style="margin-bottom:14px;">
            Akun baru otomatis berperan sebagai Siswa. Role Admin/Petugas hanya dapat ditetapkan oleh Admin.
        </p>

        <div class="form-actions">
            <button type="submit" class="btn">Daftar</button>
            <a class="link" href="{{ route('login') }}">Sudah punya akun? Login</a>
        </div>
    </form>
</div>

@endsection
