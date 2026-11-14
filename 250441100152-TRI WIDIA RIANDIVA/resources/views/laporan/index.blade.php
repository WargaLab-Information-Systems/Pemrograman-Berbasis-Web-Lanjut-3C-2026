<x-app-layout>
<div class="container bagian-buku" style="padding: 20px;">
    <h2 class="judul" style="font-size: 1.8rem; margin-bottom: 20px; font-weight: 900;">Laporan Peminjaman</h2>

    <div style="display: flex; gap: 20px; margin-bottom: 25px;">
        <div style="flex: 1; background: #0f172a; color: white; padding: 20px; border-radius: 10px;">
            <h4 style="margin: 0; font-size: 0.9rem; color: #94a3b8;">TOTAL TRANSAKSI</h4>
            <p style="margin: 10px 0 0 0; font-size: 2rem; font-weight: 800;">{{ $totalTransaksi }}</p>
        </div>
        <div style="flex: 1; background: #10b981; color: white; padding: 20px; border-radius: 10px;">
            <h4 style="margin: 0; font-size: 0.9rem; color: #e2e8f0;">TRANSAKSI SELESAI</h4>
            <p style="margin: 10px 0 0 0; font-size: 2rem; font-weight: 800;">{{ $totalSelesai }}</p>
        </div>
    </div>

    <div class="tabel-buku-wrapper">
        <table class="table-detail" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #0f172a; color: white;">
                    <th style="padding: 10px; color: #ffffff !important; width: 50px;">No</th>
                    <th style="padding: 10px; color: #ffffff !important;">Peminjam</th>
                    <th style="padding: 10px; color: #ffffff !important;">Judul Buku</th>
                    <th style="padding: 10px; color: #ffffff !important;">Tgl Pinjam</th>
                    <th style="padding: 10px; color: #ffffff !important;">Tgl Kembali</th>
                    <th style="padding: 10px; color: #ffffff !important;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $item)
                <tr style="border-bottom: 1px solid #ddd; text-align: left;">
                    <td style="padding: 10px;">{{ $index + 1 }}</td>
                    <td style="padding: 10px;">{{ $item->user->name ?? '-' }}</td>
                    <td style="padding: 10px; font-weight: 700; color: #0f172a;">{{ $item->buku->judul ?? '-' }}</td>
                    <td style="padding: 10px;">{{ $item->tanggal_pinjam }}</td>
                    <td style="padding: 10px;">{{ $item->tanggal_kembali ?? '-' }}</td>
                    <td style="padding: 10px;">
                        @if($item->status == 'pending')
                            <span style="padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background-color: #f59e0b; color: #fff;">Pending</span>
                        @elseif($item->status == 'dipinjam')
                            <span style="padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background-color: #3b82f6; color: #fff;">Dipinjam</span>
                        @else
                            <span style="padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background-color: #10b981; color: #fff;">Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center;">Belum ada data laporan transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>