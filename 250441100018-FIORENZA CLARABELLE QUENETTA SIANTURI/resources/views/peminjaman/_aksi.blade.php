
@if ($p->status === 'diajukan')
    @can('memproses persetujuan peminjaman')
        <form class="inline-form" method="POST" action="{{ route('peminjaman.setujui', $p) }}">
            @csrf
            <button type="submit" class="btn btn-success btn-sm">Setujui</button>
        </form>
        <form class="inline-form" method="POST" action="{{ route('peminjaman.tolak', $p) }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
        </form>
    @endcan
@elseif ($p->status === 'dipinjam')
    @can('mencatat pengembalian buku')
        <form class="inline-form" method="POST" action="{{ route('peminjaman.kembali', $p) }}">
            @csrf
            <button type="submit" class="btn btn-sm">Catat Pengembalian</button>
        </form>
    @endcan
@endif
