<x-app-layout>
    <div class="container bagian-buku" style="padding: 20px;">
        <h2 class="judul" style="font-size: 1.8rem; margin-bottom: 20px; font-weight: 900; color: #0f172a;">
            Detail Transaksi Peminjaman
        </h2>

        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); width: 100%;">
            <div style="display: flex; flex-direction: column; gap: 12px; font-size: 1rem; color: #334155;">
                <p><strong>ID Transaksi:</strong> {{ $peminjaman->id }}</p>
                <p><strong>Nama Peminjam:</strong> {{ $peminjaman->user->name ?? 'User ID: '.$peminjaman->anggota_id }}</p>
                <p><strong>Judul Buku:</strong> {{ $peminjaman->buku->judul ?? '-' }}</p>
                <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
                <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tanggal_kembali }}</p>
                <p><strong>Status:</strong> <span style="text-transform: capitalize; font-weight: 700; color: #2563eb;">{{ $peminjaman->status }}</span></p>
            </div>
        </div>
    </div>
</x-app-layout>