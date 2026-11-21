<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $judul }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <x-flash />

            {{-- Filter status (hanya di halaman kelola peminjaman) --}}
            @if ($mode === 'peminjaman')
                <form method="GET" action="{{ route('kelola.peminjaman') }}" class="flex gap-2">
                    <select name="status" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua status</option>
                        @foreach (['diajukan', 'dipinjam', 'dikembalikan', 'ditolak'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-900">Filter</button>
                </form>
            @endif

            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Anggota</th>
                            <th class="px-4 py-2">Buku</th>
                            <th class="px-4 py-2">Tgl Pinjam</th>
                            <th class="px-4 py-2">Tgl Kembali</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $p)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $p->id }}</td>
                                <td class="px-4 py-2">{{ $p->anggota?->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $p->buku?->judul ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">{{ $p->tanggal_kembali?->format('d-m-Y') ?? '-' }}</td>
                                <td class="px-4 py-2"><x-status-badge :status="$p->status" /></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">

                                        {{-- Setujui / tolak pengajuan --}}
                                        @if ($mode === 'peminjaman' && $p->status === 'diajukan')
                                            @can('memproses persetujuan peminjaman')
                                                <form method="POST" action="{{ route('kelola.peminjaman.setujui', $p) }}">
                                                    @csrf
                                                    <button class="rounded bg-green-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-green-700">Setujui</button>
                                                </form>
                                                <form method="POST" action="{{ route('kelola.peminjaman.tolak', $p) }}">
                                                    @csrf
                                                    <button class="rounded bg-red-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-red-700">Tolak</button>
                                                </form>
                                            @endcan
                                        @endif

                                        {{-- Catat pengembalian --}}
                                        @if ($mode === 'pengembalian' && $p->status === 'dipinjam')
                                            @can('mencatat pengembalian buku')
                                                <form method="POST" action="{{ route('kelola.pengembalian.store', $p) }}">
                                                    @csrf
                                                    <button class="rounded bg-blue-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-blue-700">Catat Pengembalian</button>
                                                </form>
                                            @endcan
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $peminjamans->links() }}
        </div>
    </div>
</x-app-layout>
