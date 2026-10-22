@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

<div class="page-title">

    <h2>Daftar Buku</h2>

    <p>
        Berikut adalah koleksi buku yang tersedia
        di perpustakaan.
    </p>

</div>

<div class="book-grid">

    @foreach ($buku as $data)

        <x-buku-card
            :judul="$data->judul"
            :penulis="$data->penulis"
            :tahun="$data->tahun_terbit"
        >

            <p>
                <strong>Kategori:</strong>
                {{ $data->kategori->nama }}
            </p>

            <a
                href="{{ route('buku.show', $data->id) }}"
                class="button"
            >
                Lihat Detail
            </a>

        </x-buku-card>

    @endforeach

</div>

@endsection