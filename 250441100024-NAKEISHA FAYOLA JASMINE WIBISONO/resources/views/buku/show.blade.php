@extends('layouts.perpus')

@section('title', $buku['judul'] ?? 'Buku Tidak Ditemukan')

@section('content')
    <a href="{{ route('buku.index') }}" class="back-link">&larr; Kembali ke Daftar Buku</a>

    @if (session('sukses'))
        <p style="color:#1e7b34; margin-top:12px;">{{ session('sukses') }}</p>
    @endif
    @if (session('error'))
        <p style="color:#b3261e; margin-top:12px;">{{ session('error') }}</p>
    @endif

    @if ($buku)
        <div class="detail-buku">
            <h2>{{ $buku['judul'] }}</h2>
            <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku['tahun'] }}</p>
            <p><strong>Kategori:</strong> {{ $buku['kategori'] }}</p>

            @can('ajukan-peminjaman-buku')
                <form method="POST" action="{{ route('peminjaman.ajukan', $buku['id']) }}" style="margin-top:16px;">
                    @csrf
                    <button type="submit" style="padding:8px 14px; border:0; border-radius:4px; background:#2d6cdf; color:#fff; cursor:pointer;">
                        Ajukan Peminjaman
                    </button>
                </form>
            @endcan
        </div>
    @else
        <div class="alert-notfound">
            <p>Maaf, buku dengan id tersebut tidak ditemukan.</p>
        </div>
    @endif
@endsection
