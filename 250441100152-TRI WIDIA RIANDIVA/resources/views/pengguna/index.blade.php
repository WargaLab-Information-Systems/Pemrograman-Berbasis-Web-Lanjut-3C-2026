<x-app-layout>
<div class="container bagian-buku">
    <h2 class="judul header-kelola">Kelola Data Pengguna & Role</h2>

    @if(session('success'))
        <div class="alert-notif alert-sukses">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-notif alert-bahaya">{{ session('error') }}</div>
    @endif

    <div class="tabel-buku-wrapper">
        <table class="table-detail tabel-pengguna">
            <thead style="background-color: #0f172a; color: #ffffff;">
                <tr>
                    <th class="th-no" style="color: #ffffff;">No</th>
                    <th class="th-pengguna" style="color: #ffffff;">Nama</th>
                    <th class="th-pengguna" style="color: #ffffff;">Email</th>
                    <th class="th-role" style="color: #ffffff;">Role Saat Ini</th>
                    <th class="th-aksi" style="color: #ffffff; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                <tr class="tr-pengguna">
                    <td class="td-center">{{ $index + 1 }}</td>
                    <td class="td-nama">{{ $u->name }}</td>
                    <td class="td-email">{{ $u->email }}</td>
                    <td class="td-center">
                        @php $role = $u->roles->pluck('name')->first(); @endphp
                        @if($role == 'admin')
                            <span class="badge-admin">ADMIN</span>
                        @elseif($role == 'petugas')
                            <span class="badge-petugas">PETUGAS</span>
                        @else
                            <span class="badge-siswa">SISWA</span>
                        @endif
                    </td>
                    <td class="td-center">
                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                            @if($role === 'admin')
                                <form action="{{ route('admin.users.updateRole', $u->id) }}" method="POST" style="display: flex; gap: 4px; align-items: center;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.8rem; color: #0f172a; min-width: 105px; line-height: 1.2;">
                                        <option value="admin" selected>ADMIN</option>
                                    </select>
                                    <button type="submit" style="background-color: #0f172a; color: #ffffff; padding: 6px 10px; border-radius: 6px; border: none; font-size: 0.8rem; cursor: pointer; white-space: nowrap;">
                                        Ubah
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.updateRole', $u->id) }}" method="POST" style="display: flex; gap: 4px; align-items: center;">
                                @csrf
                                @method('PATCH')
                                <select name="role" style="padding: 6px 12px 6px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.8rem; color: #0f172a; min-width: 105px; line-height: 1.2;">
                                    @foreach($roles as $r)
                                        <option value="{{ $r->name }}" {{ $u->hasRole($r->name) ? 'selected' : '' }}>
                                            {{ strtoupper($r->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" style="background-color: #0f172a; color: #ffffff; padding: 6px 10px; border-radius: 6px; border: none; font-size: 0.8rem; cursor: pointer; white-space: nowrap;">
                                    Ubah
                                </button>
                            </form>
                            @endif

                            @if($u->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-hapus" style="padding: 6px 10px; font-size: 0.8rem;">Hapus</button>
                            </form>
                            @else
                            <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic; margin-left: 4px;">Akun Saya</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>