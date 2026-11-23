@extends('layouts.app')

@section('title', ($buku['judul'] ?? 'Detail Buku') . ' — Lentera Aksara')

@section('content')
    <div class="container page">

        <a href="{{ route('buku.index') }}" class="back-link">← Kembali ke Rekomendasi</a>

        @if ($buku)
            <section class="detail-hero" data-kategori="{{ $buku['kategori'] }}">
                <div class="hero-pattern" aria-hidden="true"></div>

                <div class="detail-cover" aria-hidden="true">
                    @if (!empty($buku['sampul']) && file_exists(public_path('images/' . $buku['sampul'])))
                        <img src="{{ asset('images/' . $buku['sampul']) }}" alt="">
                    @else
                        <span>{{ Str::of($buku['judul'])->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}</span>
                    @endif
                </div>

                <div class="detail-hero-text">
                    <span class="badge">{{ $buku['kategori'] }}</span>
                    <h1>{{ $buku['judul'] }}</h1>
                    <p class="detail-author">{{ $buku['penulis'] }}</p>
                </div>

                <div class="detail-number">#{{ str_pad($buku['id'], 2, '0', STR_PAD_LEFT) }}</div>
            </section>

            <section class="detail-content" data-kategori="{{ $buku['kategori'] }}">
                <div class="detail-card">
                    <span class="section-label">Tentang Buku</span>

                    <dl class="book-data">
                        <div>
                            <dt>Judul</dt>
                            <dd>{{ $buku['judul'] }}</dd>
                        </div>
                        <div>
                            <dt>Penulis</dt>
                            <dd>{{ $buku['penulis'] }}</dd>
                        </div>
                        <div>
                            <dt>Tahun Terbit</dt>
                            <dd>{{ $buku['tahun'] }}</dd>
                        </div>
                        <div>
                            <dt>Kategori</dt>
                            <dd>{{ $buku['kategori'] }}</dd>
                        </div>
                        <div>
                            <dt>ID Buku</dt>
                            <dd>#{{ str_pad($buku['id'], 3, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="detail-card synopsis-card">
                    <span class="section-label">Sinopsis</span>

                    <h2>Ringkasan cerita</h2>
                    <p>{{ $buku['sinopsis'] ?? 'Sinopsis untuk buku ini belum tersedia.' }}</p>

                    <a href="{{ route('buku.index') }}" class="btn">Kembali ke Rekomendasi</a>
                </div>
            </section>
        @else
            <section class="empty-state">
                <span class="eyebrow">Buku tidak ditemukan</span>
                <h1>Data buku tidak tersedia.</h1>
                <p>Buku yang kamu cari belum ada di dalam koleksi.</p>
                <a href="{{ route('buku.index') }}" class="btn">Kembali ke Rekomendasi</a>
            </section>
        @endif

    </div>
@endsection
