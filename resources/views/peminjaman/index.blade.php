@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
@php
    $user = auth()->user();
    // Penentuan role tanpa tergantung Spatie Blade directive
    $isSiswa = true;
    if ($user) {
        if (method_exists($user, 'hasRole')) {
            $isSiswa = $user->hasRole('siswa');
        } elseif (isset($user->roles)) {
            $isSiswa = $user->roles->pluck('name')->contains('siswa');
        }
    }
@endphp

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

    .book-form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 20px;
        margin-top: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #654a32;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        height: 45px;
        box-sizing: border-box;
        padding: 0 14px;
        border: 1px solid #e3cdb5;
        border-radius: 8px;
        background: #fffdf9;
        color: #654a32;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #654a32;
    }

    .form-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 10px;
        margin-top: 5px;
    }

    .btn {
        border: none;
        border-radius: 7px;
        padding: 10px 18px;
        cursor: pointer;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .btn-primary { background: #806548; color: white; }
    .btn-primary:hover { background: #654a32; }

    .btn-secondary { background: #eee2d0; color: #654a32; }
    .btn-secondary:hover { background: #e1d0b9; }

    .btn-return { 
        background: #38643a; 
        color: white; 
        padding: 6px 14px; 
        border: none;
        border-radius: 6px;
        font-size: 12px; 
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-return:hover { background: #274728; }

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

    .badge-pinjam { background: #eee2d0; color: #765638; }
    .badge-kembali { background: #d4edda; color: #155724; }
    .badge-menunggu { background: #fff3cd; color: #856404; }
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

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    @media (max-width: 600px) {
        .book-form { grid-template-columns: 1fr; }
    }
</style>

<div class="page-container">
    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">⚠ {{ session('error') }}</div>
    @endif

    {{-- BAGIAN FORM PEMINJAMAN (OTOMATIS AKUN LOGIN) --}}
    <div class="card">
        @if($isSiswa)
            <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">Ajukan Peminjaman Buku</h1>
            <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">Pilih buku yang ingin Anda pinjam. Pengajuan akan diproses oleh Admin/Guru.</p>
        @else
            <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">Catat Peminjaman Baru</h1>
            <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">Pilih buku untuk menambahkan transaksi peminjaman ke sistem.</p>
        @endif

        <form action="{{ route('peminjaman.store') }}" method="POST" class="book-form">
            @csrf

            {{-- Nama Peminjam Otomatis Terisi Akun Aktif (Tanpa Dropdown Opsi Pemilihan) --}}
            <input type="hidden" name="anggota_id" value="{{ auth()->id() }}">
            <div class="form-group">
                <label>Nama Peminjam</label>
                <input type="text" value="{{ auth()->user()->name ?? 'Pengguna' }}" readonly style="background: #f4ede2; cursor: not-allowed;">
            </div>

            {{-- Pilih Buku --}}
            <div class="form-group">
                <label for="buku_id">Pilih Buku</label>
                <select name="buku_id" id="buku_id" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($bukuList ?? [] as $buku)
                        <option value="{{ $buku->id }}">{{ $buku->judul }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal Pinjam --}}
            <div class="form-group">
                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required>
            </div>

            {{-- Tenggat Kembali --}}
            <div class="form-group">
                <label for="tanggal_kembali">Tenggat Kembali</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    @if($isSiswa)
                        + Ajukan Peminjaman
                    @else
                        + Catat Peminjaman
                    @endif
                </button>
                <button type="reset" class="btn btn-secondary">Kosongkan Form</button>
            </div>
        </form>
    </div>

    {{-- BAGIAN TABEL TRANSAKSI (HANYA TAMPIL UNTUK ADMIN & GURU) --}}
    @if(!$isSiswa)
    <div class="card">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">
            Daftar Transaksi Peminjaman
        </h1>
        <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">
            Manajemen data peminjaman buku aktif dan pemrosesan pengembalian.
        </p>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Peminjam</th>
                    <th style="width: 25%;">Judul Buku</th>
                    <th style="width: 15%;">Tanggal Pinjam</th>
                    <th style="width: 15%;">Tenggat Kembali</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman ?? [] as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->anggota->name ?? ($item->anggota->nama ?? 'Siswa') }}</strong></td>
                        <td>{{ $item->buku->judul ?? 'Buku Dihapus' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                        <td>
                            @if($item->status == 'dipinjam')
                                <span class="badge badge-pinjam">Sedang Dipinjam</span>
                            @elseif($item->status == 'dikembalikan')
                                <span class="badge badge-kembali">Sudah Dikembalikan</span>
                            @elseif($item->status == 'menunggu')
                                <span class="badge badge-menunggu">Menunggu Persetujuan</span>
                            @elseif($item->status == 'ditolak')
                                <span class="badge badge-tolak">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status === 'dipinjam')
                                <form action="{{ route('peminjaman.kembali', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-return" onclick="return confirm('Proses pengembalian buku ini?')">
                                        Kembali
                                    </button>
                                </form>
                            @else
                                <span style="color: #8b7865; font-size: 11px;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #8b7865; padding: 20px;">
                            Belum ada transaksi peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection