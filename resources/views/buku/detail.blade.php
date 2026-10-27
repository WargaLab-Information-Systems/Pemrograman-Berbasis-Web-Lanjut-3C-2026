@extends('layouts.app')

@section('konten')
<div class="container bagian-detail">
    @if ($buku)
        <a href="{{ route('buku.index') }}" class="btn-kembali">Kembali ke Daftar Buku</a>

        <div class="kartu-detail" style="flex-direction: column; gap: 20px;">
            <!-- Header Kartu: Inisial Judul Buku menggantikan Image Cover -->
            <div style="display: flex; align-items: center; gap: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; width: 100%;">
                <div style="width: 56px; height: 56px; background-color: #0f172a; color: #ffffff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; flex-shrink: 0;">
                    {{ strtoupper(substr($buku['judul'] ?? 'B', 0, 1)) }}
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 1.6rem; color: #0f172a; font-weight: 800;">{{ $buku['judul'] }}</h2>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 0.95rem;">Oleh {{ $buku['penulis'] }} • {{ $buku['tahun_terbit'] }}</p>
                </div>
            </div>

            <!-- Informasi Detail Buku -->
            <table class="table-detail" style="width: 100%;">
                <tr>
                    <th style="width: 140px;">ID Buku</th>
                    <td>: {{ $buku['id'] }}</td>
                </tr>
                <tr>
                    <th>Penulis</th>
                    <td>: {{ $buku['penulis'] }}</td>
                </tr>
                <tr>
                    <th>Tahun Terbit</th>
                    <td>: {{ $buku['tahun_terbit'] }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>: {{ $buku['kategori'] }}</td>
                </tr>
            </table>
        </div>
    @else
        <div class="peringatan">
            <h2>Data Buku Tidak Ditemukan!</h2>
            <p>Buku dengan ID yang dicari tidak terdaftar dalam koleksi kami.</p>
            <a href="{{ route('buku.index') }}" class="btn btn-primary">Kembali ke Daftar Buku</a>
        </div>
    @endif
</div>
@endsection