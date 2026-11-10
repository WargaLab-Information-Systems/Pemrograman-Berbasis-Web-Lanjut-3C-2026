@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="container detail-container">

    <div class="page-title">
        <h1>Detail Buku</h1>
        <p>Informasi lengkap mengenai buku yang dipilih.</p>
    </div>

    <div class="detail-card">

        <div class="book-category">
            {{ $buku->kategori->nama ?? 'Tanpa Kategori' }}
        </div>

        <h2>{{ $buku->judul }}</h2>

        <div class="book-info">
            <p>
                <strong>Penulis</strong>
                <span>{{ $buku->penulis }}</span>
            </p>

            <p>
                <strong>Tahun Terbit</strong>
                <span>{{ $buku->tahun_terbit ?? '-' }}</span>
            </p>

            <p>
                <strong>Kategori</strong>
                <span>{{ $buku->kategori->nama ?? 'Tanpa Kategori' }}</span>
            </p>

            @if(isset($buku->deskripsi) && $buku->deskripsi)
                <p class="description">
                    <strong>Deskripsi</strong>
                    <span>{{ $buku->deskripsi }}</span>
                </p>
            @endif
        </div>

    </div>

    <a href="{{ route('buku.index') }}" class="back-link">
        ← Kembali ke Daftar Buku
    </a>

</div>
@endsection
