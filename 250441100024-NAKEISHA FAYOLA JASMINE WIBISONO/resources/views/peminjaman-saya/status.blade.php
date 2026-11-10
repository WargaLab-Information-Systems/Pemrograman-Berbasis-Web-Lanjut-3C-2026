<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Status Peminjaman Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white shadow sm:rounded-lg p-6 overflow-x-auto">
                @if (! $anggota)
                    <p class="text-sm text-gray-500">Akun Anda belum terhubung dengan data anggota. Ajukan peminjaman dari halaman detail buku, atau hubungi admin.</p>
                @else
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Buku</th>
                                <th class="px-3 py-2">Tanggal</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftar as $p)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2">{{ $p->buku?->judul ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $p->tanggal_pinjam }}</td>
                                    <td class="px-3 py-2">
                                        <span class="rounded px-2 py-0.5 text-xs {{ $p->status === 'diajukan' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">{{ $p->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-3 py-4 text-gray-500">Tidak ada pengajuan atau peminjaman yang sedang berjalan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

                <div class="mt-4 text-sm">
                    @can('lihat-riwayat-peminjaman-sendiri')
                        <a href="{{ route('riwayat.saya') }}" class="text-indigo-600 underline">Lihat riwayat lengkap</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
