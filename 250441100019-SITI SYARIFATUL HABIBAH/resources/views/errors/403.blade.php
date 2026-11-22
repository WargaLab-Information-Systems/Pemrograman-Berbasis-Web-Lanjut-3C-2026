@extends('layouts.perpustakaan')

@section('title', 'Akses Ditolak')

@section('content')

    <div class="page-header">
        <h2>403 - Akses Ditolak</h2>
        <p>Anda tidak memiliki hak akses untuk membuka halaman atau menjalankan operasi ini.</p>
    </div>

    @auth
        <p>Anda masuk sebagai <strong>{{ auth()->user()->name }}</strong>.</p>
    @endauth

    <a href="{{ route('home') }}" class="button">Kembali ke Beranda</a>

@endsection
