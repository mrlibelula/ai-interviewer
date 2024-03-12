@props(['nb' => 0])
<div class="group flex flex-col gap-y-2 items-center justify-center p-6 bg-gray-300/50 dark:bg-gray-700/50 hover:bg-pink-200/40 dark:hover:bg-pink-800/30 rounded-xl smooth-500 shadow-md cursor-pointer">
    <div class="text-6xl tracking-wide font-black font-mono group-hover:text-pink-700 dark:group-hover:text-pink-200 smooth-500">
        {{ $nb }}
    </div>
    <div class="text-xl font-semibold tracking-wider w-full text-center leading-tight group-hover:text-pink-800 group-hover:dark:text-pink-300 smooth-500">
        {{ $slot }}
    </div>
</div>