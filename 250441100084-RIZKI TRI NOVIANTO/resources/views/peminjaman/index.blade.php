@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            
            <!-- Header & Badge Akses -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 pb-3 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Kelola Peminjaman & Pengembalian Buku</h3>
                <span class="mt-2 md:mt-0 bg-blue-600 text-white font-semibold px-3 py-1 rounded-md text-xs">
                    Akses Admin & Petugas
                </span>
            </div>
            <p class="text-gray-500 text-sm mb-4">
                Daftar transaksi peminjaman untuk memproses persetujuan dan mencatat pengembalian buku.
            </p>

            <!-- Tabel Peminjaman -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-3 text-sm font-bold text-gray-900 text-center border-r border-gray-200" style="width: 50px;">No</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Nama Peminjam</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Judul Buku</th>
                            <th class="p-3 text-sm font-bold text-gray-900 border-r border-gray-200">Penulis</th>
                            <th class="p-3 text-sm font-bold text-gray-900 text-center border-r border-gray-200">Tanggal Pinjam</th>
                            <th class="p-3 text-sm font-bold text-gray-900 text-center border-r border-gray-200">Status</th>
                            <th class="p-3 text-sm font-bold text-gray-900 text-center" style="width: 220px;">Aksi Operasional</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $index => $item)
                        @php
                            $status = $item->status ?? 'Pengajuan';
                            $badgeColor = match($status) {
                                'Dipinjam' => 'bg-cyan-500 text-white',
                                'Selesai' => 'bg-emerald-600 text-white',
                                'Pengajuan' => 'bg-amber-400 text-gray-900',
                                default => 'bg-gray-200 text-gray-700'
                            };
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="p-3 text-sm text-gray-800 text-center border-r border-gray-200">{{ $index + 1 }}</td>
                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">
                                {{ $item->anggota->name ?? 'Anggota Tak Dikenal' }}
                            </td>
                            <td class="p-3 text-sm text-gray-900 font-medium border-r border-gray-200">{{ $item->buku->judul ?? '-' }}</td>
                            <td class="p-3 text-sm text-gray-800 border-r border-gray-200">{{ $item->buku->penulis ?? '-' }}</td>
                            <td class="p-3 text-sm text-gray-800 text-center border-r border-gray-200">
                                {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="p-3 text-sm text-center border-r border-gray-200">
                                <span id="status-{{ $item->id }}" class="{{ $badgeColor }} font-bold px-2.5 py-1 rounded text-xs">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="p-3 text-sm text-center">
                                <div class="flex justify-center space-x-2">
                                    @can('memproses persetujuan peminjaman')
                                    <button type="button" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-1 rounded text-xs transition"
                                            onclick="ubahStatusKeDatabase({{ $item->id }}, 'Dipinjam')">
                                        Setujui
                                    </button>
                                    @endcan

                                    @can('mencatat pengembalian buku')
                                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 py-1 rounded text-xs transition"
                                            onclick="ubahStatusKeDatabase({{ $item->id }}, 'Selesai')">
                                        Catat Kembali
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500 text-sm">Belum ada transaksi peminjaman buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
async function ubahStatusKeDatabase(peminjamanId, statusBaru) {
    try {
        const response = await fetch(`/kelola/peminjaman/${peminjamanId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: statusBaru })
        });

        const result = await response.json();

        if (response.ok && result.success) {
            const badge = document.getElementById(`status-${peminjamanId}`);
            badge.innerText = statusBaru;

            if (statusBaru === 'Dipinjam') {
                badge.className = 'bg-cyan-500 text-white font-bold px-2.5 py-1 rounded text-xs';
            } else if (statusBaru === 'Selesai') {
                badge.className = 'bg-emerald-600 text-white font-bold px-2.5 py-1 rounded text-xs';
            }
        } else {
            alert(result.message || 'Gagal mengubah status.');
        }
    } catch (error) {
        alert('Terjadi kesalahan jaringan: ' + error.message);
    }
}
</script>
@endsection