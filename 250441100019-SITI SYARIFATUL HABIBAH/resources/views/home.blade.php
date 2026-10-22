@extends('layouts.app')


@section('title', 'Beranda')


@section('content')

    <div class="hero">

        <h2>
            Welcome to Perpustakaan Cale
        </h2>

        <p>
            Temukan berbagai koleksi buku
            yang ada di perpustakaan.
        </p>

        <a
            href="{{ route('buku.index') }}"
            class="button"
        >
            Lihat Daftar Buku
        </a>

    </div>

@endsection