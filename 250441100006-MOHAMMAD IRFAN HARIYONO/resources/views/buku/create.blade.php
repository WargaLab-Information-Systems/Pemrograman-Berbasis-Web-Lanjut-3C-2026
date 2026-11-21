<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Form Tambah Buku Baru</h3>
                    
                    <!-- Tampilan Form -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                            <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan judul...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                            <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama penulis...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                            <input type="number" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 2023">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Sains</option>
                                <option value="2">Fiksi</option>
                                <option value="3">Nonfiksi</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-6">
                        <a href="/buku" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                            Kembali
                        </a>
                        <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            Simpan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>