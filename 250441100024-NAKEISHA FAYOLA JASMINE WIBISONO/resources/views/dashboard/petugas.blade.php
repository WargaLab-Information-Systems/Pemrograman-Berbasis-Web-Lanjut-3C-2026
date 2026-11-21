<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Petugas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Total Buku</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ $totalBuku }}</div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Total Anggota</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ $totalAnggota }}</div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Pengajuan Menunggu</div>
                    <div class="text-3xl font-semibold text-amber-600">{{ $status->get('diajukan', 0) }}</div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Sedang Dipinjam</div>
                    <div class="text-3xl font-semibold text-blue-600">{{ $status->get('dipinjam', 0) }}</div>
                </div>
            </div>

            <div class="mt-6 bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Menu Operasional</h3>
                <div class="flex flex-wrap gap-3 text-sm">
                    @can('kelola-data-buku')
                        <a href="{{ route('kelola.buku') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Kelola Buku</a>
                    @endcan
                    @can('kelola-peminjaman')
                        <a href="{{ route('peminjaman.index') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Proses Peminjaman</a>
                    @endcan
                    @can('kelola-pengembalian')
                        <a href="{{ route('pengembalian.index') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Catat Pengembalian</a>
                    @endcan
                    @can('lihat-laporan-perpustakaan')
                        <a href="{{ route('laporan.index') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Laporan</a>
                    @endcan
                </div>
                <p class="mt-4 text-xs text-gray-500">Petugas tidak dapat mengelola akun maupun role pengguna.</p>
            </div>
        </div>
    </div>
</x-app-layout>
