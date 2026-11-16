
@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

<div class="page-title">
    <h2>Daftar Koleksi Buku</h2>
    <p>Berikut adalah koleksi buku yang tersedia di perpustakaan.</p>
</div>

<style>
    .book-form {
        margin-bottom: 25px;
        padding: 20px;
        background: #fff7fb;
        border: 1px solid #ead7e5;
        border-radius: 12px;
    }

    .book-form label {
        display: block;
        margin-top: 12px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .book-form input,
    .book-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d8c5d3;
        border-radius: 7px;
        box-sizing: border-box;
    }

    .book-form button,
    .book-table button {
        padding: 8px 12px;
        margin-top: 12px;
        margin-right: 5px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-save {
        background: #a65c89;
        color: white;
    }

    .btn-cancel {
        background: #e7dce4;
        color: #333;
    }

    .btn-edit {
        background: #f1d9a7;
        color: #333;
    }

    .btn-delete {
        background: #e8b8c0;
        color: #333;
    }

    .book-table-wrapper {
        overflow-x: auto;
    }

    .book-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .book-table th,
    .book-table td {
        border: 1px solid #eadde6;
        padding: 10px;
        text-align: left;
    }

    .book-table th {
        background: #f4e5ef;
    }

    .book-message {
        margin-top: 10px;
        color: #a12645;
    }

    .book-pagination {
        margin-top: 15px;
    }

    .book-pagination button {
        padding: 8px 12px;
        margin-right: 5px;
        cursor: pointer;
    }
</style>

@can('kelola-buku')
    <p style="margin: 0 0 20px"><a href="{{ route('buku.create') }}" class="btn">Tambah Buku</a></p>
@endcan

@if (session('success'))
    <p class="book-message" role="status">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p class="book-message" role="alert">{{ session('error') }}</p>
@endif

<h3>Daftar Koleksi Buku</h3>

<div class="book-table-wrapper">
    <table class="book-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bukus as $buku)
                <tr>
                    <td>{{ $bukus->firstItem() + $loop->index }}</td>
                    <td>{{ $buku->kategori->nama ?? '-' }}</td>

                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->tahun_terbit }}</td>
                    <td>
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-light btn-sm">Detail</a>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login untuk Meminjam</a>
                        @endguest

                        @can('mengajukan peminjaman buku')
                            <form method="POST" action="{{ route('peminjaman.store') }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                <button type="submit" class="btn btn-sm">Ajukan Pinjam</button>
                            </form>
                        @endcan

                        @can('kelola-buku')
                            <a href="{{ route('buku.edit', $buku) }}" class="btn btn-light btn-sm">Ubah</a>
                            <form method="POST" action="{{ route('buku.destroy', $buku) }}" class="inline-form"
                                  onsubmit="return confirm(@js('Apakah kamu yakin ingin menghapus buku "'.$buku->judul.'"?'))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data buku.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="book-pagination">
    {{ $bukus->links() }}
</div>

@endsection
