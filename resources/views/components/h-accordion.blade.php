@props(['title' => 'n/a'])
<div {{ $attributes->merge(['class' => 'flex flex-col gap-y-4']) }}>
    <button @click="isOpen = !isOpen" class="flex items-center gap-x-2 cursor-pointer font-semibold dark:text-gray-300">
        <div>
            {{ $title }}
        </div>
        <div class=" text-base text-gray-400 dark:text-gray-500/90 mt-[0.2rem]">
            <i x-cloak x-show="!isOpen" class="caret right icon "></i>
            <i x-cloak x-show="isOpen" class="caret down icon "></i>
        </div>
    </button>
    <div x-cloak x-show="isOpen" class=" text-left py-2"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
    >
        {{ $slot }}
    </div>
</div>