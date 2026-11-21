@extends('layouts.app')

@section('title', 'Riwayat Saya')

@section('content')
<style>
    .page-container { 
        max-width: 1000px; 
        margin: 30px auto; 
        padding: 20px; 
        font-family: 'Poppins', sans-serif; 
        color: #493728;
    }

    .card { 
        background: #fffdf9; 
        border: 1px solid #eadfce; 
        border-radius: 12px; 
        padding: 24px; 
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
        font-weight: 600;
    }

    .table-custom td { 
        padding: 12px; 
        border-bottom: 1px solid #eee5d9; 
        font-size: 13px; 
        color: #5f5043;
        vertical-align: middle;
    }

    .badge { 
        padding: 5px 12px; 
        border-radius: 12px; 
        font-size: 11px; 
        font-weight: 600; 
        display: inline-block;
    }

    .badge-menunggu { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .badge-pinjam { background: #eee2d0; color: #765638; }
    .badge-kembali { background: #d4edda; color: #155724; }
    .badge-tolak { background: #f8d7da; color: #721c24; }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
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

    <div class="card">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">
            Riwayat & Status Peminjaman Diri Sendiri
        </h1>
        <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">
            Daftar peminjaman buku yang terdaftar atas nama <strong>{{ auth()->user()->name }}</strong>.
        </p>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Judul Buku</th>
                    <th style="width: 20%;">Tanggal Pinjam</th>
                    <th style="width: 20%;">Tenggat Kembali</th>
                    <th style="width: 20%;">Status Peminjaman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->buku->judul ?? 'Buku Dihapus' }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                        <td>
                            @if($item->status == 'menunggu')
                                <span class="badge badge-menunggu">Menunggu Persetujuan</span>
                            @elseif($item->status == 'dipinjam')
                                <span class="badge badge-pinjam">Sedang Dipinjam</span>
                            @elseif($item->status == 'dikembalikan')
                                <span class="badge badge-kembali">Sudah Dikembalikan</span>
                            @elseif($item->status == 'ditolak')
                                <span class="badge badge-tolak">Ditolak</span>
                            @else
                                <span class="badge">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #8b7865; padding: 20px;">
                            Belum ada riwayat peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection