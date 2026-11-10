@extends('layouts.app')

@section('title', 'Laporan Perpustakaan & Persetujuan')

@section('content')
<style>
    .page-container { 
        max-width: 1000px; 
        margin: 30px auto; 
        padding: 20px; 
        font-family: 'Poppins', sans-serif; 
        color: #493728;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fffdf9;
        border: 1px solid #eadfce;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }

    .stat-card h3 {
        margin: 0 0 6px;
        font-size: 13px;
        color: #8b7865;
        font-weight: 500;
    }

    .stat-card .number {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        font-weight: 700;
        color: #654a32;
        margin: 0;
    }

    .card { 
        background: #fffdf9; 
        border: 1px solid #eadfce; 
        border-radius: 12px; 
        padding: 20px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.04); 
        margin-bottom: 25px;
    }

    .table-custom { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 15px; 
    }

    .table-custom th { 
        background: #f2e8d9; 
        color: #654a32; 
        text-align: left; 
        padding: 12px; 
        font-size: 13px; 
    }

    .table-custom td { 
        padding: 12px; 
        border-bottom: 1px solid #eee5d9; 
        font-size: 13px; 
        color: #5f5043;
        vertical-align: middle;
    }

    .badge { 
        padding: 4px 10px; 
        border-radius: 12px; 
        font-size: 11px; 
        font-weight: 600; 
        display: inline-block;
    }

    .badge-pinjam { background: #eee2d0; color: #765638; }
    .badge-kembali { background: #d4edda; color: #155724; }
    .badge-menunggu { background: #fff3cd; color: #856404; }
    .badge-tolak { background: #f8d7da; color: #721c24; }

    .btn-acc {
        background: #38643a;
        color: #ffffff;
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-acc:hover { background: #274728; }

    .btn-tolak {
        background: #a83232;
        color: #ffffff;
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-tolak:hover { background: #852525; }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
    }
</style>

<div class="page-container">
    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">⚠ {{ session('error') }}</div>
    @endif

    {{-- Ringkasan Statistik --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Buku Sedang Dipinjam</h3>
            <p class="number">{{ $totalPinjam ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <h3>Buku Sudah Dikembalikan</h3>
            <p class="number">{{ $totalKembali ?? 0 }}</p>
        </div>
        <div class="stat-card">
            <h3>Menunggu Persetujuan</h3>
            <p class="number">{{ isset($persetujuanPinjam) ? $persetujuanPinjam->count() : 0 }}</p>
        </div>
    </div>

    {{-- Persetujuan Peminjaman Buku --}}
    <div class="card">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">Persetujuan Peminjaman Buku</h1>
        <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">Daftar pengajuan peminjaman mandiri dari siswa yang memerlukan persetujuan.</p>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Pemohon</th>
                    <th style="width: 30%;">Judul Buku</th>
                    <th style="width: 15%;">Tanggal Pinjam</th>
                    <th style="width: 15%;">Tenggat Kembali</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($persetujuanPinjam ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->anggota->name ?? ($item->anggota->nama ?? 'Siswa') }}</strong></td>
                        <td>{{ $item->buku->judul ?? 'Buku Dihapus' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 6px;">
                                <form action="{{ route('peminjaman.setujui', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-acc" onclick="return confirm('ACC peminjaman buku ini?')">ACC</button>
                                </form>
                                <form action="{{ route('peminjaman.tolak', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-tolak" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #8b7865; padding: 20px;">
                            Tidak ada antrean peminjaman yang menunggu persetujuan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Riwayat Transaksi Keseluruhan --}}
    <div class="card">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">Riwayat Transaksi Perpustakaan</h1>
        <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">Daftar seluruh rekapitulasi transaksi peminjaman dan pengembalian buku.</p>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Peminjam</th>
                    <th style="width: 35%;">Judul Buku</th>
                    <th style="width: 15%;">Tanggal Pinjam</th>
                    <th style="width: 20%;">Status Peminjaman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($semuaTransaksi ?? [] as $idx => $trx)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $trx->anggota->name ?? ($trx->anggota->nama ?? 'Siswa') }}</strong></td>
                        <td>{{ $trx->buku->judul ?? 'Buku Dihapus' }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>
                            @if($trx->status == 'dipinjam')
                                <span class="badge badge-pinjam">Sedang Dipinjam</span>
                            @elseif($trx->status == 'dikembalikan')
                                <span class="badge badge-kembali">Sudah Dikembalikan</span>
                            @elseif($trx->status == 'menunggu')
                                <span class="badge badge-menunggu">Menunggu Persetujuan</span>
                            @elseif($trx->status == 'ditolak')
                                <span class="badge badge-tolak">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #8b7865; padding: 20px;">
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection