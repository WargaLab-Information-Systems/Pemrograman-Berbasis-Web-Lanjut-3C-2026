@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    @if ($buku)
        <div class="detail">
            <span class="tag">{{ $buku['kategori'] }}</span>
            <h2>{{ $buku['judul'] }}</h2>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun:</strong> {{ $buku['tahun'] }}</p>
        </div>
    @else
        <div class="not-found">
            <h2>Buku tidak ditemukan</h2>
        </div>
    @endif

    <a href="{{ route('buku.index') }}" class="btn" style="margin-top:20px;display:inline-block;">&larr; Kembali</a>
    
@endsection