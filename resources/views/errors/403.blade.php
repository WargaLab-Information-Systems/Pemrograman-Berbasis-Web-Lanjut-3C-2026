@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')

<div class="not-found">
    <h2>403 &mdash; Akses Ditolak</h2>

    <p>Anda tidak memiliki hak akses untuk membuka halaman atau menjalankan aksi ini.</p>

    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="button">Kembali</a>
</div>

@endsection
