@extends('layouts.main')

@section('title', 'Daftar Buku')

@section('content')
    <h2>Daftar Koleksi Buku</h2>
    
    {{-- Tambahkan style ini langsung di sini --}}
    <style>
        .buku-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
    </style>
    
    {{-- Pastikan div ini membungkus @foreach --}}
    <div class="buku-grid">
        @foreach ($bukuList as $buku)
            <x-buku-card :buku="$buku">
                <span class="badge">{{ $buku['kategori'] }}</span>
            </x-buku-card>
        @endforeach
    </div>
@endsection