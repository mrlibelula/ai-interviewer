@props(['active' => false, 'href' => '#'])
<a wire:navigate href="{{ $href }}" class="overflow-hidden cursor-pointer p-0.5 {{ $active ? 'bg-gradient-to-bl from-purple-500 via-orange-400 to-purple-500' : 'bg-transaprent' }} w-full lg:w-fit rounded-lg hover:text-gray-900 dark:hover:text-gray-300">
    <div {{ $attributes->merge(['class' => 'w-full leading-tight text-center hover:bg-gray-100 dark:hover:bg-gray-900 smooth-300 rounded-lg py-2 px-4 font-semibold ' . ($active ? 'text-black dark:text-gray-200 bg-gray-100 dark:bg-gray-700' : 'bg-gray-200 dark:bg-gray-800') . ' ']) }}>
        {{ $slot }}
    </div>
</a>