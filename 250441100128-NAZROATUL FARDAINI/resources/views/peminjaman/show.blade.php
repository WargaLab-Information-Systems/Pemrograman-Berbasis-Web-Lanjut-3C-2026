<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Peminjaman Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Informasi Peminjaman (ID Peminjaman: {{ $peminjaman->id }})</h3>
                

                <p><strong>Nama Peminjam:</strong> {{ $peminjaman->anggota->nama ?? 'Tidak Diketahui' }}</p>
<p><strong>ID Anggota:</strong> {{ $peminjaman->anggota_id }}</p>
                <p><strong>Buku ID:</strong> {{ $peminjaman->buku_id }}</p>
                <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $peminjaman->status ?? 'Aktif' }}</p>
                
                <div class="mt-6">
                    <span class="px-4 py-2 bg-red-100 text-red-700 rounded font-semibold">
                        Bukti Celah BOLA: Anda bisa melihat data milik user lain!
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>