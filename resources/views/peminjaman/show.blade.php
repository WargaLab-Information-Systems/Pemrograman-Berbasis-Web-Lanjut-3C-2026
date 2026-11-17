<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Peminjaman') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">Informasi lengkap data peminjaman buku.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl class="divide-y divide-gray-100">
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">ID Peminjaman</dt>
                        <dd class="text-gray-900">{{ $peminjaman->id }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">Nama Peminjam</dt>
                        <dd class="text-gray-900">{{ $peminjaman->anggota->nama ?? '-' }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">Judul Buku</dt>
                        <dd class="text-gray-900">{{ $peminjaman->buku->judul ?? '-' }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">Email Peminjam</dt>
                        <dd class="text-gray-900">{{ $peminjaman->anggota->email ?? '-' }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">Tanggal Pinjam</dt>
                        <dd class="text-gray-900">{{ $peminjaman->tanggal_pinjam }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">Tanggal Kembali</dt>
                        <dd class="text-gray-900">{{ $peminjaman->tanggal_kembali ?? 'Belum Dikembalikan' }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 sm:grid-cols-2 sm:gap-4">
                        <dt class="font-semibold text-gray-700">Status</dt>
                        <dd>
                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">
                                {{ $peminjaman->status }}
                            </span>
                        </dd>
                    </div>
                </dl>

                @can('kelola peminjaman')
                    <a href="{{ route('peminjaman.kelola') }}" class="mt-6 inline-flex rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">
                        Kembali
                    </a>
                @else
                    <a href="{{ route('peminjaman.index') }}" class="mt-6 inline-flex rounded-md bg-emerald-700 px-4 py-2 font-semibold text-white hover:bg-emerald-800">
                        Kembali
                    </a>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
