@extends('layouts.app')

@section('title', 'Dashboard Admin | KATAMITHA')

@section('content')

    <section class="dashboard-page">

        <div class="dashboard-heading">

            <span class="dashboard-label">
                ADMINISTRATOR
            </span>

            <h2>
                Dashboard Admin
            </h2>

            <p>
                Selamat datang, {{ auth()->user()->name }}.
                Kelola perpustakaan digital KATAMITHA melalui halaman ini.
            </p>

        </div>


        <div class="dashboard-grid">

            {{-- =====================================================
                 KELOLA BUKU
                 ===================================================== --}}
            @can('kelola data buku')

                <div class="dashboard-card">

                    <span class="dashboard-card-number">
                        01
                    </span>

                    <h3>
                        Kelola Buku
                    </h3>

                    <p>
                        Tambah, ubah, lihat, dan kelola koleksi buku KATAMITHA.
                    </p>

                    <a href="{{ route('kelola-buku.index') }}">
                        Kelola Buku
                    </a>

                </div>

            @endcan


            {{-- =====================================================
                 KELOLA PENGGUNA DAN ROLE
                 ===================================================== --}}
            @can('kelola data pengguna')

                <div class="dashboard-card">

                    <span class="dashboard-card-number">
                        02
                    </span>

                    <h3>
                        Kelola Pengguna
                    </h3>

                    <p>
                        Kelola data pengguna dan atur role pengguna sesuai kewenangannya.
                    </p>

                    <a href="{{ route('admin.users.index') }}">
                        Kelola Pengguna
                    </a>

                </div>

            @endcan


            {{-- =====================================================
                 PEMINJAMAN
                 ===================================================== --}}
            @can('kelola peminjaman')

                <div class="dashboard-card">

                    <span class="dashboard-card-number">
                        03
                    </span>

                    <h3>
                        Peminjaman
                    </h3>

                    <p>
                        Kelola pengajuan dan proses peminjaman buku perpustakaan.
                    </p>

                    <a href="{{ route('peminjaman.index') }}">
                        Kelola Peminjaman
                    </a>

                </div>

            @endcan


            {{-- =====================================================
                 PENGEMBALIAN
                 ===================================================== --}}
            @can('kelola pengembalian')

                <div class="dashboard-card">

                    <span class="dashboard-card-number">
                        04
                    </span>

                    <h3>
                        Pengembalian
                    </h3>

                    <p>
                        Kelola proses pengembalian buku yang telah dipinjam.
                    </p>

                    <a href="{{ route('pengembalian.index') }}">
                        Kelola Pengembalian
                    </a>

                </div>

            @endcan


            {{-- =====================================================
                 LAPORAN
                 ===================================================== --}}
            @can('lihat laporan perpustakaan')

                <div class="dashboard-card">

                    <span class="dashboard-card-number">
                        05
                    </span>

                    <h3>
                        Laporan
                    </h3>

                    <p>
                        Lihat informasi dan laporan aktivitas perpustakaan KATAMITHA.
                    </p>

                    <a href="{{ route('laporan.index') }}">
                        Lihat Laporan
                    </a>

                </div>

            @endcan

        </div>

    </section>

@endsection