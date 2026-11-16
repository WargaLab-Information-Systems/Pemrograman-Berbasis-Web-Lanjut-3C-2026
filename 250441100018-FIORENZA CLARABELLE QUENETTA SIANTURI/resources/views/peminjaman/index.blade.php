@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')

<div class="page-title">
    <h2>Data Peminjaman</h2>
    <p>Seluruh pengajuan dan peminjaman buku.</p>
</div>

<form method="GET" class="toolbar">
    <select name="status">
        <option value="">Semua status</option>
        @foreach (['diajukan', 'dipinjam', 'dikembalikan', 'ditolak'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn btn-light" type="submit">Filter</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr><th>No.</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse ($peminjamans as $p)
            <tr>
                <td>{{ $peminjamans->firstItem() + $loop->index }}</td>
                <td>{{ $p->anggota->nama }}</td>
                <td>{{ $p->buku->judul }}</td>
                <td>{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                <td>{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                <td><x-status-badge :status="$p->status" /></td>
                <td>
                    <a class="btn btn-light btn-sm" href="{{ route('peminjaman.show', $p) }}">Detail</a>
                    @include('peminjaman._aksi', ['p' => $p])
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty">Belum ada data peminjaman.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $peminjamans->links() }}

@endsection
