<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <h3 class="text-lg font-bold mb-4">Daftar Buku yang Dipinjam</h3>
                    
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="p-3 font-semibold">Judul Buku</th>
                                <th class="p-3 font-semibold">Tanggal Pinjam</th>
                                <th class="p-3 font-semibold">Batas Kembali</th>
                                <th class="p-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Looping Data Dinamis dari Database -->
                            @forelse ($riwayats as $riwayat)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="p-3 font-medium">{{ $riwayat->buku->judul ?? 'Buku Tidak Diketahui' }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($riwayat->tanggal_pinjam)->format('Y-m-d') }}</td>
                                    <td class="p-3">{{ $riwayat->tanggal_kembali ? \Carbon\Carbon::parse($riwayat->tanggal_kembali)->format('Y-m-d') : 'Belum ditentukan' }}</td>
                                    <td class="p-3">
                                        @if($riwayat->status == 'Menunggu Persetujuan')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">{{ $riwayat->status }}</span>
                                        @elseif($riwayat->status == 'Sedang Dipinjam')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">{{ $riwayat->status }}</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">{{ $riwayat->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center p-6 text-gray-500">Anda belum memiliki riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>