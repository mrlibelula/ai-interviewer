<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-x-4 py-2']) }}>
    <div @click="$dispatch('get-code')" class="p-1 cursor-pointer group w-fit flex items-start gap-x-1">
        <div class=" -mt-1.5 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300">
            {{-- <i class="save icon"></i> --}}
            <i class="cloud upload icon"></i>
        </div>
        <div class="text-base group-hover:dark:text-emerald-400 smooth-300 leading-none">
            Save code
        </div>
    </div>
    <div class="flex flex-col md:flex-row items-center gap-y-2 md:gap-y-0 md:gap-x-2">
        
        <x-editor-nav-button class="w-full text-center whitespace-nowrap" 
            @click="$dispatch('run-code'); $dispatch('chatbot-loader-on')"
        >
            Run and A.I. analyze
        </x-editor-nav-button>
        {{-- <x-editor-nav-button class="w-full text-center whitespace-nowrap" @click="$dispatch('analyze-code')">A.I. Analyzer</x-editor-nav-button> --}}
        <x-editor-nav-button 
            @click="$dispatch('chatbot-loader-on')"
            class="w-full text-center whitespace-nowrap"
        >
            Analyze T/S Complexity
        </x-editor-nav-button>
        
    </div>
    <div id="fullscreenIcon" class="p-1 cursor-pointer group w-fit flex items-center gap-x-2">
        <span class=" hidden md:block text-base group-hover:dark:text-emerald-400 smooth-300 leading-none">
            Fullscreen
        </span>
        <x-icon-fullscreen class="w-6 h-6 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300" />
    </div>
</div>