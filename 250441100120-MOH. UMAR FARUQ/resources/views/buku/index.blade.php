@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Efqai')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Jelajahi koleksi</p>
        <h2>Daftar Buku</h2>
        <p>Pilih buku untuk melihat informasi lengkapnya.</p>
    </section>

    <div class="card-grid">
        @foreach ($bukuList as $buku)
            <x-buku-card :buku="$buku">
                <x-slot:extra>
                    <span class="badge">{{ $buku['kategori'] }}</span>
                </x-slot:extra>
            </x-buku-card>
        @endforeach
    </div>
@endsection
