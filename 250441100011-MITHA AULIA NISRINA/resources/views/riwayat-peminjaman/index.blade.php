@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="history-management">

    {{-- HEADER --}}
    <div class="history-management-header">
        <span class="history-management-label">
            KATAMITHA DIGITAL LIBRARY
        </span>

        <h2>Riwayat Peminjaman</h2>

        <p>
            Lihat seluruh riwayat peminjaman buku Anda beserta
            status peminjaman dan tanggal pengembaliannya.
        </p>
    </div>


    {{-- RINGKASAN --}}
    <div class="history-summary">

        <div class="history-summary-card">
            <span>Total Peminjaman</span>

            <strong>
                {{ $peminjamans->count() }}
            </strong>
        </div>

        <div class="history-summary-card">
            <span>Menunggu</span>

            <strong>
                {{ $peminjamans->where('status', 'menunggu')->count() }}
            </strong>
        </div>

        <div class="history-summary-card">
            <span>Dipinjam</span>

            <strong>
                {{ $peminjamans->where('status', 'dipinjam')->count() }}
            </strong>
        </div>

        <div class="history-summary-card">
            <span>Dikembalikan</span>

            <strong>
                {{ $peminjamans->where('status', 'dikembalikan')->count() }}
            </strong>
        </div>

    </div>


    {{-- TABEL RIWAYAT --}}
    <div class="history-table-card">

        <div class="history-table-heading">

            <div>
                <span>DATA PEMINJAMAN</span>

                <h3>Riwayat Buku Saya</h3>
            </div>

            <p>
                {{ $peminjamans->count() }} Transaksi
            </p>

        </div>


        @if ($peminjamans->isEmpty())

            <div class="history-empty">

                <div class="history-empty-icon">
                    ✓
                </div>

                <h3>Belum Ada Riwayat Peminjaman</h3>

                <p>
                    Anda belum memiliki riwayat peminjaman buku.
                    Silakan lihat koleksi buku dan ajukan peminjaman
                    dari buku yang ingin dibaca.
                </p>

            </div>

        @else

            <div class="history-table-wrapper">

                <table class="history-table">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($peminjamans as $peminjaman)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="history-book">

                                        <strong>
                                            {{ $peminjaman->buku?->judul ?? 'Buku tidak tersedia' }}
                                        </strong>

                                        <span>
                                            {{ $peminjaman->buku?->kategori?->nama ?? 'Tanpa kategori' }}
                                        </span>

                                    </div>
                                </td>

                                <td>
                                    {{ $peminjaman->tanggal_pinjam ?? '-' }}
                                </td>

                                <td>
                                    {{ $peminjaman->tanggal_kembali ?? '-' }}
                                </td>

                                <td>

                                    @if ($peminjaman->status === 'menunggu')

                                        <span class="history-status history-status-waiting">
                                            Menunggu
                                        </span>

                                    @elseif ($peminjaman->status === 'dipinjam')

                                        <span class="history-status history-status-borrowed">
                                            Dipinjam
                                        </span>

                                    @elseif ($peminjaman->status === 'dikembalikan')

                                        <span class="history-status history-status-returned">
                                            Dikembalikan
                                        </span>

                                    @else

                                        <span class="history-status">
                                            {{ ucfirst($peminjaman->status) }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>


    {{-- KEMBALI --}}
    <div class="history-back">

        @if (auth()->user()->hasRole('admin'))

            <a href="{{ route('admin.dashboard') }}">
                Kembali ke Dashboard
            </a>

        @elseif (auth()->user()->hasRole('petugas'))

            <a href="{{ route('petugas.dashboard') }}">
                Kembali ke Dashboard
            </a>

        @else

            <a href="{{ route('dashboard') }}">
                Kembali ke Dashboard
            </a>

        @endif

    </div>

</div>
@endsection