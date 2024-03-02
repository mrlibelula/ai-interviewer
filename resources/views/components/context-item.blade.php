<a {{ $attributes->merge(['class' => 'group flex items-center gap-x-3 hover:bg-gray-300 hover:dark:bg-gray-500 text-gray-600 dark:text-gray-300 w-full px-4 py-3 font-semibold cursor-pointer smooth-300']) }}>
    @isset($icon)
    <div class="w-6 h-6 text-gray-500 dark:text-gray-400 group-hover:text-gray-900 group-hover:dark:text-gray-200 smooth-300">
        {{ $icon }}
    </div>
    @endisset
    <div class="group-hover:text-black group-hover:dark:text-gray-100 smooth-300">
        {{ $slot }}
    </div>
</a>