<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Beranda Siswa</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800">Halo, {{ Auth::user()->name }}</h3>

                @if ($anggota)
                    <p class="mt-1 text-sm text-gray-500">Anggota perpustakaan: {{ $anggota->nama }} ({{ $anggota->email }})</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Akun Anda belum terhubung dengan data anggota.</p>
                @endif

                <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded border p-4">
                        <div class="text-sm text-gray-500">Diajukan</div>
                        <div class="text-2xl font-semibold text-amber-600">{{ $status->get('diajukan', 0) }}</div>
                    </div>
                    <div class="rounded border p-4">
                        <div class="text-sm text-gray-500">Sedang Dipinjam</div>
                        <div class="text-2xl font-semibold text-blue-600">{{ $status->get('dipinjam', 0) }}</div>
                    </div>
                    <div class="rounded border p-4">
                        <div class="text-sm text-gray-500">Dikembalikan</div>
                        <div class="text-2xl font-semibold text-green-600">{{ $status->get('dikembalikan', 0) }}</div>
                    </div>
                    <div class="rounded border p-4">
                        <div class="text-sm text-gray-500">Ditolak</div>
                        <div class="text-2xl font-semibold text-red-600">{{ $status->get('ditolak', 0) }}</div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3 text-sm">
                    @can('lihat-daftar-buku')
                        <a href="{{ route('buku.index') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Lihat Daftar Buku</a>
                    @endcan
                    @can('lihat-status-peminjaman-sendiri')
                        <a href="{{ route('peminjaman.saya') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Status Peminjaman</a>
                    @endcan
                    @can('lihat-riwayat-peminjaman-sendiri')
                        <a href="{{ route('riwayat.saya') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Riwayat Peminjaman</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
