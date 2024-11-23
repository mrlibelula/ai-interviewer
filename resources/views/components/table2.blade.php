@props(['header', 'color' => 'gray'])
@php
    $header_bg_colors = [
        'gray' => 'shadow-gray-900/15 dark:shadow-gray-700/10 border-gray-200 dark:border-gray-700/40',
        'black' => 'shadow-black/20 dark:shadow-black/40 border-black/15 dark:border-black/40',
        'orange' => 'shadow-orange-900/15 dark:shadow-orange-700/10 border-orange-200 dark:border-orange-700/40',
        'yellow' => 'shadow-yellow-900/15 dark:shadow-yellow-700/10 border-yellow-200 dark:border-yellow-700/40',
        'rose' => 'shadow-rose-900/15 dark:shadow-rose-700/10 border-rose-200 dark:border-rose-700/40',
        'sky' => 'shadow-sky-900/15 dark:shadow-sky-700/10 border-sky-200 dark:border-sky-700/40',
        'green' => 'shadow-green-900/15 dark:shadow-green-700/10 border-green-200 dark:border-green-700/40',
        'emerald' => 'shadow-emerald-900/15 dark:shadow-emerald-700/10 border-emerald-200 dark:border-emerald-700/40',
        'blue' => 'shadow-blue-900/15 dark:shadow-blue-700/10 border-blue-200 dark:border-blue-700/40',
        'indigo' => 'shadow-indigo-900/15 dark:shadow-indigo-700/10 border-indigo-200 dark:border-indigo-700/40',
        'purple' => 'shadow-purple-900/15 dark:shadow-purple-700/10 border-purple-200 dark:border-purple-700/40',
        'pink' => 'shadow-pink-900/15 dark:shadow-pink-700/10 border-pink-200 dark:border-pink-700/40',
        'amber' => 'shadow-amber-900/15 dark:shadow-amber-700/10 border-amber-200 dark:border-amber-700/40',
        'lime' => 'shadow-lime-900/15 dark:shadow-lime-700/10 border-lime-200 dark:border-lime-700/40',
        'cyan' => 'shadow-cyan-900/15 dark:shadow-cyan-700/10 border-cyan-200 dark:border-cyan-700/40',
        'violet' => 'shadow-violet-900/15 dark:shadow-violet-700/10 border-violet-200 dark:border-violet-700/40',
        'fuchsia' => 'shadow-fuchsia-900/15 dark:shadow-fuchsia-700/10 border-fuchsia-200 dark:border-fuchsia-700/40',
        'dark-blue' => 'shadow-dark-blue-900/15 dark:shadow-dark-blue-700/10 border-dark-blue-300 dark:border-dark-blue-700/40',
    ];
    $header_colors = [
        'gray' => 'bg-gray-300 dark:bg-gray-700/40 text-gray-600 dark:text-gray-400',
        'black' => 'bg-black/20 dark:bg-black/40 text-black dark:text-gray-500',
        'orange' => 'bg-orange-300 dark:bg-orange-700/40 text-gray-600 dark:text-gray-400',
        'yellow' => 'bg-yellow-300 dark:bg-yellow-700/40 text-gray-600 dark:text-gray-400',
        'rose' => 'bg-rose-300 dark:bg-rose-700/40 text-gray-600 dark:text-gray-400',
        'sky' => 'bg-sky-300 dark:bg-sky-700/40 text-gray-600 dark:text-gray-400',
        'green' => 'bg-green-300 dark:bg-green-700/40 text-gray-600 dark:text-gray-400',
        'emerald' => 'bg-emerald-300 dark:bg-emerald-700/40 text-gray-600 dark:text-gray-400',
        'dark-blue' => 'bg-dark-blue-300 dark:bg-dark-blue-700/40 text-gray-600 dark:text-gray-400',
        'blue' => 'bg-blue-300 dark:bg-blue-700/40 text-gray-600 dark:text-gray-400',
        'indigo' => 'bg-indigo-300 dark:bg-indigo-700/40 text-gray-600 dark:text-gray-400',
        'purple' => 'bg-purple-300 dark:bg-purple-700/40 text-gray-600 dark:text-gray-400',
        'pink' => 'bg-pink-300 dark:bg-pink-700/40 text-gray-600 dark:text-gray-400',
        'red' => 'bg-red-300 dark:bg-red-700/40 text-gray-600 dark:text-gray-400',
        'amber' => 'bg-amber-300 dark:bg-amber-700/40 text-gray-600 dark:text-gray-400',
        'lime' => 'bg-lime-300 dark:bg-lime-700/40 text-gray-600 dark:text-gray-400',
        'cyan' => 'bg-cyan-300 dark:bg-cyan-700/40 text-gray-600 dark:text-gray-400',
        'violet' => 'bg-violet-300 dark:bg-violet-700/40 text-gray-600 dark:text-gray-400',
        'fuchsia' => 'bg-fuchsia-300 dark:bg-fuchsia-700/40 text-gray-600 dark:text-gray-400',
        'dark-blue' => 'bg-dark-blue-300 dark:bg-dark-blue-700/40 text-gray-600 dark:text-gray-400',
    ];

    $table_bg_colors = [
        'gray' => 'bg-gray-300/30 dark:bg-gray-800/20',
        'black' => 'bg-black/5 dark:bg-black/20',
        'yellow' => 'bg-yellow-300/30 dark:bg-yellow-800/20',
        'orange' => 'bg-orange-300/30 dark:bg-orange-800/20',
        'rose' => 'bg-rose-300/30 dark:bg-rose-800/20',
        'sky' => 'bg-sky-300/30 dark:bg-sky-800/20',
        'green' => 'bg-green-300/30 dark:bg-green-800/20',
        'emerald' => 'bg-emerald-300/30 dark:bg-emerald-800/20',
        'dark-blue' => 'bg-dark-blue-300/30 dark:bg-dark-blue-800/20',
        'blue' => 'bg-blue-300/30 dark:bg-blue-800/20',
        'indigo' => 'bg-indigo-300/30 dark:bg-indigo-800/20',
        'purple' => 'bg-purple-300/30 dark:bg-purple-800/20',
        'pink' => 'bg-pink-300/30 dark:bg-pink-800/20',
        'amber' => 'bg-amber-300/30 dark:bg-amber-800/20',
        'lime' => 'bg-lime-300/30 dark:bg-lime-800/20',
        'cyan' => 'bg-cyan-300/30 dark:bg-cyan-800/20',
        'violet' => 'bg-violet-300/30 dark:bg-violet-800/20',
        'red' => 'bg-red-300/30 dark:bg-red-800/20',
        'fuchsia' => 'bg-fuchsia-300/30 dark:bg-fuchsia-800/20',
        'dark-blue' => 'bg-dark-blue-300/30 dark:bg-dark-blue-800/20',
    ];

    $header_color = $header_colors[$color] ?? $header_colors['gray'];
    $bg_color = $header_bg_colors[$color] ?? $header_bg_colors['gray'];
    $table_bg_color = $table_bg_colors[$color] ?? $table_bg_colors['gray'];

@endphp
<div class="w-full overflow-hidden overflow-x-auto shadow-md_ rounded-[0.63rem] border-2 {{ $bg_color }}">
    <table class="w-full text-sm">
        <thead>
            <tr class=" text-xs {{ $header_color }} leading-none whitespace-nowrap">
                {{ $header }}
            </tr>
        </thead>
        <tbody class="text-center {{ $table_bg_color }}">
            {{ $slot }}
        </tbody>
    </table>
</div>