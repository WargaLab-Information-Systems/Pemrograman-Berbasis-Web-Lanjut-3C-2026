<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peminjaman & Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded-lg text-sm font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Modul Kelola Peminjaman & Persetujuan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">1. Kelola Transaksi & Persetujuan Peminjaman</h3>
                <table class="w-full border-collapse border border-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border p-2">Peminjam</th>
                            <th class="border p-2">Judul Buku</th>
                            <th class="border p-2">Tgl Pinjam</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2 text-center">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $item)
                        <tr>
                            <td class="border p-2 font-medium">{{ $item['user_name'] }}</td>
                            <td class="border p-2">{{ $item['judul'] }}</td>
                            <td class="border p-2">{{ $item['tanggal_pinjam'] }}</td>
                            <td class="border p-2">
                                @if($item['status'] == 'Menunggu Persetujuan')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">Menunggu Persetujuan</span>
                                @elseif($item['status'] == 'Sedang Dipinjam')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Sedang Dipinjam</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Sudah Dikembalikan</span>
                                @endif
                            </td>
                            <td class="border p-2 text-center space-x-1">
                                @if($item['status'] == 'Menunggu Persetujuan')
                                    @can('Memproses persetujuan peminjaman')
                                    <form action="{{ route('peminjaman.setujui', $item['id']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                            Setujui Peminjaman
                                        </button>
                                    </form>
                                    @endcan
                                @elseif($item['status'] == 'Sedang Dipinjam')
                                    @can('Mencatat pengembalian buku')
                                    <form action="{{ route('peminjaman.kembali', $item['id']) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                            Catat Pengembalian
                                        </button>
                                    </form>
                                    @endcan
                                @else
                                    <span class="text-xs text-gray-500 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="border p-3 text-center text-gray-500">
                                Belum ada pengajuan peminjaman buku. Silakan ajukan peminjaman dari menu <strong>Koleksi Buku</strong>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modul Laporan Perpustakaan -->
            @can('Lihat laporan perpustakaan')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-bold mb-2">2. Rekap Laporan Perpustakaan</h3>
                <p class="text-sm text-gray-600 mb-4">Ringkasan statistik peminjaman buku secara real-time.</p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="p-4 bg-blue-50 rounded">
                        <p class="text-2xl font-bold text-blue-700">{{ count($peminjamans) }}</p>
                        <p class="text-xs text-gray-600">Total Peminjaman</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded">
                        <p class="text-2xl font-bold text-green-700">
                            {{ count(array_filter($peminjamans, fn($i) => $i['status'] == 'Sudah Dikembalikan')) }}
                        </p>
                        <p class="text-xs text-gray-600">Buku Dikembalikan</p>
                    </div>
                    <div class="p-4 bg-yellow-50 rounded">
                        <p class="text-2xl font-bold text-yellow-700">
                            {{ count(array_filter($peminjamans, fn($i) => $i['status'] != 'Sudah Dikembalikan')) }}
                        </p>
                        <p class="text-xs text-gray-600">Masih Dipinjam / Menunggu</p>
                    </div>
                </div>
            </div>
            @endcan

        </div>
    </div>
</x-app-layout>