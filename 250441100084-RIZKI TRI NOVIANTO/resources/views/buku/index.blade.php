@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Header Halaman -->
            <div class="page-heading"
                style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Data Buku</h2>
                    <p class="text-gray-600 text-sm mt-1">Total <span>{{ $bukus->count() }}</span> buku tersedia di
                        perpustakaan.</p>
                </div>

                {{-- Tombol Tambah Buku (Soal 3: Proteksi Permission can'kelola-buku') --}}
                @can('kelola-buku')
                    <a href="{{ route('buku.create') }}"
                        style="padding: 10px 18px; background-color: #0d6efd; color: #fff; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                        + Tambah Buku Baru
                    </a>
                @endcan
            </div>

            {{-- Kotak Pesan Sukses / Error dari Redirect Controller --}}
            @if (session('success'))
                <div
                    style="padding: 12px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    style="padding: 12px; border-radius: 4px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Kotak Pesan Sukses / Error untuk AJAX Ajukan Pinjam --}}
            <div id="alert-box" style="display: none; padding: 12px; border-radius: 4px;"></div>

            {{-- Tabel Daftar Buku --}}
            <div class="card-box"
                style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 16px; color: #2c3e50; font-size: 1.25rem; font-weight: 700;">Daftar Data Buku</h3>
                <div style="overflow-x: auto;">
                    <table class="api-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f8f9fa; text-align: left; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 10px;">Judul Buku</th>
                                <th style="padding: 10px;">Penulis</th>
                                <th style="padding: 10px;">Kategori</th>
                                <th style="padding: 10px;">Tahun</th>
                                <th style="padding: 10px;" width="38%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bukus as $buku)
                                @php
                                    $status = $buku->status ?? 'Tersedia';
                                    $sudahDiajukan = in_array($status, ['Pengajuan Pinjam', 'Pengajuan', 'Dipinjam']);
                                    $textPinjam = $sudahDiajukan
                                        ? ($status === 'Dipinjam'
                                            ? 'Dipinjam'
                                            : 'Menunggu')
                                        : 'Ajukan Pinjam';
                                @endphp
                                <tr style="border-bottom: 1px solid #eee;">
                                    {{-- Judul Buku dengan Blade Escaping --}}
                                    <td class="p-3 text-sm font-medium">{{ $buku->judul }}</td>

                                    <td style="padding: 10px;">{{ $buku->penulis }}</td>
                                    <td style="padding: 10px;">
                                        <span
                                            style="background-color: #f3f4f6; color: #4b5563; font-size: 12px; padding: 3px 8px; border-radius: 4px; border: 1px solid #e5e7eb;">
                                            {{ $buku->kategori->nama_kategori ?? ($buku->kategori->nama ?? '-') }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px;">{{ $buku->tahun_terbit }}</td>
                                    <td style="padding: 10px;">
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px; align-items: center;">
                                            {{-- Tombol Detail Buku --}}
                                            <a href="{{ route('buku.show', $buku->id) }}"
                                                style="padding: 6px 12px; font-size: 12px; background-color: #6366f1; color: #fff; border: none; border-radius: 4px; text-decoration: none; display: inline-block;">
                                                Detail Buku
                                            </a>

                                            {{-- Tombol Ajukan Pinjam --}}
                                            <button type="button" onclick="pinjamBuku({{ $buku->id }}, this)"
                                                {{ $sudahDiajukan ? 'disabled' : '' }}
                                                style="padding: 6px 12px; font-size: 12px; background-color: {{ $sudahDiajukan ? '#6c757d' : '#3b82f6' }}; color: #fff; border: none; border-radius: 4px; {{ $sudahDiajukan ? 'opacity: 0.6; cursor: not-allowed;' : 'cursor: pointer;' }}">
                                                {{ $textPinjam }}
                                            </button>

                                            {{-- Tombol Ubah & Hapus (Soal 3: Proteksi Permission 'kelola-buku') --}}
                                            @can('kelola-buku')
                                                <a href="{{ route('buku.edit', $buku->id) }}"
                                                    style="padding: 6px 12px; font-size: 12px; background-color: #eab308; color: #fff; border: none; border-radius: 4px; text-decoration: none; display: inline-block;">
                                                    Ubah
                                                </a>

                                                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 16px;">Belum ada data buku.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        function tampilkanPesan(pesan, tipe = 'success') {
            const alertBox = document.getElementById('alert-box');
            if (!alertBox) return;

            alertBox.style.display = 'block';
            if (tipe === 'success') {
                alertBox.style.backgroundColor = '#d4edda';
                alertBox.style.color = '#155724';
                alertBox.style.border = '1px solid #c3e6cb';
            } else {
                alertBox.style.backgroundColor = '#f8d7da';
                alertBox.style.color = '#721c24';
                alertBox.style.border = '1px solid #f5c6cb';
            }
            alertBox.innerHTML = pesan;
        }

        async function pinjamBuku(id, btnElement) {
            if (!confirm('Apakah Anda yakin ingin mengajukan peminjaman buku ini?')) return;

            try {
                const response = await fetch(`/buku/${id}/pinjam`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const result = await response.json();
                if (response.ok && result.success) {
                    tampilkanPesan(result.message, 'success');
                    if (btnElement) {
                        btnElement.disabled = true;
                        btnElement.innerText = 'Menunggu';
                        btnElement.style.backgroundColor = '#6c757d';
                        btnElement.style.opacity = '0.6';
                        btnElement.style.cursor = 'not-allowed';
                    }
                } else {
                    tampilkanPesan(result.message || 'Gagal mengajukan peminjaman.', 'error');
                }
            } catch (error) {
                tampilkanPesan('Terjadi kesalahan: ' + error.message, 'error');
            }
        }
    </script>
@endsection
