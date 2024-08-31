@props(['color' => 'gray', 'nowrap' => true])
@php
    $header_bg = [
        'gray' => 'bg-gray-400/50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300',
        'emerald' => 'bg-emerald-400/50 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300',
        'sky' => 'bg-sky-400/50 dark:bg-sky-900/50 text-sky-700 dark:text-sky-300',
        'orange' => 'bg-orange-400/50 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300',
        'blue' => 'bg-blue-400/50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
        'rose' => 'bg-rose-400/50 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300',
        'fuchsia' => 'bg-fuchsia-400/50 dark:bg-fuchsia-900/50 text-fuchsia-700 dark:text-fuchsia-300',
        'violet' => 'bg-violet-400/50 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300',
        'purple' => 'bg-purple-400/50 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300',
        'indigo' => 'bg-indigo-400/50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300',
        'yellow' => 'bg-yellow-400/50 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
        'green' => 'bg-green-400/50 dark:bg-green-900/50 text-green-700 dark:text-green-300',
        'red' => 'bg-red-400/50 dark:bg-red-900/50 text-red-700 dark:text-red-300',
        'pink' => 'bg-pink-400/50 dark:bg-pink-900/50 text-pink-700 dark:text-pink-300',
        'cyan' => 'bg-cyan-400/50 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-300',
        'lime' => 'bg-lime-400/50 dark:bg-lime-900/50 text-lime-700 dark:text-lime-300',
        'amber' => 'bg-amber-400/50 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300',
        'dark-blue' => 'bg-dark-blue-400/50 dark:bg-dark-blue-900/50 text-dark-blue-700 dark:text-dark-blue-300',
        'dark-blue-gray' => 'bg-dark-blue-400/50 dark:bg-dark-blue-900/50 text-dark-blue-700 dark:text-dark-blue-300',
    ];
    $header_b = $header_bg[$color] ?? $header_bg['gray'];
    
    $table_bg = [
        'gray' => 'bg-gray-200/40 dark:bg-gray-700/40',
        'emerald' => 'bg-emerald-200/40 dark:bg-emerald-900/40',
        'sky' => 'bg-sky-200/40 dark:bg-sky-900/40',
        'orange' => 'bg-orange-200/40 dark:bg-orange-900/40',
        'blue' => 'bg-blue-200/40 dark:bg-blue-900/40',
        'rose' => 'bg-rose-200/40 dark:bg-rose-900/40',
        'fuchsia' => 'bg-fuchsia-200/40 dark:bg-fuchsia-900/40',
        'violet' => 'bg-violet-200/40 dark:bg-violet-900/40',
        'purple' => 'bg-purple-200/40 dark:bg-purple-900/40',
        'indigo' => 'bg-indigo-200/40 dark:bg-indigo-900/40',
        'yellow' => 'bg-yellow-200/40 dark:bg-yellow-900/40',
        'green' => 'bg-green-200/40 dark:bg-green-900/40',
        'red' => 'bg-red-200/40 dark:bg-red-900/40',
        'pink' => 'bg-pink-200/40 dark:bg-pink-900/40',
        'cyan' => 'bg-cyan-200/40 dark:bg-cyan-900/40',
        'lime' => 'bg-lime-200/40 dark:bg-lime-900/40',
        'amber' => 'bg-amber-200/40 dark:bg-amber-900/40',
        'dark-blue' => 'bg-dark-blue-300/60 dark:bg-dark-blue-900/40',
        'dark-blue-gray' => 'bg-gray-200/40 dark:bg-gray-700/40',
    ];
    $table = $table_bg[$color] ?? $table_bg['gray'];
@endphp
<div class="overflow-hidden rounded-lg shadow overflow-x-auto w-full">
    <table {{ $attributes->merge(['class' => 'table-fixed ' . ($nowrap ? 'whitespace-nowrap ' : '') . $table]) }}>
        @isset($header)
        <thead>
            <tr class="text-sm {{ $header_b }} cursor-default">
                {{ $header }}
            </tr>
        </thead>
        @endisset
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>