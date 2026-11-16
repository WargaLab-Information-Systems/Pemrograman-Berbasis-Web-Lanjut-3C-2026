@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<div class="page-title"><h2>Detail Peminjaman</h2></div>

<div class="detail-card">
    <div class="detail-content">
        <h2>{{ $peminjaman->buku->judul }}</h2>

        <div class="detail-item"><strong>Peminjam</strong><span>{{ $peminjaman->anggota->nama }}</span></div>
        <div class="detail-item"><strong>Penulis</strong><span>{{ $peminjaman->buku->penulis }}</span></div>
        <div class="detail-item"><strong>Kategori</strong><span>{{ $peminjaman->buku->kategori->nama }}</span></div>
        <div class="detail-item"><strong>Tanggal Pinjam</strong><span>{{ $peminjaman->tanggal_pinjam->format('d-m-Y') }}</span></div>
        <div class="detail-item"><strong>Tanggal Kembali</strong><span>{{ $peminjaman->tanggal_kembali?->format('d-m-Y') ?? '-' }}</span></div>
        <div class="detail-item"><strong>Status</strong><span><x-status-badge :status="$peminjaman->status" /></span></div>
        <div class="detail-item"><strong>Diproses oleh</strong><span>{{ $peminjaman->pemroses?->name ?? '-' }}</span></div>

        <a href="{{ url()->previous() }}" class="button">Kembali</a>
    </div>
</div>

@endsection
