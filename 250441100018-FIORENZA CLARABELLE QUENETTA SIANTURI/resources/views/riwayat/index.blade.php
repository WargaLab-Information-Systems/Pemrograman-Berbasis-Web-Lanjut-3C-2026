@extends('layouts.app')

@section('title', $judul)

@section('content')

<div class="page-title">
    <h2>{{ $judul }}</h2>
    <p>{{ $pemilik->name }} &middot; {{ $pemilik->email }}</p>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr><th>No.</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th></th></tr>
        </thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td><x-status-badge :status="$p->status" /></td>
                <td><a class="btn btn-light btn-sm" href="{{ route('peminjaman.show', $p) }}">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="empty">Belum ada data peminjaman.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
