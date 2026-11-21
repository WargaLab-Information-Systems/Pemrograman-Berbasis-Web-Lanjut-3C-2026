@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 border-b-2 border-brand-light text-sm font-medium leading-5 text-white bg-brand focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-200 hover:text-white hover:bg-brand focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
