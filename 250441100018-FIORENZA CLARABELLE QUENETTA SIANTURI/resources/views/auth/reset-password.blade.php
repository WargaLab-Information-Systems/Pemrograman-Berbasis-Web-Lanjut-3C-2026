@extends('layouts.app')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')

<div class="card card-narrow">
    <h3>Atur Ulang Kata Sandi</h3>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi Baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn">Simpan Kata Sandi</button>
    </form>
</div>

@endsection
