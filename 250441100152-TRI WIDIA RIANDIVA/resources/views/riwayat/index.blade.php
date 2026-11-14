<x-app-layout>
<div class="container bagian-buku" style="padding: 20px;">
    <h2 class="judul" style="font-size: 1.8rem; margin-bottom: 20px; font-weight: 900;">Riwayat Peminjaman Buku Saya</h2>

   <div class="tabel-buku-wrapper">
        <table class="table-detail" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #0f172a; color: white;">
                    <th style="padding: 10px; color: #ffffff !important;">No</th>
                    <th style="padding: 10px; color: #ffffff !important;">Judul Buku</th>
                    <th style="padding: 10px; color: #ffffff !important;">Tgl Pinjam</th>
                    <th style="padding: 10px; color: #ffffff !important;">Tgl Kembali</th>
                    <th style="padding: 10px; color: #ffffff !important;">Status</th>
                </tr>
            </thead>
            </thead>
            <tbody>
                @forelse($riwayats as $index => $item)
                <tr style="border-bottom: 1px solid #ddd; text-align: center;">
                    <td style="padding: 10px;">{{ $index + 1 }}</td>
                    <td style="padding: 10px; font-weight: 700; color: #0f172a;">{{ $item->buku->judul ?? '-' }}</td>
                    <td style="padding: 10px;">{{ $item->tanggal_pinjam }}</td>
                   <td style="padding: 10px;">{{ $item->tanggal_kembali ?? '-' }}</td>
                    <td style="padding: 10px;">
                        @if($item->status == 'pending')
                            <span style="padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background-color: #f59e0b; color: #fff;">Menunggu Persetujuan</span>
                        @elseif($item->status == 'dipinjam')
                            <span style="padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background-color: #3b82f6; color: #fff;">Disetujui / Dipinjam</span>
                        @else
                            <span style="padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 0.85rem; background-color: #10b981; color: #fff;">Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center;">Kamu belum pernah mengajukan peminjaman buku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>