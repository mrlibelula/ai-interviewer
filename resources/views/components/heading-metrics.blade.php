@props(['right' => null])
<x-heading-content right_vertical_position="center">
    <x-slot name="title">
        <div class="flex justify-center xl:justify-start items-center gap-x-4">
            <x-icon-progress class="w-8 h-8 text-emerald-500 dark:text-emerald-400" />
            Metrics
        </div>
    </x-slot>
    <x-slot name="subtitle">
        {{ $slot }}
    </x-slot>
    @if ($right)
    <x-slot name="right">
        {{ $right }}
    </x-slot>
    @endif
</x-heading-content>