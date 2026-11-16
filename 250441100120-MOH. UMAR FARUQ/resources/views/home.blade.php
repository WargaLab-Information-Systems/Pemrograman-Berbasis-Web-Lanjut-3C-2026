@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan Efqai')

@section('content')
    <section class="hero">
        <p class="eyebrow">Ruang untuk menemukan cerita dan pengetahuan</p>
        <h2>Selamat Datang di Perpustakaan Efqai</h2>
        <p>
            Temukan koleksi pilihan yang siap menemani kegiatan belajar, mencari inspirasi,
            dan menjelajahi ide baru.
        </p>
        <a href="{{ route('buku.index') }}" class="btn">Jelajahi Daftar Buku</a>
    </section>
@endsection
