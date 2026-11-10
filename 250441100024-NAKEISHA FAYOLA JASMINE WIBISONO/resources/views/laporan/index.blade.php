<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Perpustakaan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash />

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Total Buku</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ $ringkasan['total_buku'] }}</div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Total Anggota</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ $ringkasan['total_anggota'] }}</div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-5">
                    <div class="text-sm text-gray-500">Total Peminjaman</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ $ringkasan['total_peminjaman'] }}</div>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Peminjaman Menurut Status</h3>
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr><th class="px-3 py-2">Status</th><th class="px-3 py-2">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        @foreach (['diajukan', 'dipinjam', 'dikembalikan', 'ditolak'] as $s)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $s }}</td>
                                <td class="px-3 py-2">{{ $status->get($s, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">5 Buku Paling Sering Dipinjam</h3>
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr><th class="px-3 py-2">Judul</th><th class="px-3 py-2">Penulis</th><th class="px-3 py-2">Jumlah Peminjaman</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($populer as $b)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $b->judul }}</td>
                                <td class="px-3 py-2">{{ $b->penulis }}</td>
                                <td class="px-3 py-2">{{ $b->peminjamans_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-3 py-4 text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Jumlah Buku per Kategori</h3>
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr><th class="px-3 py-2">Kategori</th><th class="px-3 py-2">Jumlah Buku</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($perKategori as $k)
                            <tr class="border-b">
                                <td class="px-3 py-2">{{ $k->nama }}</td>
                                <td class="px-3 py-2">{{ $k->jumlah_buku }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
