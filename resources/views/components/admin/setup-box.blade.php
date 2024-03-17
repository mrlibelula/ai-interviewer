@props(['title' => '', 'fixed' => false])
<div class="flex flex-col gap-y-0.5 bg-gray-300/50 dark:bg-gray-700/50 py-1 px-3.5 shadow rounded-md">
    <x-bold class="text-base py-1">{{ $title }}</x-bold>

    <div class="border-b border-dotted border-gray-700 dark:border-gray-400"></div>

    <!-- multiple choice component -->
    <div class="{{ $fixed ? 'h-[8.5rem]' : '' }} overflow-hidden overflow-y-auto py-2 px-1">
        
        {{ $slot }}
        
    </div>

    @isset($selected)
    <div class="border-b border-dotted border-gray-700 dark:border-gray-400 py-1"></div>
    
    <div class=" text-sm leading-tight py-2">
        
        {{ $selected }}
        
    </div>
    @endisset

</div>