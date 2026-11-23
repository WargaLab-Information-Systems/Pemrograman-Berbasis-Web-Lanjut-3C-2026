@extends('layouts.app')

@section('title', 'Rekomendasi Buku — Lentera Aksara')

@section('content')
    <div class="container page">

        <div class="page-heading">
            <div class="page-heading-text">
                <span class="eyebrow">Rekomendasi</span>
                <h1>Buku yang layak kamu baca berikutnya.</h1>
                <p>
                    Pilihan buku dengan cerita tentang kehidupan,
                    dan perjalanan menemukan arah sendiri — dipilih untuk jadi referensi bacaanmu.
                </p>
            </div>

            <dl class="page-stats">
                <div>
                    <dt>Buku</dt>
                    <dd>{{ count($buku) }}</dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd>{{ collect($buku)->pluck('kategori')->unique()->count() }}</dd>
                </div>
            </dl>
        </div>

        <div class="daftar-buku">
            @foreach ($buku as $item)
                <x-buku-card
                    :nomor="$item['id']"
                    :judul="$item['judul']"
                    :penulis="$item['penulis']"
                    :tahun="$item['tahun']"
                    :kategori="$item['kategori']"
                    :sampul="$item['sampul'] ?? null"
                    :url="route('buku.show', $item['id'])"
                >
                    <x-slot:label>{{ $item['kategori'] }}</x-slot:label>
                </x-buku-card>
            @endforeach
        </div>

    </div>
@endsection
