@extends('layouts.app')

@section('title', 'Pengembalian Buku')

@section('content')

<div class="page-title">
    <h2>Pengembalian Buku</h2>
    <p>Catat pengembalian buku yang sedang dipinjam.</p>
</div>

<h3>Sedang Dipinjam</h3>
<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($dipinjam as $p)
            <tr>
                <td>{{ $p->anggota->nama }}</td>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>@include('peminjaman._aksi', ['p' => $p])</td>
            </tr>
        @empty
            <tr><td colspan="4" class="empty">Tidak ada buku yang sedang dipinjam.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<h3>Pengembalian Terbaru</h3>
<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th></tr></thead>
        <tbody>
        @forelse ($dikembalikan as $p)
            <tr>
                <td>{{ $p->anggota->nama }}</td>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="empty">Belum ada pengembalian.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
