<x-app-layout>
    <div class="pt-16 pb-20 bg-gray-100 min-h-screen" style="background-color: #f3f4f6;">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8">
            
            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
                    <p class="text-xs font-bold uppercase tracking-wider" style="color: #0284c7;">Total Transaksi</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalPinjam }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
                    <p class="text-xs font-bold uppercase tracking-wider" style="color: #f59e0b;">Sedang Dipinjam</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $sedangDipinjam }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200 text-center">
                    <p class="text-xs font-bold uppercase tracking-wider" style="color: #0284c7;">Sudah Dikembalikan</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $sudahKembali }}</p>
                </div>
            </div>

            {{-- Rekap Tabel --}}
            <div class="bg-white overflow-hidden shadow-md rounded-lg p-8 border border-gray-200">
                <div class="text-center mb-8 pb-4 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Rekapitulasi Transaksi Perpustakaan</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-300 text-sm font-bold text-gray-800">
                                <th class="py-3 px-4 w-12">No</th>
                                <th class="py-3 px-4 text-left">Peminjam</th>
                                <th class="py-3 px-4 text-left">Judul Buku</th>
                                <th class="py-3 px-4">Tgl Pinjam</th>
                                <th class="py-3 px-4">Tgl Kembali</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm font-medium text-gray-700">
                            @forelse($peminjamans as $index => $item)
                            <tr class="hover:bg-sky-50 transition-colors">
                                <td class="py-4 px-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="py-4 px-4 text-left font-bold text-gray-800">{{ $item->anggota->nama ?? ($item->anggota->user->name ?? 'Anggota') }}</td>
                                <td class="py-4 px-4 text-left">{{ $item->buku->judul ?? '-' }}</td>
                                <td class="py-4 px-4">{{ $item->tanggal_pinjam }}</td>
                                <td class="py-4 px-4">{{ $item->tanggal_kembali ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @if(empty($item->tanggal_kembali))
                                        <span class="inline-block px-3 py-1 text-xs font-bold text-white rounded shadow-sm" style="background-color: #f59e0b;">
                                            Dipinjam
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-bold text-white rounded shadow-sm" style="background-color: #0284c7;">
                                            Dikembalikan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">Belum ada transaksi untuk dilaporkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>