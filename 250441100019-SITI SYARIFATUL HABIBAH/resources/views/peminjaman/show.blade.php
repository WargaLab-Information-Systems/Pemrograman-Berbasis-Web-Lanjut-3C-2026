<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Peminjaman #{{ $peminjaman->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">
                <dl class="grid grid-cols-3 gap-y-3 text-sm">
                    <dt class="text-gray-500">Peminjam</dt>
                    <dd class="col-span-2 font-medium text-gray-900">{{ $peminjaman->anggota?->nama ?? '-' }}</dd>

                    <dt class="text-gray-500">Buku</dt>
                    <dd class="col-span-2 font-medium text-gray-900">{{ $peminjaman->buku?->judul ?? '-' }}</dd>

                    <dt class="text-gray-500">Kategori</dt>
                    <dd class="col-span-2">{{ $peminjaman->buku?->kategori?->nama ?? '-' }}</dd>

                    <dt class="text-gray-500">Tanggal Pinjam</dt>
                    <dd class="col-span-2">{{ $peminjaman->tanggal_pinjam?->format('d-m-Y') }}</dd>

                    <dt class="text-gray-500">Tanggal Kembali</dt>
                    <dd class="col-span-2">{{ $peminjaman->tanggal_kembali?->format('d-m-Y') ?? '-' }}</dd>

                    <dt class="text-gray-500">Status</dt>
                    <dd class="col-span-2"><x-status-badge :status="$peminjaman->status" /></dd>
                </dl>

                <a href="{{ route('peminjaman.riwayat') }}" class="mt-6 inline-block text-sm text-indigo-600 hover:underline">&laquo; Kembali ke riwayat</a>
            </div>
        </div>
    </div>
</x-app-layout>
