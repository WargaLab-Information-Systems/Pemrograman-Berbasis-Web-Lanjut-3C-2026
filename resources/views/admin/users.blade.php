@extends('layouts.app')

@section('title', 'Kelola Data Pengguna')

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
        padding: 20px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.04); 
        margin-bottom: 25px;
    }

    .user-form {
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
        padding: 9px 16px;
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

    .select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 110px;
    }

    .form-select {
        width: 100%;
        padding: 6px 24px 6px 10px;
        border: 1px solid #e3cdb5;
        border-radius: 6px;
        background-color: #fffdf9;
        color: #493728;
        font-size: 12px;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    .form-select:focus {
        border-color: #654a32;
    }

    .select-arrow {
        position: absolute;
        right: 8px;
        pointer-events: none;
        color: #654a32;
        font-size: 9px;
    }

    .btn-update {
        background: #806548;
        color: #ffffff;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-update:hover {
        background: #654a32;
    }

    .btn-delete {
        background: #a83232;
        color: #ffffff;
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-delete:hover {
        background: #852525;
    }

    .badge { 
        padding: 4px 10px; 
        border-radius: 12px; 
        font-size: 11px; 
        font-weight: 600; 
        display: inline-block;
        text-transform: capitalize;
    }

    .badge-admin { background: #ebd5cb; color: #722f18; }
    .badge-petugas { background: #e0e6cf; color: #3b501d; }
    .badge-siswa { background: #eee2d0; color: #765638; }

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
        .user-form { grid-template-columns: 1fr; }
    }
</style>

<div class="page-container">
    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">⚠ {{ session('error') }}</div>
    @endif

    {{-- Form Tambah Pengguna Baru --}}
    <div class="card">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">Tambah Pengguna Baru</h1>
        <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">Daftarkan akun pengguna baru beserta peran (role) yang diinginkan.</p>

        <form action="{{ route('admin.users.store') }}" method="POST" class="user-form">
            @csrf
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" placeholder="Masukkan nama pengguna" required>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="contoh@domain.com" required>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi (Password)</label>
                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="form-group">
                <label for="role">Pilih Role</label>
                <select name="role" id="role" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">+ Tambah Pengguna</button>
                <button type="reset" class="btn btn-secondary">Kosongkan Form</button>
            </div>
        </form>
    </div>

    {{-- Tabel Kelola Pengguna --}}
    <div class="card">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #654a32; margin-bottom: 8px;">Kelola Data Pengguna & Hak Akses</h1>
        <p style="color: #8b7865; font-size: 13px; margin-bottom: 15px;">Daftar seluruh pengguna terdaftar, pengubahan peran (role), dan manajemen akun.</p>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Pengguna</th>
                    <th style="width: 25%;">Email</th>
                    <th style="width: 15%;">Role Saat Ini</th>
                    <th style="width: 20%;">Ubah Role</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleName = $user->roles->pluck('name')->first() ?? 'Tidak ada';
                                $badgeClass = 'badge-siswa';
                                if($roleName === 'admin') $badgeClass = 'badge-admin';
                                elseif($roleName === 'petugas') $badgeClass = 'badge-petugas';
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $roleName }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                @csrf
                                @method('PATCH')
                                <div class="select-wrapper">
                                    <select name="role" class="form-select" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="select-arrow">▼</span>
                                </div>
                                <button type="submit" class="btn-update">Simpan</button>
                            </form>
                        </td>
                        <td>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 11px; color: #8b7865;">(Akun Anda)</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #8b7865; padding: 20px;">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection