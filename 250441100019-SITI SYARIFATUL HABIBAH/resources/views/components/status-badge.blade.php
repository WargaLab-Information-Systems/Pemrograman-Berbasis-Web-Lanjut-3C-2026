@props(['status'])

@php
    $kelas = match ($status) {
        'diajukan'     => 'bg-yellow-100 text-yellow-800',
        'dipinjam'     => 'bg-blue-100 text-blue-800',
        'dikembalikan' => 'bg-green-100 text-green-800',
        'ditolak'      => 'bg-red-100 text-red-800',
        default        => 'bg-gray-100 text-gray-800',
    };
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $kelas }}">{{ ucfirst($status) }}</span>
