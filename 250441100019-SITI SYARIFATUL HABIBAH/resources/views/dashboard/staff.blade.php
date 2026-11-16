<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $judul }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-flash />

            <p class="text-gray-700">
                Selamat datang, <strong>{{ auth()->user()->name }}</strong>
                @role('admin') <span class="ml-1 rounded bg-indigo-100 px-2 py-0.5 text-xs font-semibold text-indigo-800">Admin</span> @endrole
                @role('petugas') <span class="ml-1 rounded bg-teal-100 px-2 py-0.5 text-xs font-semibold text-teal-800">Petugas</span> @endrole
            </p>

            {{-- Kartu ringkasan --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
                @foreach ($kartu as $label => $nilai)
                    <div class="rounded-lg bg-white p-4 shadow">
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ $nilai }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Jumlah pengguna per role: hanya muncul di dashboard admin --}}
            @if ($perRole)
                <div class="rounded-lg bg-white p-4 shadow">
                    <h3 class="font-semibold text-gray-800">Pengguna per Role</h3>
                    <ul class="mt-2 flex flex-wrap gap-4 text-sm text-gray-700">
                        @foreach ($perRole as $role => $jumlah)
                            <li><span class="font-semibold">{{ ucfirst($role) }}</span>: {{ $jumlah }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Pintasan menu sesuai hak akses --}}
            <div class="rounded-lg bg-white p-4 shadow">
                <h3 class="font-semibold text-gray-800">Menu Cepat</h3>
                <div class="mt-3 flex flex-wrap gap-3 text-sm">
                    @can('kelola-buku')
                        <a href="{{ route('buku.index') }}" class="rounded-md bg-indigo-600 px-3 py-2 font-semibold text-white hover:bg-indigo-700">Kelola Buku</a>
                    @endcan
                    @can('kelola peminjaman')
                        <a href="{{ route('kelola.peminjaman') }}" class="rounded-md bg-indigo-600 px-3 py-2 font-semibold text-white hover:bg-indigo-700">Kelola Peminjaman</a>
                    @endcan
                    @can('kelola pengembalian')
                        <a href="{{ route('kelola.pengembalian') }}" class="rounded-md bg-indigo-600 px-3 py-2 font-semibold text-white hover:bg-indigo-700">Kelola Pengembalian</a>
                    @endcan
                    @can('lihat laporan perpustakaan')
                        <a href="{{ route('laporan.index') }}" class="rounded-md bg-indigo-600 px-3 py-2 font-semibold text-white hover:bg-indigo-700">Laporan</a>
                    @endcan
                    @can('kelola data pengguna')
                        <a href="{{ route('admin.pengguna.index') }}" class="rounded-md bg-gray-800 px-3 py-2 font-semibold text-white hover:bg-gray-900">Kelola Pengguna</a>
                    @endcan
                </div>
            </div>

            {{-- Pengajuan terbaru --}}
            @can('kelola peminjaman')
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="border-b px-4 py-3 font-semibold text-gray-800">Pengajuan Peminjaman Terbaru</div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th class="px-4 py-2">Anggota</th>
                                <th class="px-4 py-2">Buku</th>
                                <th class="px-4 py-2">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menunggu as $p)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $p->anggota?->nama ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $p->buku?->judul ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $p->tanggal_pinjam?->format('d-m-Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada pengajuan yang menunggu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endcan

        </div>
    </div>
</x-app-layout>
