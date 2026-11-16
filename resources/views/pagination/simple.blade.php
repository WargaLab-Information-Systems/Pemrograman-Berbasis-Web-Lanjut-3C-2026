@if ($paginator->hasPages())
    <nav class="pager" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="btn btn-light btn-sm btn-disabled">Sebelumnya</span>
        @else
            <a class="btn btn-sm" href="{{ $paginator->previousPageUrl() }}">Sebelumnya</a>
        @endif

        <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a class="btn btn-sm" href="{{ $paginator->nextPageUrl() }}">Berikutnya</a>
        @else
            <span class="btn btn-light btn-sm btn-disabled">Berikutnya</span>
        @endif
    </nav>
@endif
