<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $judul }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <x-flash />

            <p class="text-sm text-gray-600">{{ $keterangan }}</p>

            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-2">Buku</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Tgl Pinjam</th>
                            <th class="px-4 py-2">Tgl Kembali</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $p)
                            <tr class="border-t">
                                <td class="px-4 py-2">
                                    <a href="{{ route('peminjaman.show', $p) }}" class="font-semibold text-indigo-600 hover:underline">{{ $p->buku?->judul ?? '-' }}</a>
                                </td>
                                <td class="px-4 py-2">{{ $p->buku?->kategori?->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                <td class="px-4 py-2"><x-status-badge :status="$p->status" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('buku.index') }}" class="inline-block text-sm text-indigo-600 hover:underline">&laquo; Ke daftar buku</a>
        </div>
    </div>
</x-app-layout>
