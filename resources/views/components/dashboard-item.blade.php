@props(['title' => '', 'info' => null, 'fixedHeight' => true])
<div {{ $attributes->merge(['class' => 'flex flex-col gap-y-8 p-6 md:p-8 rounded-xl h-fit bg-gray-100 dark:bg-gray-800 shadow-md', 'x-data' => '{ isOpen: true }']) }}>
    <div @click="isOpen = !isOpen" class="flex items-center justify-between cursor-pointer">
        <div class=" font-semibold text-gray-600 dark:text-gray-300 lg:text-2xl">
            {{ $title }}
        </div>
        <div class="flex gap-x-4">
            @if ($info)
            <x-icon-info class="w-6 h-6 text-gray-400 dark:text-gray-500" />
            @endif
            <button x-cloak x-show="isOpen">
                <i class="caret down icon"></i>
            </button>
            <button x-cloak x-show="!isOpen">
                <i class="caret right icon"></i>
            </button>
        </div>
    </div>
    <div x-cloak x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-75" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-75" class=" w-full {{ $fixedHeight ? 'max-h-[12rem]' : 'h-full' }} overflow-hidden overflow-y-auto">

        {{ $slot }}

    </div>
</div>