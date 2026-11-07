@extends('layouts.app')

@section('title', 'Kelola Peminjaman - KATAMITHA')

@section('content')

    <div class="loan-management">

        {{-- =====================================================
             HEADER
             ===================================================== --}}
        <div class="loan-management-header">

            <div>
                <span class="loan-management-label">
                    KATAMITHA DIGITAL LIBRARY
                </span>

                <h2>
                    Kelola Peminjaman
                </h2>

                <p>
                    Kelola pengajuan dan data peminjaman buku
                    perpustakaan KATAMITHA.
                </p>
            </div>

        </div>


        {{-- =====================================================
             PESAN SUKSES
             ===================================================== --}}
        @if (session('success'))

            <div class="loan-message loan-message-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- =====================================================
             PESAN ERROR
             ===================================================== --}}
        @if (session('error'))

            <div class="loan-message loan-message-error">
                {{ session('error') }}
            </div>

        @endif


        {{-- =====================================================
             INFORMASI RINGKAS
             ===================================================== --}}
        <div class="loan-summary">

            <div class="loan-summary-card">

                <span>
                    Total Peminjaman
                </span>

                <strong>
                    {{ $peminjamans->count() }}
                </strong>

            </div>


            <div class="loan-summary-card">

                <span>
                    Menunggu Persetujuan
                </span>

                <strong>
                    {{ $peminjamans->where('status', 'menunggu')->count() }}
                </strong>

            </div>


            <div class="loan-summary-card">

                <span>
                    Sedang Dipinjam
                </span>

                <strong>
                    {{ $peminjamans->where('status', 'dipinjam')->count() }}
                </strong>

            </div>


            <div class="loan-summary-card">

                <span>
                    Sudah Dikembalikan
                </span>

                <strong>
                    {{ $peminjamans->where('status', 'dikembalikan')->count() }}
                </strong>

            </div>

        </div>


        {{-- =====================================================
             DAFTAR PEMINJAMAN
             ===================================================== --}}
        <div class="loan-table-card">

            <div class="loan-table-heading">

                <div>
                    <span>
                        DATA PEMINJAMAN
                    </span>

                    <h3>
                        Daftar Peminjaman Buku
                    </h3>
                </div>

                <p>
                    {{ $peminjamans->count() }} data
                </p>

            </div>


            @if ($peminjamans->isEmpty())

                <div class="loan-empty">

                    <h3>
                        Belum Ada Peminjaman
                    </h3>

                    <p>
                        Belum terdapat data peminjaman buku
                        di perpustakaan KATAMITHA.
                    </p>

                </div>

            @else

                <div class="loan-table-wrapper">

                    <table class="loan-table">

                        <thead>

                            <tr>
                                <th>No.</th>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
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

                                        <div class="loan-member">

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

                                        <div class="loan-book">

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
                                        {{ $peminjaman->tanggal_kembali ?? '-' }}
                                    </td>


                                    <td>

                                        @if ($peminjaman->status === 'menunggu')

                                            <span class="loan-status loan-status-waiting">
                                                Menunggu
                                            </span>

                                        @elseif ($peminjaman->status === 'dipinjam')

                                            <span class="loan-status loan-status-borrowed">
                                                Dipinjam
                                            </span>

                                        @elseif ($peminjaman->status === 'dikembalikan')

                                            <span class="loan-status loan-status-returned">
                                                Dikembalikan
                                            </span>

                                        @else

                                            <span class="loan-status">
                                                {{ ucfirst($peminjaman->status) }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{-- Hanya pengajuan yang masih menunggu
                                             yang diproses pada halaman Peminjaman --}}
                                        @if ($peminjaman->status === 'menunggu')

                                            @can('memproses persetujuan peminjaman')

                                                <form
                                                    method="POST"
                                                    action="{{ route('peminjaman.approve', $peminjaman) }}"
                                                    class="loan-action-form"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="loan-approve-button"
                                                    >
                                                        Setujui
                                                    </button>

                                                </form>

                                            @else

                                                <span class="loan-no-action">
                                                    -
                                                </span>

                                            @endcan

                                        @else

                                            <span class="loan-no-action">
                                                -
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


        {{-- =====================================================
             KEMBALI KE DASHBOARD
             ===================================================== --}}
        <div class="loan-back">

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