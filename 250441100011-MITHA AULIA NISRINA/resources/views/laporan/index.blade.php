@extends('layouts.app')

@section('title', 'Laporan Perpustakaan | KATAMITHA')

@section('content')

    <section class="report-management">

        {{-- =====================================================
             HEADER
             ===================================================== --}}
        <div class="report-management-header">

            <span class="report-management-label">
                LAPORAN PERPUSTAKAAN
            </span>

            <h2>
                Laporan Perpustakaan
            </h2>

            <p>
                Lihat ringkasan data dan aktivitas peminjaman
                perpustakaan digital KATAMITHA.
            </p>

        </div>


        {{-- =====================================================
             SUMMARY
             ===================================================== --}}
        <div class="report-summary">

            <div class="report-summary-card">

                <span>
                    Total Buku
                </span>

                <strong>
                    {{ $totalBuku }}
                </strong>

            </div>


            <div class="report-summary-card">

                <span>
                    Total Anggota
                </span>

                <strong>
                    {{ $totalAnggota }}
                </strong>

            </div>


            <div class="report-summary-card">

                <span>
                    Total Peminjaman
                </span>

                <strong>
                    {{ $totalPeminjaman }}
                </strong>

            </div>

        </div>


        {{-- =====================================================
             STATUS SUMMARY
             ===================================================== --}}
        <div class="report-status-summary">

            <div class="report-status-card">

                <span class="report-status-dot report-status-dot-waiting"></span>

                <div>

                    <span>
                        Menunggu
                    </span>

                    <strong>
                        {{ $totalMenunggu }}
                    </strong>

                </div>

            </div>


            <div class="report-status-card">

                <span class="report-status-dot report-status-dot-borrowed"></span>

                <div>

                    <span>
                        Dipinjam
                    </span>

                    <strong>
                        {{ $totalDipinjam }}
                    </strong>

                </div>

            </div>


            <div class="report-status-card">

                <span class="report-status-dot report-status-dot-returned"></span>

                <div>

                    <span>
                        Dikembalikan
                    </span>

                    <strong>
                        {{ $totalDikembalikan }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TABLE
             ===================================================== --}}
        <div class="report-table-card">

            <div class="report-table-heading">

                <div>

                    <span>
                        DATA TRANSAKSI
                    </span>

                    <h3>
                        Aktivitas Peminjaman
                    </h3>

                </div>

                <p>
                    {{ $totalPeminjaman }} transaksi
                </p>

            </div>


            @if ($peminjamans->count())

                <div class="report-table-wrapper">

                    <table class="report-table">

                        <thead>

                            <tr>

                                <th>
                                    No.
                                </th>

                                <th>
                                    Anggota
                                </th>

                                <th>
                                    Buku
                                </th>

                                <th>
                                    Tanggal Pinjam
                                </th>

                                <th>
                                    Tanggal Kembali
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($peminjamans as $index => $peminjaman)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>

                                        <div class="report-member">

                                            <strong>
                                                {{ $peminjaman->anggota->nama ?? '-' }}
                                            </strong>

                                            <span>
                                                {{ $peminjaman->anggota->email ?? '-' }}
                                            </span>

                                        </div>

                                    </td>

                                    <td>

                                        <div class="report-book">

                                            <strong>
                                                {{ $peminjaman->buku->judul ?? '-' }}
                                            </strong>

                                        </div>

                                    </td>

                                    <td>
                                        {{ $peminjaman->tanggal_pinjam
                                            ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $peminjaman->tanggal_kembali
                                            ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y')
                                            : '-' }}
                                    </td>

                                    <td>

                                        @if ($peminjaman->status === 'menunggu')

                                            <span class="report-status report-status-waiting">
                                                Menunggu
                                            </span>

                                        @elseif ($peminjaman->status === 'dipinjam')

                                            <span class="report-status report-status-borrowed">
                                                Dipinjam
                                            </span>

                                        @elseif ($peminjaman->status === 'dikembalikan')

                                            <span class="report-status report-status-returned">
                                                Dikembalikan
                                            </span>

                                        @else

                                            <span class="report-status">
                                                {{ ucfirst($peminjaman->status) }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="report-empty">

                    <div class="report-empty-icon">
                        —
                    </div>

                    <h3>
                        Belum Ada Transaksi
                    </h3>

                    <p>
                        Belum terdapat data peminjaman yang dapat ditampilkan
                        dalam laporan perpustakaan.
                    </p>

                </div>

            @endif

        </div>


        {{-- =====================================================
             BACK
             ===================================================== --}}
        <div class="report-back">

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

    </section>

@endsection