<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Pengguna</h2>
            <a href="{{ route('admin.pengguna.create') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">+ Tambah Pengguna</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <x-flash />

            <form method="GET" action="{{ route('admin.pengguna.index') }}" class="flex gap-2">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email"
                       class="w-full max-w-xs rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button class="rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-900">Cari</button>
            </form>

            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $u->name }}</td>
                                <td class="px-4 py-2">{{ $u->email }}</td>
                                <td class="px-4 py-2">
                                    @forelse ($u->roles as $role)
                                        <span class="rounded bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-800">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-gray-400">-</span>
                                    @endforelse
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.pengguna.edit', $u) }}" class="font-semibold text-indigo-600 hover:underline">Ubah</a>

                                        @if ($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.pengguna.destroy', $u) }}"
                                                  onsubmit="return confirm('Hapus pengguna {{ $u->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="font-semibold text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">(akun Anda)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
