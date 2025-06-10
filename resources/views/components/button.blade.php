@props([
    'type' => 'primary'
])

@php
    $base = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2';
    $styles = [
        'primary' => 'bg-primary text-white hover:bg-gray-800 focus:ring-primary',
        'secondary' => 'bg-secondary text-white hover:bg-blue-600 focus:ring-secondary',
        'danger' => 'bg-danger text-white hover:bg-red-600 focus:ring-danger',
        'outline' => 'bg-white text-primary border-primary hover:bg-gray-50 focus:ring-primary',
    ];
@endphp

<button {{ $attributes->merge(['class' => "$base {$styles[$type]}"]) }}>
    {{ $slot }}
</button>
