@if (!$challenge)
<div class="flex flex-col gap-y-8">
    <x-heading class="-mb-8">
        <x-heading-content title="Congratulations!!" subtitle="You've finished all the challenges in this topic">
            <x-slot name="right">
                <a wire:navigate href="{{ route('interview') }}">
                    <x-button>
                        <div class=" whitespace-nowrap">
                            Start New Interview
                        </div>
                    </x-button>
                </a>
            </x-slot>
        </x-heading-content>
    </x-heading>
    <x-container>
        <x-not-found>It's over, see your results</x-not-found>
    </x-container>
</div>
@else
{{-- Livewire root stays outside wire:ignore so nested $wire / events still bind to Start --}}
<div>
{{--
  Entire IDE shell is wire:ignore so Start remorphs (or morphdom sibling bugs) can NEVER
  reorder titlebar / panels. Nested @livewire('chatbot') still updates on its own.
  Session XP / solved badge update via Alpine + session-stats-updated events.
--}}
<div
    wire:ignore
    x-data="createInterviewWorkspace({
        isChallengeSolved: {{ $is_challenge_solved ? 'true' : 'false' }},
        hasNextChallenge: {{ ($is_challenge_solved && count($challenge_ids) > 1) ? 'true' : 'false' }},
        stats: {
            total_user_bonus_xp: {{ (int) $total_user_bonus_xp }},
            total_user_extra_xp: {{ (int) $total_user_extra_xp }},
            solved_challenges_count: {{ (int) $solved_challenges_count }},
            total_challenges_count: {{ (int) $total_challenges_count }},
            attempts: {{ (int) $attempts }},
            total_bonus: {{ (int) $total_bonus }},
            total_user_bonus: {{ (int) $total_user_bonus }},
        },
    })"
    class="interview-workspace"
    :class="layout === 'ide' ? 'workspace-ide' : 'workspace-classic'"
    :style="panelStyle()"
>
    {{-- Classic reading heading --}}
    <div x-show="layout === 'classic'" x-cloak>
        <x-heading class="-mb-8">
            <x-heading-content right_vertical_position="center">
                <x-slot name="title">
                    <div class="text-left">
                        {{ $challenge->title ?? 'n/a' }}
                    </div>
                </x-slot>
                <x-slot name="subtitle">
                    <div class="flex flex-col xl:flex-row items-start xl:items-center gap-x-4 gap-y-2 pt-4">
                        <x-pill class=" uppercase font-semibold">
                            {{ $challenge->difficulty->name }}
                        </x-pill>
                        <div class=" font-mono w-fit xl:w-[88%] text-left text-base">
                            @foreach ($challenge->topics as $topic)
                            {{ $topic->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>
                </x-slot>
                <x-slot name="top">
                    <div class="w-fit pb-2 flex items-center gap-x-2">
                        @foreach ($challenge->languages as $language)
                        <div class=" text-emerald-500 dark:text-emerald-400 text-base font-semibold">
                            {{ $language->name }}
                        </div>
                        @endforeach
                    </div>
                </x-slot>
                <x-slot name="right">
                    <div class="flex items-center gap-x-4">
                        <x-workspace-layout-toggle />
                        <div x-show="isChallengeSolved" x-cloak class="group w-[2.5rem] h-[2.5rem] md:w-[3.5em] md:h-[3.5em]">
                            <x-icon-shield class=" w-full h-full group-hover:animate-spin-y" />
                        </div>
                    </div>
                </x-slot>
            </x-heading-content>
        </x-heading>
    </div>

    {{-- IDE one-liner title bar --}}
    <div
        x-show="layout === 'ide'"
        x-cloak
        class="ide-titlebar flex items-center justify-between gap-x-3 px-3 sm:px-4 py-1.5 border-b border-gray-200/70 dark:border-gray-700/60 bg-white/50 dark:bg-gray-900/40 backdrop-blur-sm"
    >
        <div class="min-w-0 flex items-center gap-x-2 sm:gap-x-3 leading-none">
            <div class="hidden sm:flex items-center gap-x-2 shrink-0">
                @foreach ($challenge->languages as $language)
                <span class="text-emerald-500 dark:text-emerald-400 font-semibold leading-none">{{ $language->name }}</span>
                @endforeach
            </div>
            <span class="min-w-0 truncate font-semibold text-gray-900 dark:text-gray-100 leading-none">
                {{ $challenge->title ?? 'n/a' }}
            </span>
        </div>
        <div class="flex items-center gap-x-2 sm:gap-x-3 shrink-0">
            <span class="inline-flex items-center h-7 text-[0.7rem] uppercase font-semibold tracking-wide text-gray-500 dark:text-gray-400 leading-none">
                {{ $challenge->difficulty->name }}
            </span>
            <span class="hidden sm:inline-flex items-center h-7 text-gray-400 dark:text-gray-500 opacity-50 leading-none" aria-hidden="true">·</span>
            <x-pill class="hidden sm:inline-flex items-center h-7 max-w-[14rem] truncate font-mono !text-sm leading-none !px-2.5 !py-0 text-emerald-500 dark:text-emerald-400">
                @foreach ($challenge->topics as $topic)
                {{ $topic->name }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </x-pill>
            <x-workspace-layout-toggle />
            <div x-show="isChallengeSolved" x-cloak class="group w-7 h-7 flex items-center justify-center">
                <x-icon-shield class="w-full h-full group-hover:animate-spin-y" />
            </div>
        </div>
    </div>

    {{-- Mobile IDE tabs --}}
    <div
        x-show="layout === 'ide'"
        x-cloak
        class="ide-mobile-tabs md:hidden items-stretch border-b border-gray-200/70 dark:border-gray-700/60 bg-gray-100/70 dark:bg-gray-800/50"
    >
        <button type="button" @click="mobileTab = 'problem'" class="flex-1 py-2 text-xs font-semibold tracking-wide uppercase smooth-300"
            :class="mobileTab === 'problem' ? 'text-emerald-600 dark:text-emerald-400 border-b-2 border-emerald-500' : 'text-gray-500 dark:text-gray-400'">Problem</button>
        <button type="button" @click="mobileTab = 'code'" class="flex-1 py-2 text-xs font-semibold tracking-wide uppercase smooth-300"
            :class="mobileTab === 'code' ? 'text-emerald-600 dark:text-emerald-400 border-b-2 border-emerald-500' : 'text-gray-500 dark:text-gray-400'">Code</button>
        <button type="button" @click="mobileTab = 'chat'" class="flex-1 py-2 text-xs font-semibold tracking-wide uppercase smooth-300"
            :class="mobileTab === 'chat' ? 'text-emerald-600 dark:text-emerald-400 border-b-2 border-emerald-500' : 'text-gray-500 dark:text-gray-400'">Chat</button>
    </div>

    <div
        class="workspace-body relative"
        :class="layout === 'classic' ? 'workspace-body-classic' : ''"
        x-ref="workspaceBody"
    >
        {{-- Problem --}}
        <section
            class="panel-problem ide-panel"
            :class="mobileTab === 'problem' ? 'is-mobile-active' : ''"
        >
            <div class="ide-panel-header" x-show="layout === 'ide'" x-cloak>
                <span>Challenge</span>
            </div>
            <div class="panel-problem-body panel-problem-scroll flex flex-col gap-y-4">
                @livewire('challenge-card', [
                    'challenge' => $challenge,
                    'header' => false,
                    'tags' => true,
                    'title' => false,
                    'footer' => false,
                    'creators' => false,
                ], key('challenge-card-'.$challenge->id))
            </div>
        </section>

        {{-- Editor (single instance for both layouts) --}}
        <section
            class="panel-editor-col ide-panel relative"
            :class="mobileTab === 'code' ? 'is-mobile-active' : ''"
        >
            <div class="ide-panel-header" x-show="layout === 'ide'" x-cloak>
                <span>Solution</span>
                <span class="normal-case tracking-normal font-mono text-[10px] opacity-60">Monaco</span>
            </div>

            <div
                x-show="layout === 'classic'"
                x-cloak
                class="text-left font-semibold dark:text-gray-300 cursor-pointer select-none mb-2"
                @click="$refs.classicEditorBody.classList.toggle('hidden')"
            >
                <span class="inline-flex items-center gap-x-2">
                    Try a solution
                    <i class="caret down icon text-gray-400 dark:text-gray-500/90 text-base"></i>
                </span>
            </div>

            <div
                class="editor-mount min-h-0 flex flex-1 flex-col relative overflow-hidden"
                x-ref="classicEditorBody"
            >
                <x-code-editor solverCode="{{ $challenge_attributes['solution_code'] ?? '' }}" />
                <div
                    x-show="layout === 'ide'"
                    x-cloak
                    class="ide-splitter ide-splitter-h absolute left-0 right-0 z-30"
                    :style="'bottom: var(--ide-bottom, 22%)'"
                    @pointerdown="document.body.classList.add('ide-dragging-row'); startDrag('bottom', $event)"
                    @dblclick="resetPanels()"
                    title="Drag to resize terminal"
                ></div>
            </div>
        </section>

        {{-- Meta: timer, XP, chat --}}
        <section
            class="panel-meta ide-panel"
            :class="mobileTab === 'chat' ? 'is-mobile-active' : ''"
        >
            <div class="ide-panel-header" x-show="layout === 'ide'" x-cloak>
                <span>Session</span>
            </div>
            <div class="panel-meta-body panel-meta-scroll flex flex-col items-center gap-y-10">

                <div class="session-xp grid grid-cols-2 items-center gap-1 justify-between w-full text-gray-950 dark:text-gray-400 bg-gray-200 dark:bg-gray-800 p-1 rounded-lg shadow shrink-0">
                    <div class="session-timer-row col-span-2">
                        <x-countdown-timer time_limit="{{ $challenge->time_limit }}" class="w-full" />
                    </div>
                    <x-pill-xp label="Bonus XP">+<span x-text="stats.total_user_bonus_xp">{{ $total_user_bonus_xp }}</span></x-pill-xp>
                    <x-pill-xp label="Extra Bonus">+<span x-text="stats.total_user_extra_xp">{{ $total_user_extra_xp }}</span></x-pill-xp>
                    <x-pill-xp label="Solved">
                        <div class="flex items-center gap-x-2 justify-between w-full">
                            <div x-show="isChallengeSolved" x-cloak class="w-5 h-5">
                                <x-icon-star class="w-full h-full text-amber-300" fill="currentColor" />
                            </div>
                            <span>
                                <span x-text="stats.solved_challenges_count">{{ $solved_challenges_count }}</span>/<span x-text="stats.total_challenges_count">{{ $total_challenges_count }}</span>
                            </span>
                        </div>
                    </x-pill-xp>
                    <x-pill-xp label="Attempts"><span x-text="stats.attempts">{{ $attempts }}</span></x-pill-xp>
                    <x-pill-xp class=" col-span-2" label="Total XP gained in this challenge">+<span x-text="stats.total_bonus">{{ $total_bonus }}</span></x-pill-xp>
                    <x-pill-xp class=" col-span-2 dark:bg-gray-900/50" label="Overall Total XP">+<span x-text="stats.total_user_bonus">{{ $total_user_bonus }}</span></x-pill-xp>
                </div>

                <div class="chatbot-panel w-full flex flex-col min-h-0 flex-1">
                    @livewire('chatbot', [
                        'challenge' => $challenge,
                        'chat_welcome' => $chat_welcome,
                        'openai_chat_settings' => $openai_chat_settings,
                    ], key('chatbot-'.$challenge->id))
                </div>

                <div
                    x-show="isChallengeSolved && hasNextChallenge"
                    x-cloak
                    class="flex items-center gap-x-3 justify-between shrink-0"
                    :class="layout === 'ide' ? '' : 'mt-16'"
                >
                    <x-secondary-button wire:click="nextChallenge">
                        Next challenge
                    </x-secondary-button>
                </div>
            </div>
        </section>

        {{-- IDE column splitters overlaid on the 3-column grid --}}
        <div
            x-show="layout === 'ide'"
            x-cloak
            class="ide-splitter ide-splitter-v hidden md:block"
            :style="'left: var(--ide-left, 26%)'"
            @pointerdown="startDrag('left', $event)"
            @dblclick="resetPanels()"
            title="Drag to resize · double-click to reset"
        ></div>
        <div
            x-show="layout === 'ide'"
            x-cloak
            class="ide-splitter ide-splitter-v hidden md:block"
            :style="'right: var(--ide-right, 26%)'"
            @pointerdown="startDrag('right', $event)"
            @dblclick="resetPanels()"
            title="Drag to resize · double-click to reset"
        ></div>
    </div>
</div>
</div>
@endif
