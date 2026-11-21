<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Peminjaman, Pengembalian, & Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Bagian Laporan Singkat (Angka Dinamis dari Controller) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Ringkasan Laporan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                            <h4 class="text-blue-800 font-semibold mb-1">Total Buku Dipinjam</h4>
                            <p class="text-3xl font-bold text-blue-900">{{ $totalDipinjam }}</p>
                        </div>
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                            <h4 class="text-red-800 font-semibold mb-1">Pengajuan Baru (Menunggu)</h4>
                            <p class="text-3xl font-bold text-red-900">{{ $pengajuanBaru }}</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                            <h4 class="text-green-800 font-semibold mb-1">Pengembalian Bulan Ini</h4>
                            <p class="text-3xl font-bold text-green-900">{{ $pengembalianBulanIni }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pengajuan & Peminjaman Dinamis -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <h3 class="text-lg font-bold mb-4">Daftar Pengajuan & Peminjaman</h3>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="p-3 font-semibold">Nama Siswa</th>
                                <th class="p-3 font-semibold">Judul Buku</th>
                                <th class="p-3 font-semibold">Tgl Pengajuan</th>
                                <th class="p-3 font-semibold">Status</th>
                                
                                @role('petugas')
                                    <th class="p-3 font-semibold text-center">Aksi Petugas</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Looping Data dari Database -->
                            @forelse ($peminjamans as $pinjam)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <!-- Menampilkan Nama dan Judul dari Relasi -->
                                    <td class="p-3 font-medium">{{ $pinjam->anggota->nama ?? $pinjam->anggota->name ?? 'Data Terhapus' }}</td>
                                    <td class="p-3">{{ $pinjam->buku->judul ?? 'Data Terhapus' }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('Y-m-d') }}</td>
                                    
                                    <!-- Logika Warna Status Dinamis -->
                                    <td class="p-3">
                                        @if($pinjam->status == 'Menunggu Persetujuan')
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">{{ $pinjam->status }}</span>
                                        @elseif($pinjam->status == 'Sedang Dipinjam')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">{{ $pinjam->status }}</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">{{ $pinjam->status }}</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Logika Tombol Aksi Petugas -->
                                    <!-- Logika Tombol Aksi Petugas & Admin -->
                                    @hasanyrole('admin|petugas')
                                    <td class="p-3 text-center">
                                        @if($pinjam->status == 'Menunggu Persetujuan')
                                            <!-- Form Setujui -->
                                            <form action="{{ route('peminjaman.setujui', $pinjam->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Setujui peminjaman buku ini?')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">Setujui Peminjaman</button>
                                            </form>
                                        @elseif($pinjam->status == 'Sedang Dipinjam')
                                            <!-- Form Kembali -->
                                            <form action="{{ route('peminjaman.kembali', $pinjam->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Konfirmasi buku telah dikembalikan?')" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">Mencatat Pengembalian</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 italic text-sm">Selesai</span>
                                        @endif
                                    </td>
                                    @endhasanyrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-6 text-gray-500">Belum ada data pengajuan atau peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>