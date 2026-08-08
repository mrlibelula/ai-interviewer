<div {{ $attributes->merge(['class' => 'editor-nav flex items-center justify-between gap-x-2 sm:gap-x-4 py-2']) }}>
    <button
        type="button"
        @click="$dispatch('get-code')"
        class="editor-nav-save p-1 cursor-pointer group w-fit flex items-center gap-x-1 shrink-0"
        title="Save code"
    >
        <div class="-mt-0.5 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300">
            <i class="cloud upload icon"></i>
        </div>
        <div class="text-sm sm:text-base group-hover:dark:text-emerald-400 smooth-300 leading-none whitespace-nowrap">
            <span class="sm:hidden">Save</span>
            <span class="hidden sm:inline">Save code</span>
        </div>
    </button>

    <div class="editor-nav-actions flex items-center gap-1.5 sm:gap-x-2 min-w-0 overflow-x-auto">
        <x-editor-nav-button
            class="editor-nav-btn shrink-0 text-center whitespace-nowrap"
            @click="$dispatch('run-code')"
            title="Run code"
        >
            <span class="sm:hidden">Run</span>
            <span class="hidden sm:inline">Run code</span>
        </x-editor-nav-button>
        <x-editor-nav-button
            class="editor-nav-btn shrink-0 text-center whitespace-nowrap"
            @click="$dispatch('run-and-analyze'); $dispatch('chatbot-loader-on');"
            title="Run and A.I. analyze"
        >
            <span class="sm:hidden">A.I.</span>
            <span class="hidden sm:inline">Run and A.I. analyze</span>
        </x-editor-nav-button>
        <x-editor-nav-button
            @click="$dispatch('complexity'); $dispatch('chatbot-loader-on');"
            class="editor-nav-btn shrink-0 text-center whitespace-nowrap"
            title="Analyze T/S Complexity"
        >
            <span class="sm:hidden">T/S</span>
            <span class="hidden sm:inline">Analyze T/S Complexity</span>
        </x-editor-nav-button>
    </div>

    <button
        type="button"
        class="fullscreen-icon p-1 cursor-pointer group w-fit flex items-center gap-x-2 shrink-0"
        title="Fullscreen"
    >
        <span class="hidden md:block text-base group-hover:dark:text-emerald-400 smooth-300 leading-none">
            Fullscreen
        </span>
        <x-icon-fullscreen class="w-6 h-6 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300" />
    </button>
</div>
