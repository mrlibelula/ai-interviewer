<div>
    @if ($importedChallenges->total() || filled($search))
    <x-descr-list>
        <div class="mt-2 mb-4 flex items-center justify-between gap-3 flex-wrap">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                A.I. imported challenges
            </p>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="w-full sm:w-56">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search challenges"
                        class="form-input dark:bg-gray-800 h-[2.25rem] w-full text-sm placeholder-gray-500 placeholder:text-sm"
                    />
                </div>
                <div class="w-auto shrink-0">
                    <x-per-page size="text-sm" />
                </div>
                <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold tracking-wide uppercase bg-gray-200/80 text-gray-700 dark:bg-gray-700/70 dark:text-gray-300">
                    {{ $importedChallenges->total() }} {{ $importedChallenges->total() === 1 ? 'challenge' : 'challenges' }}
                </span>
            </div>
        </div>

        @if ($importedChallenges->total())
        <div class="flex flex-col gap-y-3 w-full min-w-0">
            @foreach ($importedChallenges as $importedChallenge)
            @php
                $isExpanded = $expandedChallengeId === (int) $importedChallenge->id;
                $isNew = in_array((int) $importedChallenge->id, $newChallengeIds, true);
                $topicLabel = $importedChallenge->topics
                    ->pluck('name')
                    ->filter()
                    ->map(fn ($name) => ucfirst($name))
                    ->implode(', ');
                $difficultyName = $importedChallenge->difficulty?->name;
                $difficultyKey = strtolower((string) $difficultyName);
                $difficultyClasses = match ($difficultyKey) {
                    'easy' => 'bg-amber-100 text-amber-800 dark:bg-amber-800/40 dark:text-amber-300',
                    'medium' => 'bg-sky-100 text-sky-800 dark:bg-sky-800/40 dark:text-sky-300',
                    'hard' => 'bg-rose-100 text-rose-800 dark:bg-rose-800/40 dark:text-rose-300',
                    default => 'bg-gray-200/80 text-gray-700 dark:bg-gray-700/70 dark:text-gray-300',
                };
            @endphp
            <div class="rounded-lg border border-gray-200/80 dark:border-gray-700/60 bg-white dark:bg-gray-800/40 px-3 sm:px-5 py-3 sm:py-4 overflow-hidden min-w-0" wire:key="imported-challenge-row-{{ $importedChallenge->id }}">
                <div class="flex flex-col gap-y-4 min-w-0">
                    <button
                        type="button"
                        wire:click="toggleExpanded({{ $importedChallenge->id }})"
                        class="flex items-start sm:items-center gap-x-2 w-full min-w-0 cursor-pointer font-semibold dark:text-gray-300 text-left"
                    >
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 min-w-0 text-left">
                                <div class="flex items-start sm:items-center gap-2 sm:gap-3 min-w-0 flex-1">
                                    <span class="shrink-0 inline-flex h-7 w-7 items-center justify-center rounded-md text-xs font-semibold bg-gray-200/90 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $importedChallenges->firstItem() + $loop->index }}
                                    </span>
                                    <span class="min-w-0 flex-1 font-semibold leading-snug break-words sm:truncate sm:break-normal">
                                        {{ $importedChallenge->title }}
                                    </span>
                                    @if ($isNew)
                                    <span class="shrink-0 inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold tracking-wide uppercase bg-green-100 text-green-800 dark:bg-green-800/40 dark:text-green-300">
                                        New
                                    </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 pl-9 sm:pl-0 shrink-0 flex-wrap sm:flex-nowrap min-w-0">
                                    @if ($topicLabel !== '')
                                    <span class="max-w-[10rem] sm:max-w-[12rem] truncate inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold tracking-wide uppercase bg-gray-200/80 text-gray-700 dark:bg-gray-700/70 dark:text-gray-300" title="{{ $topicLabel }}">
                                        {{ $topicLabel }}
                                    </span>
                                    @endif
                                    @if ($difficultyName)
                                    <span class="shrink-0 inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold tracking-wide uppercase {{ $difficultyClasses }}">
                                        {{ ucfirst($difficultyName) }}
                                    </span>
                                    @endif
                                    <span class="shrink-0 hidden sm:inline text-xs font-normal text-gray-400 dark:text-gray-500 tabular-nums">
                                        #{{ $importedChallenge->id }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 text-base text-gray-400 dark:text-gray-500/90 mt-[0.2rem] sm:mt-0">
                            @if ($isExpanded)
                            <i class="caret down icon"></i>
                            @else
                            <i class="caret right icon"></i>
                            @endif
                        </div>
                    </button>

                    @if ($isExpanded)
                    <div class="pt-2 w-full min-w-0 overflow-x-auto text-left">
                        @livewire('challenge-card', ['challenge' => $importedChallenge], key('imported-challenge-card-'.$importedChallenge->id))
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if ($importedChallenges->hasPages())
        <div class="mt-5">
            {{ $importedChallenges->links(data: ['scrollTo' => false]) }}
        </div>
        @endif
        @else
        <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700/70 px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
            No challenges match your search
        </div>
        @endif
    </x-descr-list>
    @else
    <x-descr-list>
        <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700/70 px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
            No A.I. requested challenges yet
        </div>
    </x-descr-list>
    @endif
</div>
