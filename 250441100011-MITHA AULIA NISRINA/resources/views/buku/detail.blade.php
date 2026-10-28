@extends('layouts.app')

@section(
    'title',
    $bukuDitemukan
        ? $bukuDitemukan['title'] . ' - KATAMITHA'
        : 'Buku Tidak Ditemukan - KATAMITHA'
)

@section('content')

    @if ($bukuDitemukan)

        <div class="detail-card">

            <span class="detail-label">
                {{ $bukuDitemukan['category'] }}
            </span>

            <h2>
                {{ $bukuDitemukan['title'] }}
            </h2>


            {{-- =====================================================
                 COVER BUKU
                 ===================================================== --}}
            <div class="detail-cover">

                @if (!empty($bukuDitemukan['image']))

                    <img
                        src="{{ asset('images/books/' . $bukuDitemukan['image']) }}"
                        alt="Cover {{ $bukuDitemukan['title'] }}"
                    >

                @else

                    <div class="detail-cover-placeholder">

                        <span>
                            KATAMITHA
                        </span>

                        <strong>
                            {{ $bukuDitemukan['title'] }}
                        </strong>

                        <small>
                            Cover belum tersedia
                        </small>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 DESKRIPSI / SINOPSIS
                 ===================================================== --}}
            <div class="detail-description">

                <h3>
                    Sinopsis
                </h3>

                <p>
                    @if (!empty($bukuDitemukan['description']))

                        {{ $bukuDitemukan['description'] }}

                    @else

                        Deskripsi buku belum tersedia.

                    @endif
                </p>

            </div>


            {{-- =====================================================
                 INFORMASI BUKU
                 ===================================================== --}}
            <div class="detail-info">

                <div class="info-item">

                    <span>
                        ID Buku
                    </span>

                    <strong>
                        {{ $bukuDitemukan['id'] }}
                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        Penulis
                    </span>

                    <strong>
                        {{ $bukuDitemukan['author'] }}
                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        Tahun Terbit
                    </span>

                    <strong>
                        {{ $bukuDitemukan['year'] }}
                    </strong>

                </div>


                <div class="info-item">

                    <span>
                        Kategori
                    </span>

                    <strong>
                        {{ $bukuDitemukan['category'] }}
                    </strong>

                </div>

            </div>


            <a
                href="{{ route('buku.index') }}"
                class="back-button"
            >
                Kembali ←
            </a>

        </div>

    @else

        {{-- =====================================================
             BUKU TIDAK DITEMUKAN
             ===================================================== --}}
        <div class="not-found">

            <div class="not-found-icon">

                <span class="book-shape">
                    <span></span>
                </span>

            </div>

            <span class="not-found-label">
                KOLEKSI KATAMITHA
            </span>

            <h2>
                Buku Tidak Ditemukan
            </h2>

            <p>
                Maaf, buku yang kamu cari tidak tersedia dalam
                koleksi KATAMITHA.
            </p>

            <a
                href="{{ route('buku.index') }}"
                class="back-button"
            >
                <span>←</span>
                Kembali ke Daftar Buku
            </a>

        </div>

    @endif

@endsection