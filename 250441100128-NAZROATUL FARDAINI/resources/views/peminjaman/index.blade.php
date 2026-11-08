<x-app-layout>
    <div class="pt-16 pb-20 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="bg-white overflow-hidden shadow-md rounded-lg p-8 border border-gray-200">
                
                <div class="text-center mb-8 pb-4 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Data Peminjaman</h2>
                    <p class="text-sm text-gray-500 mt-1">Proses transaksi peminjaman dan pengembalian buku</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-3 bg-sky-100 border border-sky-400 text-sky-800 rounded text-center text-sm font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-center border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-300 text-sm font-bold text-gray-800">
                                <th class="py-3 px-4 w-12">No</th>
                                <th class="py-3 px-4 text-left">Peminjam</th>
                                <th class="py-3 px-4 text-left">Judul Buku</th>
                                <th class="py-3 px-4">Tgl Pinjam</th>
                                <th class="py-3 px-4">Tgl Kembali</th>
                                <th class="py-3 px-4">Aksi</th>
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
                                    @if($item->tanggal_kembali == null)
                                        <form action="{{ route('peminjaman.updateStatus', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-1.5 bg-sky-400 hover:bg-sky-500 text-white text-xs font-bold rounded shadow transition-colors">
                                                Proses Pengembalian
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs font-bold text-gray-400">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">Belum ada transaksi peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>