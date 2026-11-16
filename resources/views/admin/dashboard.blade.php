@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="page-title">
    <h2>Dashboard Admin</h2>
    <p>Gambaran umum sistem perpustakaan.</p>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-number">{{ $totalBuku }}</div><div class="stat-label">Total buku</div></div>
    <div class="stat-card"><div class="stat-number">{{ $totalUser }}</div><div class="stat-label">Total pengguna</div></div>
    <div class="stat-card"><div class="stat-number">{{ $menunggu }}</div><div class="stat-label">Pengajuan menunggu</div></div>
    <div class="stat-card"><div class="stat-number">{{ $aktif }}</div><div class="stat-label">Sedang dipinjam</div></div>
    <div class="stat-card"><div class="stat-number">{{ $totalPeminjaman }}</div><div class="stat-label">Total peminjaman</div></div>
</div>

<div class="card">
    <h3>Pengguna per Role</h3>
    <ul>
        @foreach ($perRole as $role)
            <li>{{ ucfirst($role->name) }}: <strong>{{ $role->users_count }}</strong></li>
        @endforeach
    </ul>
</div>

<div class="toolbar">
    @can('kelola data pengguna')
        <a class="btn" href="{{ route('users.index') }}">Kelola Pengguna &amp; Role</a>
    @endcan
    @can('kelola-buku')
        <a class="btn btn-light" href="{{ route('buku.index') }}">Kelola Buku</a>
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
