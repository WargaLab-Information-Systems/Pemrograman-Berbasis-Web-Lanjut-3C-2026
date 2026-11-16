<x-app-layout>
<div class="container bagian-buku" style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="judul" style="font-size: 1.8rem; margin: 0; font-weight: 900;">Laporan Peminjaman</h2>
        
        <button onclick="window.print()" class="no-print" style="background-color: #0f172a; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Laporan
        </button>
    </div>

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

<style>
@media print {

    .no-print, nav, header, footer {
        display: none !important;
    }
    
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }

    .container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
    }

    tr {
        page-break-inside: avoid;
    }
}
</style>
</x-app-layout>