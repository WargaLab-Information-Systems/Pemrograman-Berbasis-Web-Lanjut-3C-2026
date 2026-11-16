@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="card card-narrow">
    <h3>Login</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <label class="form-check">
            <input type="checkbox" name="remember"> Ingat saya
        </label>

        <div class="form-actions">
            <button type="submit" class="btn">Login</button>
            <a class="link" href="{{ route('register') }}">Belum punya akun? Registrasi</a>
        </div>

        <p class="form-hint" style="margin-top:14px;">
            <a class="link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
        </p>
    </form>
</div>

@endsection
