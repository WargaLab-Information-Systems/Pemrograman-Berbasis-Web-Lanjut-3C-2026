@extends('layouts.app')

@section('title', 'Beranda - Perpustakaan')

@section('content')

<div class="home">

    <div class="container">

        <div class="welcome">

            <p class="welcome-label">
                SELAMAT DATANG
            </p>

            <h1>
                Perpustakaan Taman Sastra
            </h1>

            <p class="welcome-text">
                Selamat datang di Perpustakaan Taman Sastra.
                Temukan berbagai buku menarik untuk dibaca
                dan menambah pengetahuan.
            </p>

            <a
                href="{{ route('buku.index') }}"
                class="button"
            >
                Lihat Daftar Buku
            </a>

        </div>


        <div class="home-info">

            <div class="info-box">

                <h3>
                    Koleksi Buku
                </h3>

                <p>
                    Temukan berbagai macam buku
                    yang tersedia di perpustakaan.
                </p>

            </div>


            <div class="info-box">

                <h3>
                    Mudah Dibaca
                </h3>

                <p>
                    Pilih buku dan lihat informasi
                    lengkap mengenai buku tersebut.
                </p>

            </div>


            <div class="info-box">

                <h3>
                    Temukan Inspirasi
                </h3>

                <p>
                    Membaca buku dapat memberikan
                    pengetahuan dan inspirasi baru.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection