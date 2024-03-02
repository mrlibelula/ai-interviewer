<div 
        x-cloak 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        {{ $attributes->merge(['class' => 'z-40 absolute top-[4.5rem] right-4 bg-gray-200 dark:bg-gray-700 w-fit rounded-lg shadow-md overflow-hidden']) }}
    >
        {{ $slot }}
</div>