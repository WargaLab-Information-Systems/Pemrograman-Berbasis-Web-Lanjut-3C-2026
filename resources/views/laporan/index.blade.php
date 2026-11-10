@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')

@section('content')

<div class="page-title">
    <h2>Laporan Perpustakaan</h2>
    <p>Ringkasan koleksi dan peminjaman.</p>
</div>

<div class="stat-grid">
    <div class="stat-card"><div class="stat-number">{{ $totalBuku }}</div><div class="stat-label">Total buku</div></div>
    <div class="stat-card"><div class="stat-number">{{ $totalAnggota }}</div><div class="stat-label">Total anggota</div></div>
    @foreach (['diajukan' => 'Diajukan', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak'] as $kunci => $label)
        <div class="stat-card"><div class="stat-number">{{ $statusCount[$kunci] ?? 0 }}</div><div class="stat-label">{{ $label }}</div></div>
    @endforeach
</div>

<h3>Buku per Kategori</h3>
<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Kategori</th><th>Jumlah Buku</th></tr></thead>
        <tbody>
        @foreach ($bukuPerKategori as $k)
            <tr><td>{{ $k->nama }}</td><td>{{ $k->bukus_count }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>

<h3>Buku Paling Sering Dipinjam</h3>
<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Judul</th><th>Penulis</th><th>Jumlah Peminjaman</th></tr></thead>
        <tbody>
        @foreach ($peminjamanPerBuku as $b)
            <tr><td>{{ $b->judul }}</td><td>{{ $b->penulis }}</td><td>{{ $b->peminjamans_count }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>

<h3>Sedang Dipinjam</h3>
<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th></tr></thead>
        <tbody>
        @forelse ($sedangDipinjam as $p)
            <tr><td>{{ $p->anggota->nama }}</td><td>{{ $p->buku->judul }}</td><td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td></tr>
        @empty
            <tr><td colspan="3" class="empty">Tidak ada buku yang sedang dipinjam.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
