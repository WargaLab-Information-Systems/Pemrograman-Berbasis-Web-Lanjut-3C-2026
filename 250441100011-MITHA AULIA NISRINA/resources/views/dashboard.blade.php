@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="student-dashboard">

    {{-- HEADER --}}
    <div class="student-dashboard-header">

        <span class="student-dashboard-label">
            KATAMITHA DIGITAL LIBRARY
        </span>

        <h2>Dashboard Siswa</h2>

        <p>
            Selamat datang, {{ auth()->user()->name }}.
            Temukan koleksi buku, lihat riwayat peminjaman,
            dan kelola profil Anda melalui dashboard ini.
        </p>

    </div>


    {{-- MENU SISWA --}}
    <div class="student-dashboard-menu">

        {{-- DAFTAR BUKU --}}
        <a href="{{ route('buku.index') }}"
           class="student-dashboard-card">

            <span class="student-card-number">
                01
            </span>

            <div class="student-card-content">

                <span class="student-card-label">
                    KOLEKSI
                </span>

                <h3>Daftar Buku</h3>

                <p>
                    Lihat koleksi dan detail buku yang tersedia
                    di KATAMITHA Digital Library.
                </p>

            </div>

            <span class="student-card-arrow">
                &rsaquo;
            </span>

        </a>


        {{-- RIWAYAT PEMINJAMAN --}}
        <a href="{{ route('riwayat-peminjaman.index') }}"
           class="student-dashboard-card">

            <span class="student-card-number">
                02
            </span>

            <div class="student-card-content">

                <span class="student-card-label">
                    PEMINJAMAN
                </span>

                <h3>Riwayat Peminjaman</h3>

                <p>
                    Lihat status dan seluruh riwayat
                    peminjaman buku milik Anda.
                </p>

            </div>

            <span class="student-card-arrow">
                &rsaquo;
            </span>

        </a>


        {{-- PROFIL --}}
        <a href="{{ route('profile.edit') }}"
           class="student-dashboard-card">

            <span class="student-card-number">
                03
            </span>

            <div class="student-card-content">

                <span class="student-card-label">
                    AKUN
                </span>

                <h3>Profil Saya</h3>

                <p>
                    Lihat dan perbarui informasi profil
                    akun Anda.
                </p>

            </div>

            <span class="student-card-arrow">
                &rsaquo;
            </span>

        </a>

    </div>

</div>

@endsection