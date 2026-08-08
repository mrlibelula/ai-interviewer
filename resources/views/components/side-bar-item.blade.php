@props(['link' => null, 'external' => false, 'tooltip' => null])
<a
    {{ !$external ? 'wire:navigate' : '' }}
    href="{{ $link ?? '' }}"
    {{ $attributes->merge(['class' => 'sidebar-tip group relative flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-gray-300 dark:hover:bg-gray-700 smooth-300']) }}
    @if ($tooltip)
        aria-label="{{ $tooltip }}"
    @endif
>
    {{ $slot }}
    @if ($tooltip)
        <span class="sidebar-tooltip" role="tooltip">{{ $tooltip }}</span>
    @endif
</a>
