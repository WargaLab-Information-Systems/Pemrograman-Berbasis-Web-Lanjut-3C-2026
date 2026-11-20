@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Tambah Buku Baru</h2>
            <form action="{{ route('buku.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="kategori_id" class="w-full border-gray-300 rounded-md" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori ?? $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Judul Buku</label>
                    <input type="text" name="judul" class="w-full border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Penulis</label>
                    <input type="text" name="penulis" class="w-full border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" class="w-full border-gray-300 rounded-md" required>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold">Simpan Buku</button>
                    <a href="{{ route('buku.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-semibold">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection