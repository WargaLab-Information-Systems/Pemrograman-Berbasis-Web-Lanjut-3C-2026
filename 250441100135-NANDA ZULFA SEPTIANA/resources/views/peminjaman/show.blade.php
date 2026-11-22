<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 20px;">

        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; color: #374151; font-size: 14px;">ID Peminjaman</th>
                    <th style="padding: 12px; color: #374151; font-size: 14px;">Nama Peminjam</th>
                    <th style="padding: 12px; color: #374151; font-size: 14px;">ID Buku</th>
                    <th style="padding: 12px; color: #374151; font-size: 14px;">Tanggal Pinjam</th>
                    <th style="padding: 12px; color: #374151; font-size: 14px;">Status</th>
                </tr>
            </thead>
            <tbody>
                 <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; color: #1f2937; font-weight: bold;">
                            {{ $peminjaman->id }}
                        </td>
                        <td style="padding: 12px; color: #6b7280; font-size: 14px;">
                            {{ $peminjaman->user->name ?? '-' }}
                        </td>
                        <td style="padding: 12px; color: #6b7280; font-size: 14px;">
                             {{ $peminjaman->buku->judul }}
                        </td>
                        <td style="padding: 12px;">
                            {{ $peminjaman->tanggal_pinjam }}
                        </td>
                        <td style="padding: 12px;">
                            {{ $peminjaman->status }}
                        </td>
                    </tr>
            </tbody>
        </table>

    </div>

</body>
</html>