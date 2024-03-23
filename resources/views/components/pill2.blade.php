@props(['label' => '', 'value' => ''])
<div class=" flex items-center gap-x-2 whitespace-nowrap text-black dark:text-gray-200">
    <x-pill {{ $attributes->merge(['class' => 'flex items-center gap-x-2 bg-white dark:bg-gray-900 leading-none py-1 rounded-md py-2']) }}>
        {{ $label }}
        <div class=" text-lg text-green-700 dark:text-green-400 tracking-tight leading-none">
            {{ $value }}
        </div>
    </x-pill>
</div>