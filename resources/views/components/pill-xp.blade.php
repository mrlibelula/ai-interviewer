@props(['label' => '', 'bold_text' => 'overall total xp'])
@php
    $bold_text = 'overall total xp';
@endphp
<div {{ $attributes->merge(['class' => 'flex items-center gap-x-3 justify-between bg-white dark:bg-black/50 px-3 py-3 rounded-md w-full hover:bg-gray-100/60 hover:dark:bg-black smooth-300 group']) }}>
    <div class=" whitespace-nowrap group-hover:text-emerald-500 group-hover:dark:text-gray-300 smooth-300 {{ strtolower($label) === $bold_text ? 'font-semibold dark:text-gray-300' : '' }}">{{ $label }}</div>
    <div class=" text-emerald-500 dark:text-emerald-400 font-semibold">
        {{ $slot }}
    </div>
</div>