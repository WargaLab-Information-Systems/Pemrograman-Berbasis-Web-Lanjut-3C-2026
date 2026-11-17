@extends('layouts.main')

@section('content')
<div class="welcome-box">
    <div class="welcome-content">
        <h2>Selamat Datang di Perpustakaan Digital</h2>
        <p>Jelajahi berbagai koleksi buku terbaik, novel, sains, hingga modul pembelajaran secara daring kapan saja dan di mana saja.</p>
        
        <div class="hero-action">
            <a href="{{ route('buku.index') }}" class="btn-primary">
                Jelajahi Koleksi Buku &rarr;
            </a>
        </div>
    </div>
</div>
@endsection