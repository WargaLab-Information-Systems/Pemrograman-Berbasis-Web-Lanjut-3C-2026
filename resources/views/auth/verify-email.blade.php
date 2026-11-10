@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')

<div class="card card-narrow">
    <h3>Verifikasi Email</h3>

    <p class="form-hint" style="margin-bottom:14px;">
        Terima kasih telah mendaftar. Silakan verifikasi alamat email Anda melalui tautan yang kami kirim.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">Tautan verifikasi baru telah dikirim ke email Anda.</div>
    @endif

    <div class="form-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">Kirim Ulang Email Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light">Logout</button>
        </form>
    </div>
</div>

@endsection
