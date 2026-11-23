@extends('layouts.app')

@section('title', 'Beranda — Lentera Aksara')

@section('content')
    <section class="hero-slider">
        <div class="hero-pattern" aria-hidden="true"></div>

        <div class="container hero-inner">
            <div class="hero-stage">
                <div class="slide is-active">
                    <p class="lead-in">Selamat datang di</p>
                    <h2>Lentera Aksara</h2>
                    <p class="hero-text">
                        Kumpulan rekomendasi novel dan cerita panjang tentang
                        perjalanan, pilihan, dan keberanian
                        untuk membantumu menemukan bacaan berikutnya.
                    </p>
                    <a href="{{ route('buku.index') }}" class="btn" style="margin-top: 28px;">Lihat Rekomendasi</a>
                </div>
            </div>
        </div>
    </section>
@endsection
