@props(['label' => '', 'value' => ''])
<div class=" flex items-center gap-x-2 whitespace-nowrap text-black dark:text-gray-200 w-full">
    <x-pill {{ $attributes->merge(['class' => 'flex items-center justify-center gap-x-2 bg-white dark:bg-gray-800 w-full leading-none py-1 rounded-md py-2']) }}>
        {{ $label }}
        <div class=" font-semibold text-emerald-700 dark:text-emerald-400 tracking-tight leading-none">
            {{ $value }}
        </div>
    </x-pill>
</div>