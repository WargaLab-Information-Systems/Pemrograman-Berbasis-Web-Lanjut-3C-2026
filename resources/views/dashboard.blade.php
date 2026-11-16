@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="page-title">
    <h2>Halo, {{ auth()->user()->name }}</h2>
    <p>Ringkasan peminjaman buku milik Anda.</p>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $diajukan }}</div>
        <div class="stat-label">Menunggu persetujuan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $dipinjam }}</div>
        <div class="stat-label">Sedang dipinjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $selesai }}</div>
        <div class="stat-label">Sudah dikembalikan</div>
    </div>
</div>

<div class="toolbar">
    @can('lihat daftar buku')
        <a class="btn" href="{{ route('buku.index') }}">Lihat Daftar Buku</a>
    @endcan
    @can('melihat status peminjaman sendiri')
        <a class="btn btn-light" href="{{ route('peminjaman.status') }}">Status Peminjaman</a>
    @endcan
    @can('melihat riwayat peminjaman sendiri')
        <a class="btn btn-light" href="{{ route('riwayat.saya') }}">Riwayat Peminjaman</a>
    @endcan
    @can('lihat profil sendiri')
        <a class="btn btn-light" href="{{ route('profile.edit') }}">Profil Saya</a>
    @endcan
</div>

@endsection
