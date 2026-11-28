@extends('layouts.app')

@section('content')
<div class="loan-detail-page">
    <div class="loan-detail-container">

        <div class="loan-detail-heading">
            <span class="loan-detail-eyebrow">KATAMITHA LIBRARY</span>
            <h2>Detail Peminjaman</h2>
            <p>Informasi lengkap mengenai data peminjaman buku.</p>
        </div>

        <div class="loan-detail-card">
            <div class="loan-detail-card-header">
                <div>
                    <span class="loan-detail-label">DATA PEMINJAMAN</span>
                    <h3>Peminjaman #{{ $peminjaman->id }}</h3>
                </div>

                <span class="loan-detail-status">
                    {{ ucfirst($peminjaman->status ?? 'Tidak diketahui') }}
                </span>
            </div>

            <div class="loan-detail-section">
                <h4>Informasi Anggota</h4>

                <div class="loan-detail-grid">
                    <div class="loan-detail-item">
                        <span>Nama Anggota</span>
                        <strong>{{ $peminjaman->anggota?->nama ?? '-' }}</strong>
                    </div>

                    <div class="loan-detail-item">
                        <span>Email</span>
                        <strong>{{ $peminjaman->anggota?->email ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="loan-detail-section">
                <h4>Informasi Buku</h4>

                <div class="loan-detail-grid">
                    <div class="loan-detail-item">
                        <span>Judul Buku</span>
                        <strong>{{ $peminjaman->buku?->judul ?? '-' }}</strong>
                    </div>

                    <div class="loan-detail-item">
                        <span>Penulis</span>
                        <strong>{{ $peminjaman->buku?->penulis ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="loan-detail-section">
                <h4>Informasi Tanggal</h4>

                <div class="loan-detail-grid">
                    <div class="loan-detail-item">
                        <span>Tanggal Pinjam</span>
                        <strong>{{ $peminjaman->tanggal_pinjam ?? '-' }}</strong>
                    </div>

                    <div class="loan-detail-item">
                        <span>Tanggal Kembali</span>
                        <strong>{{ $peminjaman->tanggal_kembali ?? '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="loan-detail-actions">
                <a href="{{ url('/home') }}" class="loan-detail-back">
                    Kembali
                </a>
            </div>
        </div>

    </div>
</div>
@endsection