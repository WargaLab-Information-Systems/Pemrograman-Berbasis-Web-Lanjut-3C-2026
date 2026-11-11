@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    @if(!$buku)
        <div class="error">
            <h2>Buku tidak ditemukan!</h2>
            <a href="{{ route('buku.index') }}" class="btn">Kembali</a>
        </div>
    @else
        <h2>Detail Buku: {{ $buku['judul'] }}</h2>
        <div class="detail-container">
            <p><strong>ID:</strong> {{ $buku['id'] }}</p>
            <p><strong>Judul:</strong> {{ $buku['judul'] }}</p>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku['tahun'] }}</p>
            <p><strong>Kategori:</strong> {{ $buku['kategori'] }}</p>
            <br>
            <a href="{{ route('buku.index') }}" class="btn">Kembali ke Daftar</a>
        </div>
    @endif
@endsection