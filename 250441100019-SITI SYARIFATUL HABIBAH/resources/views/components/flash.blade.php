@if (session('sukses'))
    <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="alert">
        {{ session('sukses') }}
    </div>
@endif

@if (session('error'))
    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        {{ session('error') }}
    </div>
@endif
