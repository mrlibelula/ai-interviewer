@props(['active' => false, 'href' => '#'])
<a wire:navigate href="{{ $href }}" class="group overflow-hidden cursor-pointer p-0.5 {{ $active ? 'bg-gradient-to-bl from-white dark:from-black via-emerald-500 dark:via-emerald-400 to-white dark:to-black' : 'bg-transaprent' }} w-full rounded-lg hover:text-gray-900 dark:hover:text-gray-300">
    <div {{ $attributes->merge(['class' => 'w-full leading-tight text-center group-hover:bg-gray-400/80 dark:group-hover:bg-gray-700/90 smooth-300 rounded-lg py-2 px-3 font-semibold ' . ($active ? 'text-black dark:text-gray-200 bg-white dark:bg-gray-900 group-hover:text-white smooth-300' : 'bg-gray-300/80 dark:bg-black/40') . ' ']) }}>
        {{ $slot }}
    </div>
</a>