<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                {{-- Dashboard Admin --}}
                @role('admin')
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Selamat Datang di Dashboard {{ Auth::user()->name }}
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Sistem pencarian dan pengelolaan data perpustakaan secara terstruktur.
                    </p>

                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-800 mb-3">Hak Akses & Tugas Admin:</h4>
                        <ul class="list-disc list-inside text-gray-700 space-y-2 text-sm">
                            <li>Mengelola data akun pengguna dan peran (Role).</li>
                            <li>Mengelola data koleksi buku perpustakaan.</li>
                            <li>Memproses persetujuan peminjaman dan pengembalian buku.</li>
                        </ul>
                    </div>
                @endrole

                {{-- Dashboard Petugas --}}
                @role('petugas')
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Selamat Datang di Dashboard {{ Auth::user()->name }}
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Sistem pencarian dan pengelolaan data perpustakaan secara terstruktur.
                    </p>

                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-800 mb-3">Hak Akses & Tugas Petugas:</h4>
                        <ul class="list-disc list-inside text-gray-700 space-y-2 text-sm">
                            <li>Memproses persetujuan pengajuan peminjaman buku dari siswa.</li>
                            <li>Mencatat transaksi pengembalian buku.</li>
                            <li>Melihat dan mengelola data koleksi buku perpustakaan.</li>
                        </ul>
                    </div>
                @endrole

                {{-- Dashboard Siswa --}}
                @role('siswa')
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Selamat Datang di Dashboard {{ Auth::user()->name }}
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Sistem pencarian dan pengelolaan data perpustakaan secara terstruktur.
                    </p>

                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-800 mb-3">Fitur yang Dapat Diakses:</h4>
                        <ul class="list-disc list-inside text-gray-700 space-y-2 text-sm">
                            <li>Melihat daftar koleksi buku perpustakaan.</li>
                            <li>Mengajukan peminjaman buku secara online.</li>
                            <li>Melihat riwayat transaksi peminjaman pribadi.</li>
                            <li>Mengubah informasi profil akun.</li>
                        </ul>
                    </div>
                @endrole

            </div>
        </div>
    </div>
</x-app-layout>