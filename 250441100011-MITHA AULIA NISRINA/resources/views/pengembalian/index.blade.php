@extends('layouts.app')

@section('title', 'Kelola Pengembalian - KATAMITHA')

@section('content')

    <div class="return-management">

        {{-- =====================================================
             HEADER
             ===================================================== --}}
        <div class="return-management-header">

            <div>
                <span class="return-management-label">
                    KATAMITHA DIGITAL LIBRARY
                </span>

                <h2>
                    Kelola Pengembalian
                </h2>

                <p>
                    Kelola dan catat pengembalian buku yang sedang
                    dipinjam di perpustakaan KATAMITHA.
                </p>
            </div>

        </div>


        {{-- =====================================================
             PESAN SUKSES
             ===================================================== --}}
        @if (session('success'))

            <div class="return-message return-message-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- =====================================================
             PESAN ERROR
             ===================================================== --}}
        @if (session('error'))

            <div class="return-message return-message-error">
                {{ session('error') }}
            </div>

        @endif


        {{-- =====================================================
             INFORMASI RINGKAS
             ===================================================== --}}
        <div class="return-summary">

            <div class="return-summary-card">

                <span>
                    Sedang Dipinjam
                </span>

                <strong>
                    {{ $peminjamans->count() }}
                </strong>

                <p>
                    Buku yang belum dikembalikan
                </p>

            </div>

        </div>


        {{-- =====================================================
             DAFTAR PENGEMBALIAN
             ===================================================== --}}
        <div class="return-table-card">

            <div class="return-table-heading">

                <div>
                    <span>
                        DATA PENGEMBALIAN
                    </span>

                    <h3>
                        Buku yang Sedang Dipinjam
                    </h3>
                </div>

                <p>
                    {{ $peminjamans->count() }} data
                </p>

            </div>


            @if ($peminjamans->isEmpty())

                <div class="return-empty">

                    <div class="return-empty-icon">
                        ✓
                    </div>

                    <h3>
                        Tidak Ada Pengembalian yang Perlu Diproses
                    </h3>

                    <p>
                        Saat ini tidak ada buku berstatus dipinjam
                        yang menunggu proses pengembalian.
                    </p>

                </div>

            @else

                <div class="return-table-wrapper">

                    <table class="return-table">

                        <thead>

                            <tr>
                                <th>No.</th>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($peminjamans as $peminjaman)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <div class="return-member">

                                            <strong>
                                                {{ $peminjaman->anggota?->nama ?? 'Data anggota tidak tersedia' }}
                                            </strong>

                                            @if ($peminjaman->anggota?->email)

                                                <span>
                                                    {{ $peminjaman->anggota->email }}
                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    <td>

                                        <div class="return-book">

                                            <strong>
                                                {{ $peminjaman->buku?->judul ?? 'Data buku tidak tersedia' }}
                                            </strong>

                                            @if ($peminjaman->buku?->penulis)

                                                <span>
                                                    {{ $peminjaman->buku->penulis }}
                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    <td>
                                        {{ $peminjaman->tanggal_pinjam ?? '-' }}
                                    </td>


                                    <td>

                                        <span class="return-status">
                                            Dipinjam
                                        </span>

                                    </td>


                                    <td>

                                        @can('mencatat pengembalian buku')

                                            <form
                                                method="POST"
                                                action="{{ route('pengembalian.return', $peminjaman) }}"
                                                class="return-action-form"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="return-button"
                                                >
                                                    Kembalikan
                                                </button>

                                            </form>

                                        @else

                                            <span class="return-no-action">
                                                -
                                            </span>

                                        @endcan

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        {{-- =====================================================
             KEMBALI KE DASHBOARD
             ===================================================== --}}
        <div class="return-back">

            @role('admin')

                <a href="{{ route('admin.dashboard') }}">
                    Kembali ke Dashboard
                </a>

            @endrole


            @role('petugas')

                <a href="{{ route('petugas.dashboard') }}">
                    Kembali ke Dashboard
                </a>

            @endrole

        </div>

    </div>

@endsection