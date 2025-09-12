@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 ' . $class]) }}>
    <img src="{{ asset('storage/horizontal-logo.png') }}" alt="Logo" class="w-12 md:w-40 h-8 md:h-24">
</div>
