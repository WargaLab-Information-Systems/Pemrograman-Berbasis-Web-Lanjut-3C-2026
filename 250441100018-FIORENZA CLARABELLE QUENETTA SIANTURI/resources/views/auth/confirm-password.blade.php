@extends('layouts.app')

@section('title', 'Konfirmasi Kata Sandi')

@section('content')

<div class="card card-narrow">
    <h3>Konfirmasi Kata Sandi</h3>

    <p class="form-hint" style="margin-bottom:14px;">
        Ini area aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label for="password">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">Konfirmasi</button>
    </form>
</div>

@endsection
