<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Perpustakaan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                @foreach ($ringkasan as $label => $nilai)
                    <div class="rounded-lg bg-white p-4 shadow">
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ $nilai }}</div>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-2">

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="border-b px-4 py-3 font-semibold text-gray-800">5 Buku Paling Sering Dipinjam</div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th class="px-4 py-2">Judul</th>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terpopuler as $buku)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $buku->judul }}</td>
                                    <td class="px-4 py-2">{{ $buku->kategori?->nama }}</td>
                                    <td class="px-4 py-2 font-semibold">{{ $buku->total_dipinjam }}x</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="border-b px-4 py-3 font-semibold text-gray-800">Jumlah Buku per Kategori</div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Jumlah Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perKategori as $kategori)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $kategori->nama }}</td>
                                    <td class="px-4 py-2 font-semibold">{{ $kategori->bukus_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
