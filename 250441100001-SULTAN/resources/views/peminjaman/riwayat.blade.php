<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat & Status Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded-lg text-sm font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Peminjaman Pribadi</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                        Authenticated User ID: {{ auth()->id() }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Halaman ini menerapkan <strong>pemeriksaan kepemilikan (ownership check)</strong>. Hanya menampilkan data milik akun <strong>{{ auth()->user()->name }}</strong>.
                </p>

                <table class="w-full border-collapse border border-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border p-3">No</th>
                            <th class="border p-3">Judul Buku</th>
                            <th class="border p-3">Tanggal Pinjam</th>
                            <th class="border p-3">Tanggal Kembali</th>
                            <th class="border p-3 text-center">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('Melihat status peminjaman sendiri')
                            @forelse($peminjamans as $item)
                            <tr>
                                <td class="border p-3">{{ $loop->iteration }}</td>
                                <td class="border p-3 font-semibold">{{ $item['judul'] }}</td>
                                <td class="border p-3">{{ $item['tanggal_pinjam'] }}</td>
                                <td class="border p-3">{{ $item['tanggal_kembali'] }}</td>
                                <td class="border p-3 text-center space-x-2">
                                    @if($item['status'] == 'Menunggu Persetujuan')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($item['status'] == 'Sedang Dipinjam')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded mr-2">
                                            Sedang Dipinjam
                                        </span>
                                        <form action="{{ route('peminjaman.kembalikan.sendiri', $item['id']) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded text-xs font-semibold">
                                                Kembalikan Buku
                                            </button>
                                        </form>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">
                                            Sudah Dikembalikan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border p-3 text-center text-gray-500">
                                    Belum ada data peminjaman buku untuk akun Anda. Silakan ajukan di menu <strong>Koleksi Buku</strong>.
                                </td>
                            </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="5" class="border p-3 text-center text-red-500">
                                    Anda tidak memiliki hak akses untuk melihat status peminjaman.
                                </td>
                            </tr>
                        @endcan
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>