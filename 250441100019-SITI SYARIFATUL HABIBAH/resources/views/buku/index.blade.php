@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <div class="page-header">

        <h2>
            Daftar Buku
        </h2>

        <p>
            Berikut adalah koleksi buku
            yang tersedia di perpustakaan.
        </p>

    </div>

    <div class="book-grid">

        @foreach ($buku as $item)

            <x-buku-card
                :id="$item['id']"
                :judul="$item['judul']"
                :penulis="$item['penulis']"
                :tahun="$item['tahun']"
            >

                <x-slot:footer>

                    <span class="category">
                        {{ $item['kategori'] }}
                    </span>

                </x-slot:footer>

            </x-buku-card>

        @endforeach

    </div>

@endsection