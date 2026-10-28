@extends('layouts.app')


@section('title', 'Detail Buku')


@section('content')

    <div class="page-header">

        <h2>
            Detail Buku
        </h2>

    </div>


    @if ($detailBuku)

        <div class="detail-card">

            <h3>
                {{ $detailBuku['judul'] }}
            </h3>


            <div class="detail-info">

                <p>
                    <strong>ID Buku:</strong>
                    {{ $detailBuku['id'] }}
                </p>


                <p>
                    <strong>Penulis:</strong>
                    {{ $detailBuku['penulis'] }}
                </p>


                <p>
                    <strong>Tahun Terbit:</strong>
                    {{ $detailBuku['tahun'] }}
                </p>


                <p>
                    <strong>Kategori:</strong>
                    {{ $detailBuku['kategori'] }}
                </p>

            </div>


            <a
                href="{{ route('buku.index') }}"
                class="button"
            >
                Kembali ke Daftar Buku
            </a>

        </div>


    @else

        <div class="not-found">

            <h3>
                Buku Tidak Ditemukan
            </h3>

            <p>
                Data buku dengan ID tersebut
                tidak tersedia.
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