@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')

<div class="page-title">
    <h2>Kelola Pengguna</h2>
    <p>Kelola akun pengguna dan penetapan role.</p>
</div>

<div class="toolbar">
    <a class="btn" href="{{ route('users.create') }}">+ Tambah Pengguna</a>

    <form method="GET" class="toolbar" style="margin:0;">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / email">
        <button class="btn btn-light" type="submit">Cari</button>
    </form>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr><th>No.</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse ($users as $u)
            <tr>
                <td>{{ $users->firstItem() + $loop->index }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                    @can('kelola role pengguna')
                        <form class="inline-form" method="POST" action="{{ route('users.role', $u) }}">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" @disabled($u->is(auth()->user()))>
                                @foreach (['admin', 'petugas', 'siswa'] as $r)
                                    <option value="{{ $r }}" @selected($u->hasRole($r))>{{ ucfirst($r) }}</option>
                                @endforeach
                            </select>
                        </form>
                    @else
                        @foreach ($u->roles as $r)
                            <span class="badge">{{ ucfirst($r->name) }}</span>
                        @endforeach
                    @endcan
                </td>
                <td>
                    <a class="btn btn-light btn-sm" href="{{ route('users.edit', $u) }}">Ubah</a>
                    <a class="btn btn-light btn-sm" href="{{ route('riwayat.user', $u) }}">Riwayat</a>
                    @unless ($u->is(auth()->user()))
                        <form class="inline-form" method="POST" action="{{ route('users.destroy', $u) }}"
                              onsubmit="return confirm('Hapus pengguna {{ addslashes($u->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    @endunless
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="empty">Tidak ada pengguna.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $users->links() }}

@endsection
