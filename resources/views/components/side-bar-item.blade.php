@props(['link' => null, 'external' => false])
<a {{ !$external ? 'wire:navigate' : '' }} href="{{ $link ?? '' }}"  {{ $attributes->merge(['class' => 'flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700']) }}>
    {{ $slot }}
</a>