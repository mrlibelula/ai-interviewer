<div class="flex flex-col lg:flex-row items-center justify-center lg:gap-4 bg-gray-300/30 dark:bg-gray-800/70 rounded-lg overflow-hidden">
    <p class="w-full lg:w-[35%] p-2 lg:p-5 text-center lg:text-left leading-relaxed">
        {{ $title }}
    </p>
    
    <div class="p-2 lg:p-5 w-full lg:w-[65%]">
        {{ $slot }}
    </div>
</div>