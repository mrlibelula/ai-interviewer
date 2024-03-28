@props(['color' => 'green'])
@php
    $tw_colors = [
        'red' => 'bg-red-500 dark:bg-red-500',
        'rose' => 'bg-rose-500 dark:bg-rose-500',
        'amber' => 'bg-amber-600 dark:bg-amber-400',
        'green' => 'bg-green-600 dark:bg-green-400',
        'blue' => 'bg-blue-400 dark:bg-blue-500',
    ];
    $tw_color = $tw_colors[$color] ?? $tw_colors['green'];
@endphp
<div class="{{ $tw_color }} w-2.5 h-2.5 rounded-full">
    &nbsp;
</div>