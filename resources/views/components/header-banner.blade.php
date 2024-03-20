<x-container class="md:mt-6">
    <x-heading :hasBg="true" class="w-full">
        <div class="flex flex-col md:flex-row items-center gap-y-4 md:gap-x-8 py-6">
            <div class="flex flex-col md:flex-row gap-y-4 md:gap-y-0 justify-center md:justify-start items-center gap-x-4 w-full">
                @isset($icon)
                {{ $icon }}
                @endisset
                <h2 class="text-2xl md:text-3xl sm:text-4xl font-semibold text-gray-800 dark:text-gray-300 leading-tight">
                    {{ $slot }}
                </h2>
            </div>
            @isset($right)
            <div class="flex flex-col lg:flex-row items-center gap-x-8 gap-y-4 lg:gap-y-0 justify-center">
                {{ $right }}
            </div>
            @endisset
        </div>
    </x-heading>
</x-container>