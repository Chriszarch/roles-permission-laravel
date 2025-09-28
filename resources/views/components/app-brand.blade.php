@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center justify-center' . $class]) }}>
    <img src="{{ asset('storage/horizontal-logo.webp') }}" alt="Logo" class="h-8 md:h-12 object-contain">
</div>
