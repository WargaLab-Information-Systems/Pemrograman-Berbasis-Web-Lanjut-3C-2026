<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Peminjaman</h2>
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
                                <td class="px-3 py-2">
                                    {{ $p->anggota?->nama ?? '-' }}
                                    @if ($p->anggota)
                                        <a href="{{ route('riwayat.show', $p->anggota_id) }}" class="text-xs text-indigo-600 underline">riwayat</a>
                                    @endif
                                </td>
                                <td class="px-3 py-2">{{ $p->buku?->judul ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $p->tanggal_pinjam }}</td>
                                <td class="px-3 py-2">{{ $p->tanggal_kembali ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    <span class="rounded px-2 py-0.5 text-xs
                                        @switch($p->status)
                                            @case('diajukan') bg-amber-100 text-amber-800 @break
                                            @case('dipinjam') bg-blue-100 text-blue-800 @break
                                            @case('dikembalikan') bg-green-100 text-green-800 @break
                                            @default bg-red-100 text-red-800
                                        @endswitch">{{ $p->status }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    @if ($p->status === 'diajukan')
                                        @can('proses-persetujuan-peminjaman')
                                            <div class="flex gap-2">
                                                <form method="POST" action="{{ route('peminjaman.setujui', $p) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white text-xs">Setujui</button>
                                                </form>
                                                <form method="POST" action="{{ route('peminjaman.tolak', $p) }}">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-xs">Tolak</button>
                                                </form>
                                            </div>
                                        @endcan
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-3 py-4 text-gray-500">Belum ada data peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $daftar->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
