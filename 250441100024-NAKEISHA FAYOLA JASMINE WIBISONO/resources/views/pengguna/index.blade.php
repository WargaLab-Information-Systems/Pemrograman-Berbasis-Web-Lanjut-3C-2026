<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Pengguna dan Role</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="mb-4">
                <a href="{{ route('pengguna.create') }}" class="inline-block px-4 py-2 rounded bg-gray-800 text-white text-sm">+ Tambah Pengguna</a>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Email</th>
                            <th class="px-3 py-2">Role</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr class="border-b align-top">
                                <td class="px-3 py-2">{{ $users->firstItem() + $loop->index }}</td>
                                <td class="px-3 py-2">{{ $u->name }}</td>
                                <td class="px-3 py-2">{{ $u->email }}</td>
                                <td class="px-3 py-2">
                                    <span class="rounded bg-gray-100 px-2 py-0.5 text-xs">{{ $u->getRoleNames()->first() ?? 'tanpa role' }}</span>

                                    @can('kelola-role-pengguna')
                                        @if ($u->id !== Auth::id())
                                            <form method="POST" action="{{ route('pengguna.role', $u) }}" class="mt-2 flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="rounded border-gray-300 text-xs py-1">
                                                    @foreach ($roles as $r)
                                                        <option value="{{ $r }}" @selected($u->hasRole($r))>{{ $r }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="px-3 py-1 rounded bg-indigo-600 text-white text-xs">Tetapkan</button>
                                            </form>
                                        @endif
                                    @endcan
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex gap-2">
                                        <a href="{{ route('pengguna.edit', $u) }}" class="px-3 py-1 rounded bg-amber-500 text-white text-xs">Edit</a>
                                        @if ($u->id !== Auth::id())
                                            <form method="POST" action="{{ route('pengguna.destroy', $u) }}"
                                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-xs">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
