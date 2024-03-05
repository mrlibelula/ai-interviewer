@props(['nb'])
<div class="group flex flex-col gap-y-2 items-center justify-center p-6 bg-gradient-to-b from-orange-500/20 to-purple-500/20 hover:from-orange-500/25 hover:to-purple-500/25 rounded-xl smooth-300">
    <div class="text-5xl font-black font-mono group-hover:text-black group-hover:dark:text-purple-200 smooth-300">
        {{ $nb }}
    </div>
    <div class="text-2xl w-full text-center leading-tight group-hover:text-black group-hover:dark:text-gray-100 smooth-300">
        {{ $slot }}
    </div>
</div>