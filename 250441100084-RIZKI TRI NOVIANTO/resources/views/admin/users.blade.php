@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Pesan Notifikasi (Sukses / Error) -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md" role="alert">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md" role="alert">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Form Tambah Pengguna Baru -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Pengguna Baru</h3>
            
            <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Nama lengkap" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" placeholder="alamat@email.com" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tetapkan Role</label>
                    <select name="role" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition">
                        Tambah
                    </button>
                </div>
            </form>
        </div>

        <!-- Card Tabel Manajemen Akun & Penetapan Role -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Manajemen Akun & Penetapan Role Pengguna</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-3 text-sm font-semibold text-gray-700 text-center" style="width: 60px;">ID</th>
                            <th class="p-3 text-sm font-semibold text-gray-700">Nama Pengguna</th>
                            <th class="p-3 text-sm font-semibold text-gray-700">Email</th>
                            <th class="p-3 text-sm font-semibold text-gray-700">Role Saat Ini</th>
                            <th class="p-3 text-sm font-semibold text-gray-700" style="width: 280px;">Ubah Penetapan Role</th>
                            <th class="p-3 text-sm font-semibold text-gray-700 text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm text-gray-800 text-center">{{ $u->id }}</td>
                                <td class="p-3 text-sm text-gray-800 font-medium">{{ $u->name }}</td>
                                <td class="p-3 text-sm text-gray-800">{{ $u->email }}</td>
                                <td class="p-3 text-sm">
                                    <span class="bg-gray-800 text-white px-2.5 py-1 rounded text-xs font-bold uppercase">
                                        {{ $u->getRoleNames()->first() ?? 'Tanpa Role' }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm">
                                    @can('kelola role pengguna')
                                        <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="text-xs border-gray-300 rounded-md shadow-sm py-1 focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>
                                                        {{ strtoupper($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-gray-900 font-semibold px-3 py-1 rounded text-xs transition">
                                                Simpan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak ada izin</span>
                                    @endcan
                                </td>
                                <td class="p-3 text-sm text-center">
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-3 py-1 rounded text-xs transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection