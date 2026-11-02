@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

<div class="page-title">

    <h2>Detail Buku</h2>

</div>

@if ($buku)

    <div class="detail-card">

        <div class="detail-content">

            <h2>{{ $buku->judul }}</h2>

            <div class="detail-item">
                <strong>ID Buku</strong>
                <span>{{ $buku->id }}</span>
            </div>

            <div class="detail-item">
                <strong>Penulis</strong>
                <span>{{ $buku->penulis }}</span>
            </div>

            <div class="detail-item">
                <strong>Tahun Terbit</strong>
                <span>{{ $buku->tahun_terbit }}</span>
            </div>

            <div class="detail-item">
                <strong>Kategori</strong>
                <span>{{ $buku->kategori->nama }}</span>
            </div>

            <a
                href="{{ route('buku.index') }}"
                class="button"
            >
                Kembali ke Daftar Buku
            </a>

        </div>

    </div>

@else

    <div class="not-found">

        <h2>Buku Tidak Ditemukan</h2>

        <p>
            Maaf, buku dengan ID tersebut tidak tersedia.
        </p>

        <a
            href="{{ route('buku.index') }}"
            class="button"
        >
            Kembali ke Daftar Buku
        </a>

    </div>

@endif

@endsection
