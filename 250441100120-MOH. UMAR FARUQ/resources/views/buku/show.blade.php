@extends('layouts.app')

@section('title', isset($buku) ? $buku['judul'] : 'Buku Tidak Ditemukan')

@section('content')
    @if ($buku)
        <article class="detail-card">
            <p class="eyebrow">Detail koleksi</p>
            <h2>{{ $buku['judul'] }}</h2>
            <dl class="book-details">
                <div>
                    <dt>ID Buku</dt>
                    <dd>{{ $buku['id'] }}</dd>
                </div>
                <div>
                    <dt>Penulis</dt>
                    <dd>{{ $buku['penulis'] }}</dd>
                </div>
                <div>
                    <dt>Tahun Terbit</dt>
                    <dd>{{ $buku['tahun_terbit'] }}</dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd><span class="badge">{{ $buku['kategori'] }}</span></dd>
                </div>
            </dl>

            <a href="{{ route('buku.index') }}" class="btn btn-secondary">
                &larr; Kembali ke Daftar Buku
            </a>
        </article>
    @else
        <div class="alert">
            <h3>Data Buku Tidak Ditemukan!</h3>
            <p>Buku dengan ID tersebut tidak tersedia dalam koleksi perpustakaan.</p>
            <a href="{{ route('buku.index') }}" class="btn">Kembali ke Daftar Buku</a>
        </div>
    @endif
@endsection
