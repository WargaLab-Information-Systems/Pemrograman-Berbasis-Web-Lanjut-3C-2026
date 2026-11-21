<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pengembalian</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white shadow sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Anggota</th>
                            <th class="px-3 py-2">Buku</th>
                            <th class="px-3 py-2">Tgl Pinjam</th>
                            <th class="px-3 py-2">Tgl Kembali</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftar as $p)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $daftar->firstItem() + $loop->index }}</td>
                                <td class="px-3 py-2">{{ $p->anggota?->nama ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $p->buku?->judul ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $p->tanggal_pinjam }}</td>
                                <td class="px-3 py-2">{{ $p->tanggal_kembali ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="rounded px-2 py-0.5 text-xs {{ $p->status === 'dipinjam' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ $p->status }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    @if ($p->status === 'dipinjam')
                                        @can('catat-pengembalian-buku')
                                            <form method="POST" action="{{ route('pengembalian.store', $p) }}">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white text-xs">Catat Pengembalian</button>
                                            </form>
                                        @endcan
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-3 py-4 text-gray-500">Belum ada buku yang dipinjam.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $daftar->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
