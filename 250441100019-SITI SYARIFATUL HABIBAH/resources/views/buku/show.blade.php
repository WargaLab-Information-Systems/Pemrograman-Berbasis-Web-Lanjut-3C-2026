@extends('layouts.perpustakaan')


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


            {{-- Hanya yang punya permission 'mengajukan peminjaman buku' --}}
            @can('mengajukan peminjaman buku')
                <form
                    method="POST"
                    action="{{ route('peminjaman.ajukan', $detailBuku['id']) }}"
                    class="form-pinjam"
                >
                    @csrf
                    <button type="submit" class="button">
                        Ajukan Peminjaman
                    </button>
                </form>
            @endcan

            {{-- A3 DIPERBAIKI (Soal 2c dan Soal 3): hapus lewat route DELETE + @csrf, dijaga Gate --}}
            @can('kelola-buku')
                <form
                    method="POST"
                    action="{{ route('buku.destroy', $detailBuku['id']) }}"
                    class="form-pinjam"
                    onsubmit="return confirm('Hapus buku ini?')"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button button-hapus">
                        Hapus Buku
                    </button>
                </form>
            @endcan


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