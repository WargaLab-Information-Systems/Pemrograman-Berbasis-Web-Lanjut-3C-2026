<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if ($buku)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-xl font-bold mb-6 border-b pb-4">Informasi Lengkap Buku</h3>
                        
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-left">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-3 w-1/3 text-gray-600 font-medium">ID Buku</th>
                                        <td class="py-3 font-semibold">: {{ $buku->id }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-3 text-gray-600 font-medium">Judul Buku</th>
                                        <td class="py-3 font-semibold">: {{ $buku->judul ?? $buku->judul_buku }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-3 text-gray-600 font-medium">Penulis</th>
                                        <td class="py-3 font-semibold">: {{ $buku->penulis ?? $buku->nama_penulis }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-3 text-gray-600 font-medium">Tahun Terbit</th>
                                        <td class="py-3 font-semibold">: {{ $buku->tahun_terbit ?? $buku->tahun }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <th class="py-3 text-gray-600 font-medium">Kategori</th>
                                        <td class="py-3 font-semibold">: 
                                            <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-bold">
                                                {{ $buku->kategori->nama ?? 'Tanpa Kategori' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('buku.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                                &laquo; Kembali ke Daftar Buku
                            </a>
                            
                            <!-- Tambahan: Jika siswa yang melihat, bisa langsung ajukan pinjam dari sini -->
                            <!-- @role('siswa')
                                <button onclick="alert('Mengajukan peminjaman buku: {{ $buku->judul ?? $buku->judul_buku }}')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                    Ajukan Pinjam
                                </button>
                            @endrole -->
                        </div>
                    </div>
                </div>
            @else
                <!-- Jika buku tidak ditemukan -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center shadow-sm">
                    <h2 class="text-2xl font-bold text-red-700 mb-2">Buku Tidak Ditemukan</h2>
                    <p class="text-red-600 mb-6">Maaf, buku dengan ID yang Anda cari tidak dapat ditemukan dalam sistem.</p>
                    <a href="{{ route('buku.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                        &laquo; Kembali ke Daftar Buku
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>