@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

<div class="page-title">
    <h2>Dashboard Petugas</h2>
    <p>Operasional harian perpustakaan.</p>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-number">{{ $totalBuku }}</div><div class="stat-label">Total buku</div></div>
    <div class="stat-card"><div class="stat-number">{{ $totalAnggota }}</div><div class="stat-label">Total anggota</div></div>
    <div class="stat-card"><div class="stat-number">{{ $menunggu }}</div><div class="stat-label">Pengajuan menunggu</div></div>
    <div class="stat-card"><div class="stat-number">{{ $aktif }}</div><div class="stat-label">Sedang dipinjam</div></div>
</div>

<h3>Pengajuan Menunggu Persetujuan</h3>

<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Anggota</th><th>Buku</th><th>Tanggal</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse ($terbaru as $p)
            <tr>
                <td>{{ $p->anggota->nama }}</td>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>@include('peminjaman._aksi', ['p' => $p])</td>
            </tr>
        @empty
            <tr><td colspan="4" class="empty">Tidak ada pengajuan yang menunggu.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="toolbar">
    @can('kelola-buku')
        <a class="btn" href="{{ route('buku.index') }}">Kelola Buku</a>
    @endcan
    @can('kelola peminjaman')
        <a class="btn btn-light" href="{{ route('peminjaman.index') }}">Data Peminjaman</a>
    @endcan
    @can('kelola pengembalian')
        <a class="btn btn-light" href="{{ route('pengembalian.index') }}">Pengembalian</a>
    @endcan
    @can('lihat laporan perpustakaan')
        <a class="btn btn-light" href="{{ route('laporan.index') }}">Laporan</a>
    @endcan
</div>

@endsection
