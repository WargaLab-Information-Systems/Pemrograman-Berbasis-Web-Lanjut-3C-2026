@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<div class="hero">
    <h2>Selamat Datang di Perpustakaan Digital</h2>

    <p>
        Temukan berbagai macam buku dan baca informasi
        mengenai buku yang tersedia di perpustakaan.
    </p>

    <a href="{{ route('buku.index') }}" class="button">
        Lihat Daftar Buku
    </a>
</div>

@endsection 