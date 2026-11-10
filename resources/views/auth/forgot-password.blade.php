@extends('layouts.app')

@section('title', 'Lupa Kata Sandi')

@section('content')

<div class="card card-narrow">
    <h3>Lupa Kata Sandi</h3>

    <p class="form-hint" style="margin-bottom:14px;">
        Masukkan email Anda, kami akan mengirim tautan untuk mengatur ulang kata sandi.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">Kirim Tautan Reset</button>
    </form>
</div>

@endsection
