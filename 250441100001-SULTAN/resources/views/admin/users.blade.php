<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Akun & Role') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openTambah: false, openRole: false, selectedUser: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded-lg text-sm font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Pengguna & Hak Akses (RBAC)</h3>
                        <p class="text-xs text-gray-500">Halaman khusus <strong>Admin</strong> untuk mengelola data akun dan pembagian role pengguna.</p>
                    </div>
                    
                    <!-- Permission: Kelola data pengguna -->
                    @can('Kelola data pengguna')
                        <button @click="openTambah = true" class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2 rounded shadow font-semibold">
                            + Tambah Pengguna Baru
                        </button>
                    @endcan
                </div>

                <table class="w-full border-collapse border border-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left font-semibold text-gray-700">
                            <th class="border p-3">No</th>
                            <th class="border p-3">Nama</th>
                            <th class="border p-3">Email</th>
                            <th class="border p-3">Role Saat Ini</th>
                            <th class="border p-3 text-center">Aksi (Kelola Data & Role)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $u)
                        <tr>
                            <td class="border p-3">{{ $index + 1 }}</td>
                            <td class="border p-3 font-semibold">{{ $u['name'] }}</td>
                            <td class="border p-3">{{ $u['email'] }}</td>
                            <td class="border p-3">
                                @if($u['role'] == 'Admin')
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-bold">Admin</span>
                                @elseif($u['role'] == 'Petugas')
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-bold">Petugas</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-bold">Siswa</span>
                                @endif
                            </td>
                            <td class="border p-3 text-center space-x-1">
                                <!-- Permission: Kelola role pengguna -->
                                @can('Kelola role pengguna')
                                    <button @click="selectedUser = {{ json_encode($u) }}; openRole = true" 
                                            class="bg-purple-600 hover:bg-purple-700 text-white text-xs px-2 py-1 rounded font-semibold">
                                        Ubah Role
                                    </button>
                                @endcan

                                <!-- Permission: Kelola data pengguna -->
                                @can('Kelola data pengguna')
                                    <form action="{{ route('admin.users.delete', $u['id']) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-2 py-1 rounded font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- MODAL TAMBAH PENGGUNA (LENGKAP DENGAN INPUT PASSWORD) -->
            <div x-show="openTambah" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
                    <h3 class="text-lg font-bold mb-4">Tambah Pengguna Baru</h3>
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-bold mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan Nama Pengguna" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-bold mb-1">Email</label>
                            <input type="email" name="email" required placeholder="nama@test.com" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-bold mb-1">Password</label>
                            <input type="password" name="password" required placeholder="Minimal 6 Karakter" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold mb-1">Pilih Role</label>
                            <select name="role" required class="w-full border p-2 rounded text-sm">
                                <option value="Siswa">Siswa</option>
                                <option value="Petugas">Petugas</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="openTambah = false" class="bg-gray-400 text-white px-3 py-1.5 rounded text-xs">Batal</button>
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-bold">Simpan & Buat Akun</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL UBAH ROLE -->
            <div x-show="openRole" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
                    <h3 class="text-lg font-bold mb-2">Ubah Role Pengguna</h3>
                    <p class="text-xs text-gray-600 mb-4">Pengguna: <strong x-text="selectedUser?.name"></strong></p>
                    
                    <form :action="'/admin/users/update-role/' + selectedUser?.id" method="POST">
                        @csrf
                        <input type="hidden" name="email" :value="selectedUser?.email">
                        <div class="mb-4">
                            <label class="block text-xs font-bold mb-1">Pilih Role Baru</label>
                            <select name="role" x-model="selectedUser.role" required class="w-full border p-2 rounded text-sm">
                                <option value="Admin">Admin</option>
                                <option value="Petugas">Petugas</option>
                                <option value="Siswa">Siswa</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="openRole = false" class="bg-gray-400 text-white px-3 py-1.5 rounded text-xs">Batal</button>
                            <button type="submit" class="bg-purple-600 text-white px-3 py-1.5 rounded text-xs font-bold">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>