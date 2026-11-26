@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b">Detail Transaksi Peminjaman #{{ $peminjaman->id }}</h2>
            <div class="space-y-3 text-sm text-gray-800">
                <p><strong>ID Peminjam (Anggota):</strong> {{ $peminjaman->anggota_id }}</p>
                <p><strong>Nama Peminjam:</strong> {{ $peminjaman->anggota->name ?? $peminjaman->anggota->nama ?? '-' }}</p>
                <p><strong>Judul Buku:</strong> {{ $peminjaman->buku->judul ?? '-' }}</p>
                <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam ?? '-' }}</p>
                <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tanggal_kembali ?? '-' }}</p>
                <p><strong>Status:</strong> <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded font-semibold">{{ $peminjaman->status }}</span></p>
            </div>
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="bg-gray-700 text-white px-4 py-2 rounded-md text-xs font-semibold">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection