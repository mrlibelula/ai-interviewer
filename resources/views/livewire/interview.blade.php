<div class="flex flex-col gap-y-10">
    <x-header-banner>
        <x-slot name="icon">
            <x-icon-rocket class="w-8 h-8 md:w-12 md:h-12 text-green-700 dark:text-green-400" />
        </x-slot>
        Choose your Learning Path
        <x-slot name="right">
            @if ($selected_topic_id && $selected_topic_id !== -1)
            <!-- not using wire:navigate because of chatbot animation bug, needs to reload entire page -->
            <a href="{{ route('interview-start', [
                \App\Tool::encode($selected_difficulty), 
                \App\Tool::encode($selected_topic_id), 
            ]) }}">
                <x-button class="flex justify-center">
                    <div class="text-xl px-8 w-full whitespace-nowrap">Start all</div>
                </x-button>
            </a>
            @else
            <x-secondary-button disabled class="flex justify-center">
                <div class="text-xl px-8 w-full whitespace-nowrap">Start all</div>
            </x-secondary-button>
            @endif
        </x-slot>
        
    </x-header-banner>

    <x-container>
        <div class="flex flex-col gap-y-16">
            <div class="flex flex-col gap-y-8">
                <p class=" text-center lg:text-left text-justify_ lg:text-2xl">
                    Receive <x-bold>real-time A.I. feedback</x-bold>, track time with a countdown timer, submit solutions promptly, and <x-bold>review results</x-bold> comprehensively for optimized technical interview preparation.
                </p>

                <x-descr-list>
                    <div class="flex p-4 flex-col lg:flex-row items-center lg:items-start justify-around gap-x-10 gap-y-8 lg:gap-y-0">
                        <div class="flex flex-col gap-y-4 text-center w-full lg:w-1/4">
                            <x-h5>Difficulty level</x-h5>
                            <select wire:model.live='selected_difficulty' class="form-select w-full md:text-2xl">
                                @foreach ($difficulties as $difficulty)
                                <option value="{{ strtolower($difficulty->name) }}">{{ ucfirst($difficulty->name) }} {{ ($nb = \App\Tool::challengesCountByDifficultyLevel($difficulty->name)) !== 0 ? '(' . $nb . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-y-4 text-center w-full lg:w-3/4">
                            <x-h5>Interview Topic</x-h5>
                            <select wire:model.live='selected_topic_id' class="form-select w-full md:text-2xl">
                                <option value="-1"> -- Available "{{ strtolower($selected_difficulty) }}" topics -- </option>
                                @foreach ($topics as $topic)
                                    @if ($topic->challenges_count)
                                    <option value="{{ $topic->id }}">{{ $topic->name }} ({{ $topic->challenges_count }} challenge{{ $topic->challenges_count === 1 ? '' : 's' }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>

                </x-descr-list>
                
                @if ($selected_challenges !== null)
                <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between gap-x-4">
                    <x-h5>Available A.I. Challenges ({{ count($selected_challenges) }})</x-h5>
                    <div class="flex items-center gap-x-4 flex-wrap">
                        <div class="w-full sm:w-56">
                            <input
                                type="search"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search challenges"
                                class="form-input dark:bg-gray-800 h-[2.25rem] w-full text-sm placeholder-gray-500 placeholder:text-sm"
                            />
                        </div>
                        <div class="flex items-center gap-x-4">
                            <x-btn-list 
                                :active="$challenge_list_order === 'list' ? true : false" 
                                wire:click="changeChallengeListOrderTo('list')"
                            />
                            <x-btn-squares 
                                :active="$challenge_list_order === 'squares' ? true : false" 
                                wire:click="changeChallengeListOrderTo('squares')"
                            />
                        </div>
                    </div>
                </div>

                    @if (count($selected_challenges) === 0)
                    <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700/70 px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        @if (filled($search))
                            No challenges match your search
                        @else
                            No challenges available for this topic
                        @endif
                    </div>
                    @elseif ($challenge_list_order === 'list')
                    <!-- challenges - list view -->
                    <div class=" text-center">
                        <x-table class="bg-gray-300/30 dark:bg-gray-800/70 w-full">
                            @foreach ($selected_challenges as $challenge)
                            @php
                                $mainTopic = $challenge->topics->firstWhere('id', $selected_topic_id)
                                    ?? $challenge->topics->first();
                            @endphp
                            <tr class="group font-semibold hover:bg-gray-300/70 hover:dark:bg-gray-700 smooth-300">
                                <td class="py-6 px-1 w-16 text-base opacity-60">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-6 px-2 text-left">
                                    <div class="flex items-center gap-x-6">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-x-2.5 gap-y-1.5 flex-wrap">
                                                @if ($challenge->languages->count())
                                                <div class="text-base font-mono tracking-wide text-green-600 dark:text-green-400">
                                                    @foreach ($challenge->languages as $language)
                                                    {{ $language->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </div>
                                                @endif
                                                @if ($mainTopic)
                                                <span
                                                    class="inline-flex max-w-[16rem] truncate items-center rounded-full px-2.5 py-1 text-[11px] font-semibold tracking-wide leading-none bg-sky-100/90 text-sky-800 ring-1 ring-inset ring-sky-600/15 dark:bg-sky-500/15 dark:text-sky-300 dark:ring-sky-400/20"
                                                    title="{{ $mainTopic->name }}"
                                                >
                                                    {{ $mainTopic->name }}
                                                </span>
                                                @endif
                                            </div>
                                            <div class="text-2xl dark:text-gray-300 group-hover:text-gray-950 group-hover:dark:text-gray-100 smooth-300">
                                                {{ $challenge->title }}
                                            </div>
                                            <div class="text-sm flex items-center gap-x-2 flex-wrap mt-2">
                                                @if (count($challenge->tags))
                                                    @foreach ($challenge->tags as $tag)
                                                    <div>#{{ ucfirst(Str::camel($tag->name)) }}</div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-2 w-36">
                                    @if (\App\Tool::isChallengeSolved($challenge))
                                    <div class="flex items-center gap-x-3 justify-end">
                                        <x-icon-star class="w-[2rem] h-[2rem] animate-star" fill="currentColor" />
                                        {{-- <x-icon-shield /> --}}
                                    </div>
                                    @endif
                                </td>
                                <td class="py-6 px-2 w-32 font-mono text-base text-left group-hover:text-gray-950 group-hover:dark:text-gray-100 smooth-300">
                                    <!-- not using wire:navigate because of chatbot animation bug, needs to reload entire page -->
                                    <a href="{{ route('interview-start', [
                                        \App\Tool::encode($selected_difficulty), 
                                        \App\Tool::encode($selected_topic_id), 
                                        \App\Tool::encode($challenge->id), 
                                        $challenge->challenge_slug, 
                                    ]) }}">
                                        <x-secondary-button>
                                            <div class=" text-sm">
                                                Start
                                            </div>
                                        </x-secondary-button>
                                    </a>
                                </td>                            
                            </tr>
                            @endforeach
                        </x-table>
                    </div>
                    @else
                    <!-- challenges - squares view -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-12 items-stretch auto-rows-fr">
                        @foreach ($selected_challenges as $challenge)
                        @php
                            $is_solved = (bool)\App\Tool::isChallengeSolved($challenge);
                            $languages = $challenge->languages;
                            $mainTopic = $challenge->topics->firstWhere('id', $selected_topic_id)
                                ?? $challenge->topics->first();
                            $challengeUrl = route('interview-start', [
                                \App\Tool::encode($selected_difficulty),
                                \App\Tool::encode($selected_topic_id),
                                \App\Tool::encode($challenge->id),
                                $challenge->challenge_slug,
                            ]);
                            $visibleTagLimit = 4;
                            $tagCount = count($challenge->tags);
                            $hasExtraTags = $tagCount > $visibleTagLimit;
                        @endphp
                        <div
                            wire:key="challenge-square-{{ $challenge->id }}"
                            x-data="{
                                tagsExpanded: false,
                                descExpanded: false,
                                needsDescToggle: false,
                                init() {
                                    this.$nextTick(() => this.measureDesc());
                                },
                                measureDesc() {
                                    if (!this.$refs.desc) return;
                                    const wasExpanded = this.descExpanded;
                                    this.descExpanded = false;
                                    this.$nextTick(() => {
                                        this.needsDescToggle = this.$refs.desc.scrollHeight > this.$refs.desc.clientHeight + 2;
                                        this.descExpanded = wasExpanded;
                                    });
                                },
                            }"
                            class="group flex flex-col p-0.5 rounded-lg w-full h-full bg-gradient-to-br {{ $is_solved ? ' from-emerald-500 to-gray-100 dark:from-[#1a6ef9] dark:to-gray-950' : ' from-gray-300 to-gray-100 dark:from-gray-600 dark:to-gray-950' }} smooth-300 opacity-[0.80] hover:opacity-100 overflow-hidden shadow-md"
                        >
                            <div class="flex flex-col flex-1 min-h-0 p-6 rounded-lg bg-white/80 dark:bg-black/80 w-full h-full">
                                <div class="flex items-start justify-between gap-x-6 flex-1 min-h-0">
                                    <div class="w-full text-left min-w-0 flex flex-col flex-1 min-h-0">
                                        <div class="flex items-center gap-x-2.5 gap-y-1.5 flex-wrap">
                                            @if ($languages->count())
                                            <div class="flex items-center text-sm dark:text-emerald-400 tracking-wide">
                                                @foreach ($languages as $language)
                                                <div>
                                                    {{ $language->name }}{{ !$loop->last ? ', ' : '' }}
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            @if ($mainTopic)
                                            <span
                                                class="inline-flex max-w-[12rem] truncate items-center rounded-full px-2.5 py-1 text-[11px] font-semibold tracking-wide leading-none bg-sky-100/90 text-sky-800 ring-1 ring-inset ring-sky-600/15 dark:bg-sky-500/15 dark:text-sky-300 dark:ring-sky-400/20"
                                                title="{{ $mainTopic->name }}"
                                            >
                                                {{ $mainTopic->name }}
                                            </span>
                                            @endif
                                        </div>
                                        <div class="font-semibold leading-tight {{ $is_solved ? 'dark:text-gray-100 dark:group-hover:text-white' : 'dark:text-gray-300 dark:group-hover:text-white' }} text-[1.35rem] {{ $languages->count() || $mainTopic ? 'mt-1' : '' }}">
                                            {{ $challenge->title }}
                                        </div>
                                        @if ($tagCount)
                                        <div class="text-sm flex items-center gap-x-2 flex-wrap mt-2 opacity-70">
                                            @foreach ($challenge->tags as $tag)
                                            <div @if ($loop->index >= $visibleTagLimit) x-cloak x-show="tagsExpanded" @endif>
                                                #{{ ucfirst(Str::camel($tag->name)) }}
                                            </div>
                                            @endforeach
                                            @if ($hasExtraTags)
                                            <button
                                                type="button"
                                                class="text-sky-600 dark:text-sky-400 hover:underline font-medium"
                                                @click="tagsExpanded = !tagsExpanded"
                                                x-text="tagsExpanded ? 'Show less' : '+{{ $tagCount - $visibleTagLimit }} more'"
                                            ></button>
                                            @endif
                                        </div>
                                        @endif
                                        @if (filled($challenge->description))
                                        <div class="text-base mt-2">
                                            <div
                                                x-ref="desc"
                                                class="leading-relaxed"
                                                :class="descExpanded ? '' : 'line-clamp-3'"
                                            >
                                                {{ $challenge->description }}
                                            </div>
                                            <button
                                                type="button"
                                                x-cloak
                                                x-show="needsDescToggle"
                                                class="mt-1.5 text-sm font-semibold text-sky-600 dark:text-sky-400 hover:underline"
                                                @click="descExpanded = !descExpanded"
                                                x-text="descExpanded ? 'Read less' : 'Read more'"
                                            ></button>
                                        </div>
                                        @endif
                                    </div>
                                    @if ($is_solved)
                                    <div class="leading-tight shrink-0">
                                        <x-icon-star fill="currentColor" class="w-6 h-6 animate-star" />
                                    </div>
                                    @endif
                                </div>
                                <div class="mt-auto pt-4 border-t border-gray-200/80 dark:border-gray-700/60">
                                    <!-- not using wire:navigate because of chatbot animation bug, needs to reload entire page -->
                                    <a href="{{ $challengeUrl }}" class="inline-flex">
                                        <x-secondary-button>
                                            <div class="text-sm px-1">Take challenge</div>
                                        </x-secondary-button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                @endif
            </div>
            
        </div>
    </x-container>
</div>
