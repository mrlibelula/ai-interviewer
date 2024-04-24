<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-x-4 py-2']) }}>
    <div @click="$dispatch('get-code')" class="p-1 cursor-pointer group w-fit flex items-start gap-x-1">
        <div class=" -mt-0.5 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300">
            <i class="save icon"></i>
            {{-- <i class="cloud upload icon"></i> --}}
        </div>
        <div class="text-base group-hover:dark:text-emerald-400 smooth-300">
            Save code
        </div>
    </div>
    <div class="flex items-center gap-x-2">
        <div @click="$dispatch('run-code')" class="text-base hover:dark:text-emerald-400 smooth-300 border rounded shadow hover:bg-gray-100 dark:hover:bg-transparent dark:border-gray-600 dark:hover:border-emerald-500 px-3 cursor-pointer">
            Run
        </div>
        <div @click="$dispatch('analyze-code')" class="text-base hover:dark:text-emerald-400 smooth-300 border rounded shadow hover:bg-gray-100 dark:hover:bg-transparent dark:border-gray-600 dark:hover:border-emerald-500 px-3 cursor-pointer">
            A.I. Analyzer
        </div>
    </div>
    <div id="fullscreenIcon" class="p-1 cursor-pointer group w-fit flex items-center gap-x-2">
        <span class="text-base group-hover:dark:text-emerald-400 smooth-300">Fullscreen</span>
        <x-icon-fullscreen class="w-6 h-6 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300" />
    </div>
</div>