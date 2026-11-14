<x-app-layout>
<div class="container bagian-buku" style="padding: 20px;">
    <h2 class="judul" style="font-size: 1.8rem; margin-bottom: 20px; font-weight: 900;">Halaman Peminjaman & Pengembalian</h2>

    <div id="alertSuccess" class="alert-notif alert-sukses" style="display:none; margin-bottom: 15px;"></div>

    <div class="tabel-buku-wrapper">
        <table class="table-detail" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #0f172a; color: white;">
                    <th style="padding: 10px; color: #ffffff !important;">No</th>
                    <th style="padding: 10px; color: #ffffff !important;">Peminjam</th>
                    <th style="padding: 10px; color: #ffffff !important;">Judul Buku</th>
                    <th style="padding: 10px; color: #ffffff !important;">Tgl Pinjam</th>
                    <th style="padding: 10px; color: #ffffff !important;">Tgl Jatuh Tempo</th>
                    <th style="padding: 10px; color: #ffffff !important;">Status</th>
                    <th style="padding: 10px; color: #ffffff !important; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $item)
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
                    <td style="padding: 10px; text-align: left;">
                        @if($item->status == 'pending')
                            <button onclick="updateStatus({{ $item->id }}, 'dipinjam')" class="btn-primary" style="background-color: #2563eb; padding: 5px 12px; font-size: 0.85rem;">Setujui</button>
                        @elseif($item->status == 'dipinjam')
                            <button onclick="updateStatus({{ $item->id }}, 'dikembalikan')" class="btn-primary" style="background-color: #10b981; padding: 5px 12px; font-size: 0.85rem;">Kembalikan</button>
                        @else
                            <span style="color: #64748b; font-size: 0.85rem;">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center;">Belum ada transaksi peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
async function updateStatus(id, newStatus) {
    if (!confirm(`Apakah Anda yakin ingin mengubah status menjadi ${newStatus}?`)) return;

    try {
        const response = await fetch(`/peminjaman/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        });

        const result = await response.json();
        if (response.ok) {
            alert(result.message || 'Status berhasil diperbarui!');
            location.reload();
        } else {
            alert(result.message || 'Gagal mengubah status.');
        }
    } catch (error) {
        alert('Terjadi kesalahan jaringan.');
    }
}
</script>
</x-app-layout>