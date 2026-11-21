<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Peminjaman{{ $anggota && (int) $anggota->user_id !== (int) Auth::id() ? ': ' . $anggota->nama : ' Saya' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white shadow sm:rounded-lg p-6 overflow-x-auto">
                @if (! $anggota)
                    <p class="text-sm text-gray-500">Akun Anda belum terhubung dengan data anggota, jadi belum ada riwayat peminjaman.</p>
                @else
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Buku</th>
                                <th class="px-3 py-2">Tgl Pinjam</th>
                                <th class="px-3 py-2">Tgl Kembali</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daftar as $p)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2">{{ $p->buku?->judul ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $p->tanggal_pinjam }}</td>
                                    <td class="px-3 py-2">{{ $p->tanggal_kembali ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $p->status }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-3 py-4 text-gray-500">Belum ada riwayat peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
