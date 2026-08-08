{{-- Reader (classic) vs IDE workspace toggle — matches existing gray/emerald chrome --}}
@props(['compact' => false])

<div
    {{ $attributes->merge(['class' => 'inline-flex items-center']) }}
    x-data="{
        layout: (localStorage.getItem('interviewWorkspaceLayout') === 'classic') ? 'classic' : 'ide',
        setLayout(mode) {
            this.layout = mode === 'ide' ? 'ide' : 'classic'
            window.InterviewWorkspace?.set(this.layout)
        }
    }"
    @workspace-layout-changed.window="layout = $event.detail.layout"
    title="Switch workspace layout"
>
    <div class="inline-flex items-center rounded-lg p-0.5 bg-gray-200/80 dark:bg-gray-800 border border-gray-300/70 dark:border-gray-700/80 shadow-sm">
        <button
            type="button"
            @click="setLayout('classic')"
            class="flex items-center gap-x-1.5 rounded-md px-2.5 py-1 text-xs font-semibold tracking-wide smooth-300"
            :class="layout === 'classic'
                ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow'
                : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
        >
            <x-icon-list class="w-4 h-4" />
            @unless ($compact)
            <span>Reader</span>
            @endunless
        </button>
        <button
            type="button"
            @click="setLayout('ide')"
            class="flex items-center gap-x-1.5 rounded-md px-2.5 py-1 text-xs font-semibold tracking-wide smooth-300"
            :class="layout === 'ide'
                ? 'bg-white dark:bg-gray-700 text-emerald-600 dark:text-emerald-400 shadow'
                : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
        >
            <x-icon-squares class="w-4 h-4" />
            @unless ($compact)
            <span>IDE</span>
            @endunless
        </button>
    </div>
</div>
